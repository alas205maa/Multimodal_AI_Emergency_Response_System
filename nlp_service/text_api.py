import sys
from pathlib import Path

from flask import Flask, jsonify, request

BASE_DIR = Path(__file__).resolve().parent
if str(BASE_DIR) not in sys.path:
    sys.path.insert(0, str(BASE_DIR))

try:
    from predict import analyze_emergency
except ModuleNotFoundError:  # pragma: no cover
    from nlp_service.predict import analyze_emergency

app = Flask(__name__)


@app.route("/health", methods=["GET"])
def health():
    return jsonify({
        "status": "ok",
        "service": "Emergency NLP API"
    }), 200


@app.route("/nlp/analyze", methods=["POST"])
def analyze_text_api():
    data = request.get_json(silent=True)

    if not isinstance(data, dict):
        return jsonify({
            "error": "JSON body is required"
        }), 400

    emergency_id = data.get("emergency_id")
    text = data.get("text")

    if not emergency_id:
        return jsonify({
            "error": "emergency_id is required"
        }), 400

    if text is None or not str(text).strip():
        return jsonify({
            "error": "text is required"
        }), 400

    try:
        result = analyze_emergency({
            "emergency_id": emergency_id,
            "text": text
        })
        return jsonify(result), 200

    except Exception as e:
        return jsonify({
            "error": str(e)
        }), 500


if __name__ == "__main__":
    app.run(
        host="0.0.0.0",
        port=5002,
        debug=False
    )
