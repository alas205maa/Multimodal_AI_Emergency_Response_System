import json
from pathlib import Path

import osmnx as ox

import sys

SRC_DIR = Path(__file__).resolve().parents[1]

if str(SRC_DIR) not in sys.path:
    sys.path.insert(0, str(SRC_DIR))

from routing.a_star import a_star_route


BASE_DIR = Path(__file__).resolve().parents[2]

GRAPH_FILE = (
    BASE_DIR
    / "data"
    / "maps"
    / "sanaa_drive.graphml"
)

RESOURCES_FILE = (
    BASE_DIR
    / "data"
    / "resources"
    / "resources_with_nodes.json"
)


def load_resources():
    """Load resources that already have road-network node IDs."""

    if not RESOURCES_FILE.exists():
        raise FileNotFoundError(
            f"Resources file not found: {RESOURCES_FILE}"
        )

    with open(
        RESOURCES_FILE,
        "r",
        encoding="utf-8"
    ) as file:
        return json.load(file)


def get_resources_by_type(
    resources,
    response_type
):
    """Return resources matching the required response type."""

    return [
        resource
        for resource in resources
        if resource.get("resource_type") == response_type
    ]


def select_best_resource(
    graph,
    resources,
    start_node,
    response_type
):
    """
    Calculate an A* route to every suitable resource
    and select the one with the lowest route distance.
    """

    candidates = get_resources_by_type(
        resources,
        response_type
    )

    if not candidates:
        return None

    best_resource = None
    best_route = None

    for resource in candidates:

        target_node = resource.get("node_id")

        if target_node is None:
            continue

        try:
            target_node = int(target_node)

            (
                path,
                distance_km,
                estimated_time_minutes,
                execution_time,
                explored_nodes
            ) = a_star_route(
                graph,
                start_node,
                target_node
            )

        except Exception as error:

            print(
                f"Skipping resource "
                f"{resource.get('resource_id')}: "
                f"{error}"
            )

            continue

        if path is None:
            continue

        route_result = {
            "resource": resource,
            "path": path,
            "distance_km": distance_km,
            "estimated_time_minutes":
                estimated_time_minutes,
            "execution_time":
                execution_time,
            "explored_nodes":
                explored_nodes
        }

        if (
            best_route is None
            or distance_km
            < best_route["distance_km"]
        ):
            best_resource = resource
            best_route = route_result

    if best_route is None:
        return None

    return best_route


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

    print("\nLoading resources...")

    resources = load_resources()

    print(
        f"Resources loaded: "
        f"{len(resources)}"
    )

    # Same emergency location used previously
    emergency_node = 1222684010

    # Use Ambulance because we have
    # many candidates to compare.
    response_type = "Ambulance"

    candidates = get_resources_by_type(
        resources,
        response_type
    )

    print(
        f"\nResources matching "
        f"{response_type}: "
        f"{len(candidates)}"
    )

    print("\nCalculating routes...")

    result = select_best_resource(
        graph,
        resources,
        emergency_node,
        response_type
    )

    if result is None:

        print(
            "\nNo suitable resource "
            "or route was found."
        )

    else:

        resource = result["resource"]

        print("\n==============================")
        print("BEST RESOURCE")
        print("==============================")

        print(
            f"Resource ID: "
            f"{resource.get('resource_id')}"
        )

        print(
            f"Resource Name: "
            f"{resource.get('resource_name')}"
        )

        print(
            f"Resource Type: "
            f"{resource.get('resource_type')}"
        )

        print(
            f"Road Node: "
            f"{resource.get('node_id')}"
        )

        print(
            f"Distance: "
            f"{result['distance_km']:.2f} km"
        )

        print(
            f"Estimated Time: "
            f"{result['estimated_time_minutes']:.2f} minutes"
        )

        print(
            f"A* Execution Time: "
            f"{result['execution_time']:.6f} seconds"
        )

        print(
            f"Explored Nodes: "
            f"{result['explored_nodes']}"
        )

        print(
            f"Route Nodes: "
            f"{len(result['path'])}"
        )

        print("==============================")
        