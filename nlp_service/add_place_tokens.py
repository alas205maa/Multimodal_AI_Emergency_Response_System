import json
import re
from pathlib import Path
import torch
import numpy as np

BASE_DIR = Path(__file__).resolve().parent
MODEL_PATH = BASE_DIR / "s1_emergency_bilstm_model0.2.pt"
ROUTING_CACHE = BASE_DIR.parent / "routing" / "cache"
RESOURCES_DIR = BASE_DIR.parent / "routing" / "data" / "resources"


def collect_place_tokens():
    tokens = set()

    # Scan routing cache JSON files for place names
    if ROUTING_CACHE.exists():
        for p in ROUTING_CACHE.glob("*.json"):
            try:
                data = json.loads(p.read_text(encoding="utf-8"))
            except Exception:
                continue
            # search common keys
            for key in ["name", "place", "address", "display_name"]:
                if key in data and isinstance(data[key], str):
                    tokens.update(tokenize_name(data[key]))
            # also scan all string values
            for v in data.values():
                if isinstance(v, str) and len(v) > 1:
                    tokens.update(tokenize_name(v))

    # Scan resources JSON
    if RESOURCES_DIR.exists():
        for fname in ["resources.json", "resources_with_nodes.json"]:
            p = RESOURCES_DIR / fname
            if not p.exists():
                continue
            try:
                arr = json.loads(p.read_text(encoding="utf-8"))
            except Exception:
                continue
            if isinstance(arr, list):
                for r in arr:
                    if isinstance(r, dict):
                        for key in ["name", "facility_name", "display_name"]:
                            if key in r and isinstance(r[key], str):
                                tokens.update(tokenize_name(r[key]))

    # Filter tokens: sensible length and characters
    filtered = set()
    for t in tokens:
        tt = t.strip()
        if not tt:
            continue
        if len(tt) > 40:
            continue
        filtered.add(tt)

    return sorted(filtered)


def tokenize_name(name: str):
    # split on whitespace and punctuation, keep Arabic words as-is
    parts = re.split(r"[\s,;:/\\()\[\]\"]+", name)
    out = []
    for p in parts:
        p = p.strip()
        if not p:
            continue
        out.append(p)
    return out


def expand_vocab_and_embedding():
    if not MODEL_PATH.exists():
        print("Model checkpoint not found at", MODEL_PATH)
        return

    ckpt = torch.load(MODEL_PATH, map_location="cpu")
    token_to_id = dict(ckpt.get("token_to_id", {}))
    embedding_dim = ckpt.get("embedding_dim", None)

    # sometimes embedding_dim not saved; default to 100 to match predict.py
    if embedding_dim is None:
        embedding_dim = 100

    print(f"Loaded checkpoint. vocab size = {len(token_to_id)}")

    place_tokens = collect_place_tokens()
    print(f"Collected {len(place_tokens)} place token candidates from routing data")

    # derive word-level tokens from place_tokens
    new_tokens = []
    for name in place_tokens:
        for part in tokenize_name(name):
            p_lower = part.lower()
            if p_lower not in token_to_id and p_lower not in new_tokens:
                new_tokens.append(p_lower)

    if not new_tokens:
        print("No new tokens to add.")
        return

    old_vocab_size = len(token_to_id)
    for tok in new_tokens:
        token_to_id[tok] = len(token_to_id)

    new_vocab_size = len(token_to_id)
    print(f"Adding {len(new_tokens)} tokens. New vocab size = {new_vocab_size}")

    # Load old model_state_dict and expand embedding weights
    old_state = ckpt.get("model_state_dict")
    if old_state is None:
        print("Checkpoint missing model_state_dict")
        return

    old_emb = None
    for k, v in old_state.items():
        if k.endswith("embedding.weight"):
            old_emb = v
            break

    if old_emb is None:
        print("Could not find embedding.weight in checkpoint state dict")
        return

    old_emb = old_emb.cpu().numpy()
    if old_emb.shape[1] != embedding_dim:
        print("Warning: embedding dim mismatch; using checkpoint dimension instead")
        embedding_dim = old_emb.shape[1]

    # initialize new embedding matrix
    rng = np.random.default_rng(42)
    new_emb = rng.normal(scale=0.01, size=(new_vocab_size, embedding_dim)).astype(np.float32)
    new_emb[:old_emb.shape[0], :] = old_emb

    # update state dict: replace embedding.weight with new tensor
    new_state = dict(old_state)
    # find exact key name for embedding.weight
    emb_key = None
    for k in old_state.keys():
        if k.endswith("embedding.weight"):
            emb_key = k
            break
    new_state[emb_key] = torch.from_numpy(new_emb)

    # save updated checkpoint
    new_ckpt_path = MODEL_PATH.with_name(MODEL_PATH.stem + ".with_places.pt")
    ckpt["token_to_id"] = token_to_id
    ckpt["model_state_dict"] = new_state
    ckpt["vocab_size"] = new_vocab_size
    ckpt["added_place_tokens"] = new_tokens

    torch.save(ckpt, new_ckpt_path)
    print("Saved expanded checkpoint to:", new_ckpt_path)
    print(f"Added tokens example (first 20): {new_tokens[:20]}")


if __name__ == "__main__":
    expand_vocab_and_embedding()
