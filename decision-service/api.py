# -*- coding: utf-8 -*-
"""
api.py
=======
واجهة REST API لمحرك الدمج والقرار، مطابقة تمامًا لعقد الفريق:
    POST /decision/analyze

مثال جسم الطلب (Request):
{
  "emergency_id": "EMG-000001",
  "nlp": {
    "text_urgency": 85,
    "emergency_type": "Fire",
    "priority": "critical",
    "entities": []
  },
  "vision": {
    "image_type": "Fire",
    "confidence": 0.94
  },
  "location": {
    "latitude": 15.3694,
    "longitude": 44.1910
  }
}

ملاحظة: "nlp" أو "vision" قد يكون غائبًا (حالة Single-Source)، لكن يجب توفر واحد منهما على الأقل.
"""

from flask import Flask, request, jsonify
from decision_engine import DecisionEngine

app = Flask(__name__)
engine = DecisionEngine()


@app.route("/decision/analyze", methods=["POST"])
def analyze():
    data = request.get_json(silent=True)

    if not data:
        return jsonify({"error": "جسم الطلب يجب أن يكون JSON صالحًا"}), 400

    emergency_id = data.get("emergency_id")
    nlp = data.get("nlp")
    vision = data.get("vision")
    location = data.get("location")

    try:
        result = engine.decide(
            emergency_id=emergency_id,
            nlp=nlp,
            vision=vision,
            location=location,
        )
        return jsonify(result.to_dict()), 200

    except ValueError as e:
        return jsonify({"emergency_id": emergency_id, "error": str(e)}), 400
    except Exception as e:
        return jsonify({"emergency_id": emergency_id, "error": f"خطأ داخلي: {str(e)}"}), 500


@app.route("/health", methods=["GET"])
def health():
    return jsonify({"status": "ok", "service": "decision-fusion-engine"}), 200


if __name__ == "__main__":
    # تشغيل محلي للتطوير والاختبار
    app.run(host="0.0.0.0", port=5003, debug=True)
