import json
from pathlib import Path

import osmnx as ox


BASE_DIR = Path(__file__).resolve().parents[2]

RESOURCES_FILE = (
    BASE_DIR
    / "data"
    / "resources"
    / "resources.json"
)

GRAPH_FILE = (
    BASE_DIR
    / "data"
    / "maps"
    / "sanaa_drive.graphml"
)

OUTPUT_FILE = (
    BASE_DIR
    / "data"
    / "resources"
    / "resources_with_nodes.json"
)


def load_resources():
    """Load resources from resources.json."""

    with open(RESOURCES_FILE, "r", encoding="utf-8") as file:
        return json.load(file)


def load_graph():
    """Load the OSM road graph."""

    if not GRAPH_FILE.exists():
        raise FileNotFoundError(
            f"Graph file not found: {GRAPH_FILE}"
        )

    return ox.load_graphml(GRAPH_FILE)


def map_resources_to_nodes(resources, graph):
    """
    Map every resource to its nearest road node.
    """

    for resource in resources:

        latitude = float(resource["latitude"])
        longitude = float(resource["longitude"])

        nearest_node = ox.distance.nearest_nodes(
            graph,
            X=longitude,
            Y=latitude
        )

        resource["node_id"] = int(nearest_node)

    return resources


def save_resources(resources):
    """Save resources with their road node IDs."""

    with open(OUTPUT_FILE, "w", encoding="utf-8") as file:
        json.dump(
            resources,
            file,
            ensure_ascii=False,
            indent=2
        )


if __name__ == "__main__":

    print("Loading resources...")
    resources = load_resources()

    print(f"Resources found: {len(resources)}")

    print("Loading road graph...")
    graph = load_graph()

    print(
        f"Graph loaded: "
        f"{len(graph.nodes)} nodes, "
        f"{len(graph.edges)} edges"
    )

    print("Mapping resources to nearest road nodes...")

    resources = map_resources_to_nodes(
        resources,
        graph
    )

    save_resources(resources)

    print("\nMapping completed successfully.")
    print(f"Saved to: {OUTPUT_FILE}")

    print("\nFirst 5 resources:")

    for resource in resources[:5]:

        print(
            f"{resource['resource_id']} | "
            f"{resource['resource_name']} | "
            f"{resource['resource_type']} | "
            f"Node: {resource['node_id']}"
        )