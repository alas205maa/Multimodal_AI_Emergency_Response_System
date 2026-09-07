import json
from pathlib import Path


# Path to resources.json
RESOURCES_FILE = Path(__file__).resolve().parents[2] / "data" / "resources" / "resources.json"


def load_resources():
    """Load and validate resources from resources.json."""

    if not RESOURCES_FILE.exists():
        raise FileNotFoundError(
            f"Resources file not found: {RESOURCES_FILE}"
        )

    with open(RESOURCES_FILE, "r", encoding="utf-8") as file:
        resources = json.load(file)

    if not isinstance(resources, list):
        raise ValueError("resources.json must contain a list of resources.")

    required_fields = {
        "resource_id",
        "resource_name",
        "resource_type",
        "latitude",
        "longitude",
    }

    valid_resources = []

    for resource in resources:

        missing = required_fields - resource.keys()

        if missing:
            print(
                f"Skipping resource {resource.get('resource_id', 'UNKNOWN')} "
                f"- missing fields: {missing}"
            )
            continue

        # Validate coordinates
        try:
            latitude = float(resource["latitude"])
            longitude = float(resource["longitude"])
        except (TypeError, ValueError):
            print(
                f"Skipping resource {resource['resource_id']} "
                f"- invalid coordinates"
            )
            continue

        # Validate coordinate range
        if not (-90 <= latitude <= 90):
            print(
                f"Skipping resource {resource['resource_id']} "
                f"- invalid latitude"
            )
            continue

        if not (-180 <= longitude <= 180):
            print(
                f"Skipping resource {resource['resource_id']} "
                f"- invalid longitude"
            )
            continue

        # Fix missing/NaN names
        name = resource["resource_name"]

        if name is None or str(name).strip().lower() in {"nan", "none", ""}:
            name = f"{resource['resource_type']} {resource['resource_id']}"

        resource["resource_name"] = name
        resource["latitude"] = latitude
        resource["longitude"] = longitude

        valid_resources.append(resource)

    print(f"Loaded {len(valid_resources)} valid resources.")

    return valid_resources


def get_resources_by_type(resource_type):
    """Return resources matching a specific response/resource type."""

    resources = load_resources()

    return [
        resource
        for resource in resources
        if resource["resource_type"] == resource_type
    ]


if __name__ == "__main__":

    resources = load_resources()

    print("\nResource summary:")

    resource_types = {}

    for resource in resources:
        resource_type = resource["resource_type"]
        resource_types[resource_type] = (
            resource_types.get(resource_type, 0) + 1
        )

    for resource_type, count in resource_types.items():
        print(f"- {resource_type}: {count}")