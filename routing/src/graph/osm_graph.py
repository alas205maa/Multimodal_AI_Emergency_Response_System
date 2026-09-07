import osmnx as ox
from pathlib import Path


# Sana'a bounding box
NORTH = 15.45
SOUTH = 15.25
EAST = 44.35
WEST = 44.10

OUTPUT_DIR = Path(__file__).resolve().parents[2] / "data" / "maps"
OUTPUT_FILE = OUTPUT_DIR / "sanaa_drive.graphml"


def build_road_graph():
    print("Downloading Sana'a road network from OpenStreetMap...")

    # Download drivable road network
    graph = ox.graph.graph_from_bbox(
        bbox=(WEST, SOUTH, EAST, NORTH),
        network_type="drive",
        simplify=True
    )

    print(f"Nodes: {len(graph.nodes)}")
    print(f"Edges: {len(graph.edges)}")

    # Add distance to edges
    graph = ox.distance.add_edge_lengths(graph)

    # Create output directory
    OUTPUT_DIR.mkdir(parents=True, exist_ok=True)

    # Save graph
    ox.io.save_graphml(graph, OUTPUT_FILE)

    print(f"\nGraph saved to:")
    print(OUTPUT_FILE)

    return graph


if __name__ == "__main__":
    build_road_graph()