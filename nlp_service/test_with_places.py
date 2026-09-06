import json
from pathlib import Path
import torch
import torch.nn.functional as F
import numpy as np

from predict import (
    S1EmergencyNLPModel,
    EMBEDDING_DIM,
    HIDDEN_DIM,
    DROPOUT,
    MAX_LEN,
    text_to_sequence,
    extract_entities,
)

BASE_DIR = Path(__file__).resolve().parent
CKPT = BASE_DIR / "s1_emergency_bilstm_model0.2.with_places.pt"


def load_model_from_ckpt(ckpt_path):
    ckpt = torch.load(ckpt_path, map_location="cpu")
    token_to_id = ckpt["token_to_id"]
    vocab_size = len(token_to_id)

    model = S1EmergencyNLPModel(
        vocab_size=vocab_size,
        embedding_dim=EMBEDDING_DIM,
        hidden_dim=HIDDEN_DIM,
        dropout=DROPOUT,
    )
    state = ckpt["model_state_dict"]
    model.load_state_dict(state)
    model.eval()
    return model, token_to_id


def analyze_with_model(model, token_to_id, emergency_id, text):
    seq = text_to_sequence(text, max_len=MAX_LEN)
    seq_tensor = torch.tensor([seq], dtype=torch.long)
    with torch.inference_mode():
        type_logits, priority_logits, text_embedding = model(seq_tensor)
        priority_probs = F.softmax(priority_logits, dim=-1)[0].cpu().numpy()

    entities = extract_entities(text)
    return entities, priority_probs


if __name__ == "__main__":
    if not CKPT.exists():
        print("Expanded checkpoint not found:", CKPT)
        raise SystemExit(1)

    model, token_to_id = load_model_from_ckpt(CKPT)
    print("Loaded model with vocab size:", len(token_to_id))

    samples = [
        "حريق في حي السلام قرب المستشفى الرئيسي",
        "شارع الملك فيصل أمام محطة الشرطة",
        "Fire on Elm Street near Central Hospital",
    ]

    for s in samples:
        ents, pr = analyze_with_model(model, token_to_id, "EMG-TEST", s)
        print("TEXT:", s)
        print(json.dumps({"entities": ents}, ensure_ascii=False, indent=2))
        print()
