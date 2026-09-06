import json
import re
from pathlib import Path

import numpy as np
import torch
import torch.nn as nn
import torch.nn.functional as F

# Optional translator: install with `pip install googletrans==4.0.0-rc1`
try:
    from googletrans import Translator
except Exception:
    Translator = None


# ============================================================
# Configuration
# ============================================================
BASE_DIR = Path(__file__).resolve().parent

MODEL_CANDIDATES = [
    BASE_DIR / "s1_emergency_bilstm_model0.2.with_places.pt",
    BASE_DIR / "s1_emergency_bilstm_model0.2.pt",
]

DEVICE = torch.device("cuda" if torch.cuda.is_available() else "cpu")
PAD_IDX = 0
UNK_IDX = 1
MAX_LEN = 50
EMBEDDING_DIM = 100
HIDDEN_DIM = 128
DROPOUT = 0.3


# ============================================================
# Model definition - must match the trained notebook model
# ============================================================
class MaskedAttention(nn.Module):
    def __init__(self, hidden_dim):
        super().__init__()
        self.attention = nn.Linear(hidden_dim * 2, 1)

    def forward(self, lstm_output, mask):
        attn_scores = self.attention(lstm_output).squeeze(-1)
        masked_scores = attn_scores.masked_fill(~mask, -1e9)
        attn_weights = F.softmax(masked_scores, dim=1).unsqueeze(-1)
        context = torch.sum(attn_weights * lstm_output, dim=1)
        return context, attn_weights


class S1EmergencyNLPModel(nn.Module):
    def __init__(
        self,
        vocab_size,
        embedding_dim=EMBEDDING_DIM,
        hidden_dim=HIDDEN_DIM,
        num_types=7,
        num_priorities=4,
        dropout=DROPOUT,
    ):
        super().__init__()
        self.embedding = nn.Embedding(
            vocab_size,
            embedding_dim,
            padding_idx=PAD_IDX,
        )
        self.bilstm = nn.LSTM(
            input_size=embedding_dim,
            hidden_size=hidden_dim,
            num_layers=2,
            batch_first=True,
            bidirectional=True,
            dropout=dropout,
        )
        self.attention = MaskedAttention(hidden_dim)
        self.dropout = nn.Dropout(dropout)
        self.type_head = nn.Linear(hidden_dim * 2, num_types)
        self.priority_head = nn.Linear(hidden_dim * 2, num_priorities)

    def forward(self, x):
        mask = x != PAD_IDX
        embedded = self.dropout(self.embedding(x))
        lstm_out, _ = self.bilstm(embedded)
        text_embedding, attn_weights = self.attention(lstm_out, mask)
        dropped = self.dropout(text_embedding)
        type_logits = self.type_head(dropped)
        priority_logits = self.priority_head(dropped)
        return type_logits, priority_logits, text_embedding


# ============================================================
# Load trained checkpoint
# ============================================================
def _find_model_path():
    for path in MODEL_CANDIDATES:
        if path.exists():
            return path
    names = ", ".join(path.name for path in MODEL_CANDIDATES)
    raise FileNotFoundError(
        f"Model checkpoint not found. Expected one of: {names}"
    )


MODEL_PATH = _find_model_path()
CHECKPOINT = torch.load(MODEL_PATH, map_location=DEVICE)

TOKEN_TO_ID = CHECKPOINT["token_to_id"]
OFFICIAL_TYPES = CHECKPOINT["official_types"]
OFFICIAL_PRIORITIES = CHECKPOINT["official_priorities"]

ID_TO_TYPE = {i: value for i, value in enumerate(OFFICIAL_TYPES)}
ID_TO_PRIO = {i: value for i, value in enumerate(OFFICIAL_PRIORITIES)}

model = S1EmergencyNLPModel(
    vocab_size=len(TOKEN_TO_ID),
    embedding_dim=EMBEDDING_DIM,
    hidden_dim=HIDDEN_DIM,
    num_types=len(OFFICIAL_TYPES),
    num_priorities=len(OFFICIAL_PRIORITIES),
    dropout=DROPOUT,
).to(DEVICE)

model.load_state_dict(CHECKPOINT["model_state_dict"])
model.eval()


# ============================================================
# Text preprocessing - same logic as the notebook
# ============================================================
def text_to_sequence(text, max_len=MAX_LEN):
    tokens = str(text).lower().strip().split()
    seq = [TOKEN_TO_ID.get(word, UNK_IDX) for word in tokens[:max_len]]

    if len(seq) < max_len:
        seq += [PAD_IDX] * (max_len - len(seq))

    return seq


# ============================================================
# Entity extraction - same logic as the notebook
# ============================================================
def extract_entities(text: str) -> list:
    entities = []
    text_value = str(text).strip()

    number_matches = re.findall(
        r'\b\d+\s*(?:people|persons|casualties|injured|dead|families|cars|vehicles|floors?|houses?|مصاب|قتيل|أسرة|شخص|أشخاص|طوابق?)?\b',
        text_value,
        re.IGNORECASE | re.UNICODE,
    )

    for match in number_matches:
        value = match.strip()
        if value and value not in entities:
            entities.append(value)

    location_matches = re.findall(
        r'\b(?:sector|street|highway|block|district|city|hospital|bridge|area|road|avenue|lane|station|park|market|mall|square)\s*[A-Za-z0-9\-]+\b',
        text_value,
        re.IGNORECASE,
    )

    for location in location_matches:
        value = location.strip()
        if value and value not in entities:
            entities.append(value)

    arabic_location_matches = re.findall(
        r'((?:حي|شارع|طريق|منطقة|مدينة|مستشفى|جسر|ميدان|ساحة|بلدة|محطة|حديقة|سوق|مركز|مبنى)\s*[\u0600-\u06FF\w\-]+)',
        text_value,
        re.IGNORECASE | re.UNICODE,
    )

    for location in arabic_location_matches:
        value = location.strip()
        if value and value not in entities:
            entities.append(value)

    mixed_location_matches = re.findall(
        r'\b(?:حي|شارع|طريق|street|road|avenue|district|area|city|hospital|station)\s+[A-Za-z\u0600-\u06FF\-\s]+',
        text_value,
        re.IGNORECASE | re.UNICODE,
    )

    for location in mixed_location_matches:
        value = location.strip()
        if value and value not in entities:
            entities.append(value)

    location_names = re.findall(
        r'\b(?:[A-Z][a-z]+|[\u0600-\u06FF]+)(?:\s+(?:[A-Z][a-z]+|[\u0600-\u06FF]+)){0,2}\b',
        text_value,
        re.UNICODE,
    )
    for item in location_names:
        lowered = item.strip()
        if any(k in lowered.lower() for k in [
            'street', 'road', 'hospital', 'city', 'district', 'area', 'bridge',
            'حي', 'شارع', 'مستشفى', 'مدينة', 'منطقة', 'جسر', 'ميدان', 'ساحة'
        ]):
            if lowered and lowered not in entities:
                entities.append(lowered)

    return entities


def is_arabic(text: str) -> bool:
    return bool(re.search(r'[\u0600-\u06FF]', str(text)))


def detect_language(text: str) -> str:
    text_value = str(text)
    has_ar = is_arabic(text_value)
    has_en = bool(re.search(r'[A-Za-z]', text_value))
    if has_ar and has_en:
        return "mixed"
    if has_ar:
        return "arabic"
    if has_en:
        return "english"
    return "other"


def normalize_text_for_model(text: str) -> str:
    text_value = str(text).strip()
    if not text_value:
        return text_value

    if detect_language(text_value) == "arabic":
        if Translator is not None:
            try:
                translator = Translator()
                result = translator.translate(text_value, src='ar', dest='en')
                english_text = result.text
                if english_text and not is_arabic(english_text):
                    return english_text
            except Exception:
                pass
        return text_value

    if detect_language(text_value) == "mixed":
        if Translator is not None:
            try:
                translator = Translator()
                result = translator.translate(text_value, src='auto', dest='en')
                english_text = result.text
                if english_text and not is_arabic(english_text):
                    return english_text
            except Exception:
                pass
        return text_value

    return text_value


def translate_to_english(text: str) -> str:
    """Translate Arabic text to English using googletrans if available.

    Falls back to returning original text when translator isn't installed.
    """
    return normalize_text_for_model(text)


# ============================================================
# Priority urgency score - kept for the current API contract
# ============================================================
def compute_text_urgency_score(priority_probabilities: dict) -> int:
    score = (
        priority_probabilities.get("critical", 0.0) * 100
        + priority_probabilities.get("high", 0.0) * 75
        + priority_probabilities.get("medium", 0.0) * 45
        + priority_probabilities.get("low", 0.0) * 10
    )
    return int(np.clip(round(score), 0, 100))


# ============================================================
# Main NLP prediction function used by text_api.py
# ============================================================
@torch.inference_mode()
def analyze_emergency(request_json: dict) -> dict:
    if not isinstance(request_json, dict):
        raise ValueError("request_json must be a dictionary")

    emergency_id = request_json.get("emergency_id")
    text = request_json.get("text")

    if not emergency_id:
        raise ValueError("emergency_id is required")

    if text is None or not str(text).strip():
        raise ValueError("text is required")

    # Accept Arabic, English, and mixed-language text.
    original_text = text
    language = detect_language(text)
    text_for_model = normalize_text_for_model(text)

    if language == "other":
        text_for_model = text

    sequence = text_to_sequence(text_for_model, max_len=MAX_LEN)
    sequence_tensor = torch.tensor(
        [sequence],
        dtype=torch.long,
        device=DEVICE,
    )

    type_logits, priority_logits, text_embedding = model(sequence_tensor)

    # Emergency type probabilities
    type_probs = F.softmax(type_logits, dim=-1)[0].cpu().numpy()
    predicted_type_index = int(np.argmax(type_probs))
    detected_type = ID_TO_TYPE[predicted_type_index]

    # Priority probabilities
    priority_probs = F.softmax(priority_logits, dim=-1)[0].cpu().numpy()
    priority_probabilities = {
        ID_TO_PRIO[i]: round(float(probability), 4)
        for i, probability in enumerate(priority_probs)
    }

    top_priority = max(
        priority_probabilities,
        key=priority_probabilities.get,
    )

    # Text embedding generated by the attention layer
    embedding = (
        text_embedding[0]
        .detach()
        .cpu()
        .numpy()
        .astype(float)
        .tolist()
    )

    return {
        "emergency_id": emergency_id,
        "language": detect_language(original_text),
        "text_urgency": compute_text_urgency_score(priority_probabilities),
        "emergency_type": detected_type,
        "priority": top_priority,
        "priority_probabilities": priority_probabilities,
        "text_embedding": embedding,
        "entities": extract_entities(original_text),
    }


if __name__ == "__main__":
    emergency_id = input("Enter Emergency ID (e.g. EMG-000001): ").strip()
    text = input("Enter Emergency Description Text: ").strip()

    result = analyze_emergency({
        "emergency_id": emergency_id,
        "text": text,
    })

    print(json.dumps(result, ensure_ascii=False, indent=4))
