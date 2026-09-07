import heapq
import time
from pathlib import Path

import osmnx as ox


BASE_DIR = Path(__file__).resolve().parents[2]

GRAPH_FILE = (
    BASE_DIR
    / "data"
    / "maps"
    / "sanaa_drive.graphml"
)

DEFAULT_SPEED_KMH = 30


def load_graph():
    if not GRAPH_FILE.exists():
        raise FileNotFoundError(
            f"Graph file not found: {GRAPH_FILE}"
        )

    return ox.load_graphml(GRAPH_FILE)


def get_edge_length(graph, current_node, neighbor):

    edge_data = graph.get_edge_data(
        current_node,
        neighbor
    )

    if not edge_data:
        return None

    lengths = []

    for data in edge_data.values():

        if isinstance(data, dict):

            length = data.get("length")

            if length is not None:
                lengths.append(float(length))

    if not lengths:
        return None

    return min(lengths)


def dijkstra_route(
    graph,
    start_node,
    target_node,
    speed_kmh=DEFAULT_SPEED_KMH
):
    """
    Dijkstra shortest path.

    Returns:
        path
        distance_km
        estimated_time_minutes
        execution_time
        explored_nodes
    """

    start_time = time.perf_counter()

    distances = {
        start_node: 0.0
    }

    previous = {}

    priority_queue = [
        (0.0, start_node)
    ]

    explored_nodes = set()

    while priority_queue:

        current_distance, current_node = heapq.heappop(
            priority_queue
        )

        if current_node in explored_nodes:
            continue

        explored_nodes.add(current_node)

        if current_node == target_node:
            break

        for neighbor in graph.successors(current_node):

            edge_length = get_edge_length(
                graph,
                current_node,
                neighbor
            )

            if edge_length is None:
                continue

            new_distance = (
                current_distance + edge_length
            )

            if new_distance < distances.get(
                neighbor,
                float("inf")
            ):

                distances[neighbor] = new_distance
                previous[neighbor] = current_node

                heapq.heappush(
                    priority_queue,
                    (new_distance, neighbor)
                )

    execution_time = time.perf_counter() - start_time

    if target_node not in distances:

        return (
            None,
            None,
            None,
            execution_time,
            len(explored_nodes)
        )

    # Reconstruct path
    path = []

    current = target_node

    while current != start_node:

        path.append(current)

        if current not in previous:

            return (
                None,
                None,
                None,
                execution_time,
                len(explored_nodes)
            )

        current = previous[current]

    path.append(start_node)
    path.reverse()

    distance_km = distances[target_node] / 1000

    estimated_time_minutes = (
        distance_km / speed_kmh
    ) * 60

    return (
        path,
        distance_km,
        estimated_time_minutes,
        execution_time,
        len(explored_nodes)
    )


if __name__ == "__main__":

    print("Loading road graph...")

    graph = load_graph()

    print(
        f"Graph loaded: "
        f"{len(graph.nodes)} nodes, "
        f"{len(graph.edges)} edges"
    )

    # Same test used by A*
    start_node = 1222684010
    target_node = 597127399

    print("\nRunning Dijkstra...")

    (
        path,
        distance_km,
        estimated_time,
        execution_time,
        explored_nodes
    ) = dijkstra_route(
        graph,
        start_node,
        target_node
    )

    if path is None:

        print("No route found.")

    else:

        print("\nRoute found!")
        print(f"Number of nodes: {len(path)}")
        print(f"Distance: {distance_km:.2f} km")
        print(
            f"Estimated time: "
            f"{estimated_time:.2f} minutes"
        )
        print(
            f"Execution time: "
            f"{execution_time:.6f} seconds"
        )
        print(
            f"Explored nodes: "
            f"{explored_nodes}"
        )

        print("\nFirst 10 route nodes:")

        for node in path[:10]:
            print(node)