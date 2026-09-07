import sys
from pathlib import Path

import folium
import osmnx as ox

# Add src to Python path
SRC_DIR = Path(__file__).resolve().parents[1]

if str(SRC_DIR) not in sys.path:
    sys.path.insert(0, str(SRC_DIR))

from resource_selection.select_resource import (
    load_resources,
    select_best_resource
)


BASE_DIR = Path(__file__).resolve().parents[2]

GRAPH_FILE = (
    BASE_DIR
    / "data"
    / "maps"
    / "sanaa_drive.graphml"
)

OUTPUT_FILE = (
    BASE_DIR
    / "data"
    / "maps"
    / "emergency_route.html"
)


def create_route_map(
    graph,
    emergency_latitude,
    emergency_longitude,
    route_result
):
    resource = route_result["resource"]
    path = route_result["path"]

    # Create map centered on emergency location
    emergency_map = folium.Map(
        location=[
            emergency_latitude,
            emergency_longitude
        ],
        zoom_start=14
    )

    # Emergency marker
    folium.Marker(
        location=[
            emergency_latitude,
            emergency_longitude
        ],
        popup="Emergency Location",
        tooltip="Emergency",
        icon=folium.Icon(
            icon="warning",
            prefix="fa"
        )
    ).add_to(emergency_map)

    # Resource coordinates
    resource_latitude = float(
        resource["latitude"]
    )

    resource_longitude = float(
        resource["longitude"]
    )

    # Resource marker
    folium.Marker(
        location=[
            resource_latitude,
            resource_longitude
        ],
        popup=(
            f"Resource: "
            f"{resource['resource_name']}<br>"
            f"Distance: "
            f"{route_result['distance_km']:.2f} km<br>"
            f"ETA: "
            f"{route_result['estimated_time_minutes']:.2f} min"
        ),
        tooltip="Selected Resource",
        icon=folium.Icon(
            icon="plus",
            prefix="fa"
        )
    ).add_to(emergency_map)

    # Convert route nodes to coordinates
    route_coordinates = []

    for node in path:

        latitude = float(
            graph.nodes[node]["y"]
        )

        longitude = float(
            graph.nodes[node]["x"]
        )

        route_coordinates.append(
            [latitude, longitude]
        )

    # Draw route
    folium.PolyLine(
        locations=route_coordinates,
        weight=6,
        tooltip=(
            f"A* Route | "
            f"{route_result['distance_km']:.2f} km | "
            f"{route_result['estimated_time_minutes']:.2f} min"
        )
    ).add_to(emergency_map)

    # Save map
    emergency_map.save(
        OUTPUT_FILE
    )

    return OUTPUT_FILE


if __name__ == "__main__":

    print("Loading road graph...")

    graph = ox.load_graphml(
        GRAPH_FILE
    )

    print(
        f"Graph loaded: "
        f"{len(graph.nodes)} nodes, "
        f"{len(graph.edges)} edges"
    )

    # Emergency location
    emergency_latitude = 15.3694
    emergency_longitude = 44.1910

    # Find nearest road node
    emergency_node = ox.distance.nearest_nodes(
        graph,
        X=emergency_longitude,
        Y=emergency_latitude
    )

    print(
        f"\nEmergency node: "
        f"{emergency_node}"
    )

    # Load resources
    resources = load_resources()

    # For testing, use Ambulance
    response_type = "Ambulance"

    print(
        f"\nSelecting best "
        f"{response_type}..."
    )

    result = select_best_resource(
        graph,
        resources,
        emergency_node,
        response_type
    )

    if result is None:

        print("No suitable resource found.")

    else:

        resource = result["resource"]

        print("\nBest Resource:")
        print(
            f"ID: {resource['resource_id']}"
        )
        print(
            f"Name: {resource['resource_name']}"
        )
        print(
            f"Distance: "
            f"{result['distance_km']:.2f} km"
        )
        print(
            f"ETA: "
            f"{result['estimated_time_minutes']:.2f} minutes"
        )

        output = create_route_map(
            graph,
            emergency_latitude,
            emergency_longitude,
            result
        )

        print("\nMap created successfully!")
        print(f"Saved to: {output}")