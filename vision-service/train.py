import json
import random
from pathlib import Path

import numpy as np
import pandas as pd
import tensorflow as tf

from sklearn.model_selection import train_test_split
from sklearn.metrics import (
    accuracy_score,
    precision_recall_fscore_support
)


DATASET_DIR = Path("Data")

OUTPUT_DIR = Path("outputs")
MODEL_DIR = Path("models")

OUTPUT_DIR.mkdir(exist_ok=True)
MODEL_DIR.mkdir(exist_ok=True)

TRAIN_CSV_PATH = OUTPUT_DIR / "train4.csv"
VAL_CSV_PATH = OUTPUT_DIR / "validation4.csv"
TEST_CSV_PATH = OUTPUT_DIR / "test4.csv"
CLASS_NAMES_PATH = OUTPUT_DIR / "class_names.json"
MODEL_PATH = MODEL_DIR / "image_model5.keras"

IMAGE_SIZE = 224

BATCH_SIZE = 32

EPOCHS = 28

LEARNING_RATE = 1e-4

TRAIN_RATIO = 0.70
VAL_RATIO = 0.15
TEST_RATIO = 0.15

SEED = 42


random.seed(SEED)
np.random.seed(SEED)
tf.random.set_seed(SEED)


print("=" * 60)

print(
    "TensorFlow version:",
    tf.version
)

print(
    "GPU:",
    tf.config.list_physical_devices("GPU")
)

print("=" * 60)


IMAGE_EXTENSIONS = {
    ".jpg",
    ".jpeg",
    ".png",
    ".bmp",
    ".webp"
}


def load_images(dataset_dir):

    records = []

    class_dirs = sorted(
        [
            p for p in dataset_dir.iterdir()
            if p.is_dir()
        ]
    )

    if not class_dirs:

        raise RuntimeError(
            "No class folders found."
        )

    class_names = [
        folder.name
        for folder in class_dirs
    ]

    class_to_index = {
        name: index
        for index, name
        in enumerate(class_names)
    }

    for class_folder in class_dirs:

        label = class_to_index[
            class_folder.name
        ]

        for image_path in class_folder.rglob("*"):

            if (
                image_path.suffix.lower()
                in IMAGE_EXTENSIONS
            ):

                records.append({

                    "image_path":
                        str(image_path),

                    "label":
                        label
                })

    dataframe = pd.DataFrame(
        records
    )

    if dataframe.empty:

        raise RuntimeError(
            "No images found."
        )

    return dataframe, class_names


df, class_names = load_images(
    DATASET_DIR
)

NUM_CLASSES = len(
    class_names
)


print("\nClasses:")

for i, name in enumerate(
    class_names
):

    print(
        f"{i}: {name}"
    )


print(
    "\nTotal images:",
    len(df)
)


train_df, temporary_df = train_test_split(

    df,

    test_size=(
        VAL_RATIO +
        TEST_RATIO
    ),

    stratify=df["label"],

    random_state=SEED
)


test_ratio_inside_temporary = (

    TEST_RATIO /
    (
        VAL_RATIO +
        TEST_RATIO
    )
)


val_df, test_df = train_test_split(

    temporary_df,

    test_size=test_ratio_inside_temporary,

    stratify=temporary_df["label"],

    random_state=SEED
)


train_df = train_df.reset_index(
    drop=True
)

val_df = val_df.reset_index(
    drop=True
)

test_df = test_df.reset_index(
    drop=True
)


print("\nDataset split:")

print(
    "Train:",
    len(train_df)
)

print(
    "Validation:",
    len(val_df)
)

print(
    "Test:",
    len(test_df)
)


train_df.to_csv(
    TRAIN_CSV_PATH,
    index=False
)

val_df.to_csv(
    VAL_CSV_PATH,
    index=False
)

test_df.to_csv(
    TEST_CSV_PATH,
    index=False
)


data_augmentation = tf.keras.Sequential([

    tf.keras.layers.RandomFlip(
        "horizontal"
    ),

    tf.keras.layers.RandomRotation(
        0.055
    ),

    tf.keras.layers.RandomZoom(
        0.2
    ),

    tf.keras.layers.RandomTranslation(
        0.1,
        0.1
    )
])


def load_and_preprocess(
    image_path,
    label,
    training=False
):

    image = tf.io.read_file(
        image_path
    )

    image = tf.image.decode_image(
        image,
        channels=3,
        expand_animations=False
    )

    image = tf.image.resize(
        image,
        [IMAGE_SIZE, IMAGE_SIZE]
    )

    image = tf.cast(
        image,
        tf.float32
    )

    image = image / 255.0

    if training:
        image = data_augmentation(
            image,
            training=True
        )

    return image, label


def create_dataset(
    dataframe,
    training=False
):

    paths = dataframe[
        "image_path"
    ].values

    labels = dataframe[
        "label"
    ].values


    dataset = (
        tf.data.Dataset
        .from_tensor_slices(
            (
                paths,
                labels
            )
        )
    )


    dataset = dataset.map(

        lambda image_path, label:

        load_and_preprocess(
            image_path,
            label,
            training
        ),

        num_parallel_calls=tf.data.AUTOTUNE
    )


    if training:

        dataset = dataset.shuffle(

            len(dataframe),

            seed=SEED
        )


    dataset = dataset.batch(
        BATCH_SIZE
    )


    dataset = dataset.prefetch(
        tf.data.AUTOTUNE
    )


    return dataset


train_dataset = create_dataset(
    train_df,
    training=True
)

validation_dataset = create_dataset(
    val_df,
    training=False
)

test_dataset = create_dataset(
    test_df,
    training=False
)


class_counts = (

    train_df["label"]

    .value_counts()

    .sort_index()

    .values
)


class_weights = {

    i:

    len(train_df)
    /
    (
        NUM_CLASSES *
        class_counts[i]
    )

    for i in range(NUM_CLASSES)
}


print("\nClass weights:")

for index, weight in class_weights.items():

    print(
        f"{class_names[index]}: {weight:.4f}"
    )


inputs = tf.keras.Input(

    shape=(
        IMAGE_SIZE,
        IMAGE_SIZE,
        3
    ),

    name="image_input"
)


x = tf.keras.layers.Conv2D(

    32,
    (3, 3),

    activation="relu",

    padding="same",

    name="conv1"

)(inputs)


x = tf.keras.layers.MaxPooling2D(

    (2, 2),

    name="pool1"

)(x)


x = tf.keras.layers.Conv2D(

    64,
    (3, 3),

    activation="relu",

    padding="same",

    name="conv2"

)(x)


x = tf.keras.layers.MaxPooling2D(

    (2, 2),

    name="pool2"

)(x)


x = tf.keras.layers.Conv2D(

    128,
    (3, 3),

    activation="relu",

    padding="same",

    name="conv3"

)(x)


x = tf.keras.layers.MaxPooling2D(

    (2, 2),

    name="pool3"

)(x)


x = tf.keras.layers.Conv2D(

    256,
    (3, 3),

    activation="relu",

    padding="same",

    name="conv4"

)(x)


x = tf.keras.layers.GlobalAveragePooling2D(

    name="global_average_pooling"

)(x)


image_embedding = tf.keras.layers.Dense(

    256,

    activation="relu",

    name="image_embedding"

)(x)


embedding_dropout = tf.keras.layers.Dropout(

    0.3,

    name="embedding_dropout"

)(image_embedding)


classification = tf.keras.layers.Dense(

    NUM_CLASSES,

    activation="softmax",

    name="classification"

)(embedding_dropout)


model = tf.keras.Model(

    inputs=inputs,

    outputs=[
        classification,
        image_embedding
    ],

    name="model_image"
)


classification_model = tf.keras.Model(

    inputs=model.input,

    outputs=classification,

    name="model_image_Classifier"

)


classification_model.compile(

    optimizer=tf.keras.optimizers.Adam(

        learning_rate=LEARNING_RATE

    ),

    loss="sparse_categorical_crossentropy",

    metrics=["accuracy"]
)


print(
    "\nModel architecture:\n"
)

model.summary()


# early_stopping = tf.keras.callbacks.EarlyStopping(

#     monitor="val_loss",

#     patience=5,

#     restore_best_weights=True,

#     verbose=1

# )

checkpoint= tf.keras.callbacks.ModelCheckpoint(
    MODEL_PATH,
    monitor="val_accuracy",
    save_best_only=True,
    mode="max",
    verbose=1
)

print(
    "\nStarting training...\n"
)


history = classification_model.fit(

    train_dataset,

    validation_data=validation_dataset,

    epochs=EPOCHS,

    class_weight=class_weights,

    callbacks=[
        checkpoint
    ]

)


test_loss, test_accuracy = (

    classification_model.evaluate(

        test_dataset,

        verbose=1

    )

)


print(
    "\nTest Loss:",
    test_loss
)

print(
    "Test Accuracy:",
    test_accuracy
)


all_labels = []

all_predictions = []


for images, labels in test_dataset:

    predictions = (

        classification_model.predict(

            images,
            verbose=0

        )

    )


    predictions = np.argmax(

        predictions,

        axis=1

    )


    all_labels.extend(
        labels.numpy()
    )

    all_predictions.extend(
        predictions
    )


accuracy = accuracy_score(

    all_labels,

    all_predictions

)


precision, recall, f1, _ = (

    precision_recall_fscore_support(

        all_labels,

        all_predictions,

        average="macro",

        zero_division=0

    )

)


print(
    f"\nAccuracy:  {accuracy:.4f}"
)

print(
    f"Precision: {precision:.4f}"
)

print(
    f"Recall:    {recall:.4f}"
)

print(
    f"F1 Score:  {f1:.4f}"
)


model.save(
    MODEL_PATH
)


with open(

    CLASS_NAMES_PATH,

    "w",

    encoding="utf-8"

) as file:

    json.dump(

        class_names,

        file,

        ensure_ascii=False,

        indent=4

    )


print(
    "\nTraining completed."
)

print(
    "Model saved to:",
    MODEL_PATH
)

print(
    "Class names saved to:",
    CLASS_NAMES_PATH
)

print(
    "Train split saved to:",
    TRAIN_CSV_PATH
)

print(
    "Validation split saved to:",
    VAL_CSV_PATH
)

print(
    "Test split saved to:",
    TEST_CSV_PATH

)