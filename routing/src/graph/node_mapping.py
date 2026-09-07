import osmnx as ox
from pathlib import Path


# Path to the saved road graph
GRAPH_FILE = (
    Path(__file__).resolve().parents[2]
    / "data"
    / "maps"
    / "sanaa_drive.graphml"
)


def load_graph():
    """Load the saved OSM road graph."""
    
    if not GRAPH_FILE.exists():
        raise FileNotFoundError(
            f"Graph file not found: {GRAPH_FILE}"
        )

    graph = ox.load_graphml(GRAPH_FILE)

    return graph


def get_nearest_node(graph, latitude, longitude):
    """
    Find the nearest road node to a GPS coordinate.

    Parameters:
        graph: OSMnx road graph
        latitude: latitude of the location
        longitude: longitude of the location

    Returns:
        nearest_node: ID of the nearest road node
    """

    nearest_node = ox.distance.nearest_nodes(
        graph,
        X=longitude,
        Y=latitude
    )

    return nearest_node


if __name__ == "__main__":

    graph = load_graph()

    # Example emergency location
    latitude = 15.3694
    longitude = 44.1910

    node = get_nearest_node(
        graph,
        latitude,
        longitude
    )

    print("Emergency location:")
    print(f"Latitude: {latitude}")
    print(f"Longitude: {longitude}")

    print("\nNearest road node:")
    print(node)