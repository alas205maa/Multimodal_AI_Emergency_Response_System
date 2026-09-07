from flask import Flask, request, jsonify
from pathlib import Path
import base64
import uuid

from predict import predict_image


app = Flask(__name__)


@app.route("/health", methods=["GET"])
def health():
    return jsonify({
        "status": "ok",
        "service": "Image Classification API"
    })


@app.route("/vision/analyze", methods=["POST"])
def predict_image_api():

    data = request.get_json(silent=True)

    if not data:
        return jsonify({
            "error": "JSON body is required"
        }), 400

    emergency_id = data.get("emergency_id")
    image_base64 = data.get("image")

    if not emergency_id:
        return jsonify({
            "error": "emergency_id is required"
        }), 400

    if not image_base64:
        return jsonify({
            "error": "image is required"
        }), 400

    temp_dir = Path("temp")
    temp_dir.mkdir(exist_ok=True)

    image_path = temp_dir / f"{uuid.uuid4().hex}.jpg"

    try:

        if "," in image_base64:
            image_base64 = image_base64.split(",", 1)[1]

        image_data = base64.b64decode(image_base64)

        with open(image_path, "wb") as file:
            file.write(image_data)

        result = predict_image(
            image_path=image_path
        )

        result["emergency_id"] = emergency_id

        return jsonify(result), 200

    except Exception as e:

        return jsonify({
            "error": str(e)
        }), 500

    finally:

        if image_path.exists():
            image_path.unlink()


if __name__ == "__main__":
    app.run(
        host="0.0.0.0",
        port=5001,
        debug=True
    )

