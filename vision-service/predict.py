import json
from pathlib import Path

import numpy as np
import tensorflow as tf


# MODIFIED
MODEL_PATH = Path(
    "models/image_model.keras"
)

CLASS_NAMES_PATH = Path(
    "outputs/class_names.json"
)

IMAGE_SIZE = 224


with open(
    CLASS_NAMES_PATH,
    "r",
    encoding="utf-8"
) as file:

    class_names = json.load(file)


model = tf.keras.models.load_model(
    MODEL_PATH,
    compile=False
)


def preprocess_image(
    image_path
):

    image = tf.io.read_file(
        str(image_path)
    )

    image = tf.image.decode_image(
        image,
        channels=3,
        expand_animations=False
    )

    image = tf.image.resize(
        image,
        [
            IMAGE_SIZE,
            IMAGE_SIZE
        ]
    )

    image = tf.cast(
        image,
        tf.float32
    )

    image = image / 255.0

    image = tf.expand_dims(
        image,
        axis=0
    )

    return image


def predict_image(
    image_path
):

    image_tensor = preprocess_image(
        image_path
    )

    predictions = model.predict(
        image_tensor,
        verbose=0
    )

    probabilities = predictions[0]
    embedding = predictions[1]

    predicted_index = int(
        np.argmax(
            probabilities[0]
        )
    )

    predicted_class = (
        class_names[
            predicted_index
        ]
    )

    confidence = float(
        probabilities[
            0,
            predicted_index
        ]
    )

    class_probabilities = {}

    for i, class_name in enumerate(
        class_names
    ):

        class_probabilities[
            class_name
        ] = round(
            float(
                probabilities[
                    0,
                    i
                ]
            ),
            6
        )

    image_embedding = (
        embedding[0]
        .astype(float)
        .tolist()
    )

    result = {

        "image_features":
            image_embedding,

        "emergency_type":
            predicted_class,

        "confidence":
            round(
                confidence,
                6
            ),

        "class_probabilities":
            class_probabilities

    }

    return result
