import heapq
import math
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
    """Load the OSM road graph."""

    if not GRAPH_FILE.exists():
        raise FileNotFoundError(
            f"Graph file not found: {GRAPH_FILE}"
        )

    return ox.load_graphml(GRAPH_FILE)


def heuristic(graph, node_a, node_b):
    """
    Estimate geographic distance between two nodes
    using the Haversine formula.

    Returns:
        Estimated distance in meters.
    """

    lat1 = float(graph.nodes[node_a]["y"])
    lon1 = float(graph.nodes[node_a]["x"])

    lat2 = float(graph.nodes[node_b]["y"])
    lon2 = float(graph.nodes[node_b]["x"])

    earth_radius = 6371000

    lat1 = math.radians(lat1)
    lon1 = math.radians(lon1)
    lat2 = math.radians(lat2)
    lon2 = math.radians(lon2)

    dlat = lat2 - lat1
    dlon = lon2 - lon1

    a = (
        math.sin(dlat / 2) ** 2
        + math.cos(lat1)
        * math.cos(lat2)
        * math.sin(dlon / 2) ** 2
    )

    c = 2 * math.atan2(
        math.sqrt(a),
        math.sqrt(1 - a)
    )

    return earth_radius * c


def get_edge_length(graph, current_node, neighbor):
    """Get the shortest edge length between two nodes."""

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


def a_star_route(
    graph,
    start_node,
    target_node,
    speed_kmh=DEFAULT_SPEED_KMH
):
    """
    Find the shortest road route using A*.

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
        (
            heuristic(
                graph,
                start_node,
                target_node
            ),
            0.0,
            start_node
        )
    ]

    explored_nodes = set()

    while priority_queue:

        _, current_distance, current_node = heapq.heappop(
            priority_queue
        )

        if current_node in explored_nodes:
            continue

        explored_nodes.add(current_node)

        # Target reached
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

                estimated_total_cost = (
                    new_distance
                    + heuristic(
                        graph,
                        neighbor,
                        target_node
                    )
                )

                heapq.heappush(
                    priority_queue,
                    (
                        estimated_total_cost,
                        new_distance,
                        neighbor
                    )
                )

    execution_time = time.perf_counter() - start_time

    # No route
    if target_node not in distances:
        return None, None, None, execution_time, len(explored_nodes)

    # Reconstruct path
    path = []

    current = target_node

    while current != start_node:

        path.append(current)

        if current not in previous:
            return None, None, None, execution_time, len(explored_nodes)

        current = previous[current]

    path.append(start_node)

    path.reverse()

    # Distance
    distance_km = distances[target_node] / 1000

    # Estimated time
    time_hours = distance_km / speed_kmh
    estimated_time_minutes = time_hours * 60

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

    # Same nodes used in Dijkstra
    start_node = 1222684010
    target_node = 597127399

    print("\nRunning A*...")

    (
        path,
        distance_km,
        estimated_time,
        execution_time,
        explored_nodes
    ) = a_star_route(
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