Libraries:

- Python 3.13.x
- TensorFlow 2.21.0
- NumPy
- Pillow
- Flask

Required files:

- models/image_model.keras
- outputs/class_names.json
- predict.py
- api.py

Outputs:

- image_type
- confidence
- (image_probs) class_probabilities
- image_features

Example:

{
"image_type": "Fire",
"confidence": 0.873421,
"class_probabilities": {
"Earthquake": 0.012341,
"Fire": 0.873421,
"Flood": 0.031245,
"No Emergency": 0.021553,
"Traffic Accident": 0.061440
},
"image_features": [256 values]
}


Class order:

1. Earthquake
2. Fire
3. Flood
4. No Emergency
5. Traffic Accident


Important:

- Keep the class order exactly the same when integrating with Fusion.
- image_features contains 256 values.
- If TensorFlow in not installed, the required TensorFlow package is available in the tensorFlow_packages folder

- Use:
  pip install --no--index --find-inkes=.\tensorflow_packages tensorflow
