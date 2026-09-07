import os
import json
import osmnx as ox


OUTPUT_FILE = "data/resources/resources.json"


# Bounding Box تقريبي لمنطقة صنعاء
NORTH = 15.45
SOUTH = 15.25
EAST = 44.30
WEST = 44.10


def get_resources():

    print("Downloading resources from OpenStreetMap...")

    queries = {
        "Fire_Rescue": {"amenity": "fire_station"},
        "Ambulance": {"amenity": "hospital"},
        "Rescue_Team": {"amenity": "police"}
    }

    resources = []
    resource_id = 1

    for resource_type, tags in queries.items():

        print(f"\nSearching for {resource_type}...")

        try:

            gdf = ox.features_from_bbox(
                bbox=(WEST, SOUTH, EAST, NORTH),
                tags=tags
            )

            print(f"Found: {len(gdf)}")

            for _, row in gdf.iterrows():

                geometry = row.geometry

                if geometry is None:
                    continue

                point = geometry.centroid

                name = row.get("name")

                if not name:
                    name = f"{resource_type} {resource_id}"

                resources.append({
                    "resource_id": resource_id,
                    "resource_name": str(name),
                    "resource_type": resource_type,
                    "latitude": float(point.y),
                    "longitude": float(point.x)
                })

                resource_id += 1

        except Exception as e:

            print(f"Error retrieving {resource_type}:")
            print(e)

    os.makedirs(
        os.path.dirname(OUTPUT_FILE),
        exist_ok=True
    )

    with open(
        OUTPUT_FILE,
        "w",
        encoding="utf-8"
    ) as file:

        json.dump(
            resources,
            file,
            ensure_ascii=False,
            indent=2
        )

    print("\n==============================")
    print("Finished!")
    print(f"Total resources: {len(resources)}")
    print(f"Saved to: {OUTPUT_FILE}")
    print("==============================")


if __name__ == "__main__":
    get_resources()