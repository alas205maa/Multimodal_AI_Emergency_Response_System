'''''
import sys
import json
from pathlib import Path
import traceback

import osmnx as ox


# ============================================================
# PROJECT PATHS
# ============================================================

BASE_DIR = Path(__file__).resolve().parents[1]

SRC_DIR = BASE_DIR / "src"

DATA_DIR = BASE_DIR / "data"

GRAPH_FILE = (
    DATA_DIR
    / "maps"
    / "sanaa_drive.graphml"
)

RESOURCES_FILE = (
    DATA_DIR
    / "resources"
    / "resources.json"
)

RESOURCES_WITH_NODES_FILE = (
    DATA_DIR
    / "resources"
    / "resources_with_nodes.json"
)


if str(SRC_DIR) not in sys.path:
    sys.path.insert(0, str(SRC_DIR))


# ============================================================
# IMPORT PROJECT MODULES
# ============================================================

from graph.node_mapping import get_nearest_node

from routing.dijkstra import dijkstra_route

from routing.a_star import a_star_route

from resource_selection.resource_loader import (
    load_resources as load_raw_resources
)

from resource_selection.select_resource import (
    load_resources,
    get_resources_by_type,
    select_best_resource
)


# ============================================================
# TEST CONFIGURATION
# ============================================================

EMERGENCY_ID = "EMG-000001"

EMERGENCY_LATITUDE = 15.3694
EMERGENCY_LONGITUDE = 44.1910

TEST_RESPONSE_TYPE = "Ambulance"

EXPECTED_RESOURCE_TYPES = {
    "Fire_Rescue",
    "Ambulance",
    "Rescue_Team"
}


# ============================================================
# TEST RESULT STORAGE
# ============================================================

results = []


def test(name, function):
    """
    Run one test and store PASS/FAIL.
    """

    try:

        function()

        print(f"PASS  {name}")

        results.append(
            (name, True, None)
        )

    except Exception as error:

        print(f"FAIL  {name}")

        results.append(
            (name, False, str(error))
        )


# ============================================================
# LOAD GRAPH
# ============================================================

graph = None


def test_graph_file():

    global graph

    assert GRAPH_FILE.exists(), (
        f"Graph file does not exist: {GRAPH_FILE}"
    )

    graph = ox.load_graphml(
        GRAPH_FILE
    )

    assert len(graph.nodes) > 0, (
        "Graph contains no nodes."
    )

    assert len(graph.edges) > 0, (
        "Graph contains no edges."
    )


# ============================================================
# TEST GRAPH DATA
# ============================================================

def test_graph_attributes():

    assert graph is not None

    # Check sample nodes
    sample_nodes = list(
        graph.nodes
    )[:100]

    for node in sample_nodes:

        data = graph.nodes[node]

        assert "x" in data, (
            f"Node {node} has no longitude."
        )

        assert "y" in data, (
            f"Node {node} has no latitude."
        )

        assert data["x"] is not None
        assert data["y"] is not None


# ============================================================
# TEST RESOURCE FILE
# ============================================================

raw_resources = None


def test_resource_file():

    global raw_resources

    assert RESOURCES_FILE.exists(), (
        f"Resource file does not exist: "
        f"{RESOURCES_FILE}"
    )

    raw_resources = load_raw_resources()

    assert len(raw_resources) > 0, (
        "No resources found."
    )

    required_fields = {
        "resource_id",
        "resource_name",
        "resource_type",
        "latitude",
        "longitude"
    }

    for resource in raw_resources:

        missing = (
            required_fields
            - set(resource.keys())
        )

        assert not missing, (
            f"Resource {resource.get('resource_id')} "
            f"is missing: {missing}"
        )

        latitude = float(
            resource["latitude"]
        )

        longitude = float(
            resource["longitude"]
        )

        assert -90 <= latitude <= 90, (
            f"Invalid latitude: {latitude}"
        )

        assert -180 <= longitude <= 180, (
            f"Invalid longitude: {longitude}"
        )

        assert resource["resource_type"] in (
            EXPECTED_RESOURCE_TYPES
        ), (
            f"Unknown resource type: "
            f"{resource['resource_type']}"
        )


# ============================================================
# TEST RESOURCE NODE MAPPING
# ============================================================

mapped_resources = None


def test_resource_node_mapping():

    global mapped_resources

    assert RESOURCES_WITH_NODES_FILE.exists(), (
        f"Mapped resources file does not exist: "
        f"{RESOURCES_WITH_NODES_FILE}"
    )

    with open(
        RESOURCES_WITH_NODES_FILE,
        "r",
        encoding="utf-8"
    ) as file:

        mapped_resources = json.load(file)

    assert len(mapped_resources) > 0

    for resource in mapped_resources:

        assert "node_id" in resource, (
            f"Resource "
            f"{resource.get('resource_id')} "
            f"has no node_id."
        )

        node_id = int(
            resource["node_id"]
        )

        assert node_id in graph.nodes, (
            f"Node {node_id} "
            f"does not exist in graph."
        )


# ============================================================
# TEST EMERGENCY NODE MAPPING
# ============================================================

emergency_node = None


def test_emergency_node_mapping():

    global emergency_node

    emergency_node = get_nearest_node(
        graph,
        EMERGENCY_LATITUDE,
        EMERGENCY_LONGITUDE
    )

    assert emergency_node is not None

    assert emergency_node in graph.nodes, (
        "Emergency node does not exist "
        "in road graph."
    )


# ============================================================
# TEST DIJKSTRA
# ============================================================

dijkstra_result = None


def test_dijkstra():

    global dijkstra_result

    dijkstra_result = dijkstra_route(
        graph,
        emergency_node,
        597127399
    )

    (
        path,
        distance_km,
        estimated_time_minutes,
        execution_time,
        explored_nodes
    ) = dijkstra_result

    assert path is not None, (
        "Dijkstra did not find a route."
    )

    assert len(path) > 1, (
        "Dijkstra route contains too few nodes."
    )

    assert distance_km > 0, (
        "Dijkstra distance is not positive."
    )

    assert estimated_time_minutes > 0

    assert execution_time >= 0

    assert explored_nodes > 0


# ============================================================
# TEST A*
# ============================================================

a_star_result = None


def test_a_star():

    global a_star_result

    a_star_result = a_star_route(
        graph,
        emergency_node,
        597127399
    )

    (
        path,
        distance_km,
        estimated_time_minutes,
        execution_time,
        explored_nodes
    ) = a_star_result

    assert path is not None, (
        "A* did not find a route."
    )

    assert len(path) > 1, (
        "A* route contains too few nodes."
    )

    assert distance_km > 0

    assert estimated_time_minutes > 0

    assert execution_time >= 0

    assert explored_nodes > 0


# ============================================================
# TEST DIJKSTRA VS A*
# ============================================================

def test_dijkstra_vs_a_star():

    (
        dijkstra_path,
        dijkstra_distance,
        _,
        _,
        _
    ) = dijkstra_result

    (
        a_star_path,
        a_star_distance,
        _,
        _,
        _
    ) = a_star_result

    assert dijkstra_path is not None
    assert a_star_path is not None

    # They must produce the same shortest distance.
    difference = abs(
        dijkstra_distance
        - a_star_distance
    )

    assert difference < 0.001, (
        f"Dijkstra distance = "
        f"{dijkstra_distance:.4f}, "
        f"A* distance = "
        f"{a_star_distance:.4f}"
    )


# ============================================================
# TEST RESOURCE FILTERING
# ============================================================

resources = None


def test_resource_loading():

    global resources

    resources = load_resources()

    assert len(resources) > 0

    ambulance_resources = (
        get_resources_by_type(
            resources,
            "Ambulance"
        )
    )

    assert len(ambulance_resources) > 0, (
        "No Ambulance resources found."
    )


# ============================================================
# TEST RESOURCE SELECTION
# ============================================================

resource_selection_result = None


def test_resource_selection():

    global resource_selection_result

    resource_selection_result = (
        select_best_resource(
            graph,
            resources,
            emergency_node,
            TEST_RESPONSE_TYPE
        )
    )

    assert resource_selection_result is not None, (
        "No suitable resource was selected."
    )

    resource = (
        resource_selection_result["resource"]
    )

    assert (
        resource["resource_type"]
        == TEST_RESPONSE_TYPE
    ), (
        "Selected resource has wrong type."
    )

    assert (
        resource_selection_result["distance_km"]
        > 0
    )

    assert (
        resource_selection_result[
            "estimated_time_minutes"
        ] > 0
    )

    assert len(
        resource_selection_result["path"]
    ) > 1


# ============================================================
# TEST ROUTE VALIDITY
# ============================================================

def test_route_validity():

    path = resource_selection_result[
        "path"
    ]

    assert path[0] == emergency_node, (
        "Route does not start "
        "at emergency node."
    )

    target_node = int(
        resource_selection_result[
            "resource"
        ]["node_id"]
    )

    assert path[-1] == target_node, (
        "Route does not end "
        "at selected resource."
    )

    for node in path:

        assert node in graph.nodes, (
            f"Route contains invalid node: "
            f"{node}"
        )


# ============================================================
# TEST ROUTE COORDINATES
# ============================================================

def test_route_coordinates():

    path = resource_selection_result[
        "path"
    ]

    for node in path:

        data = graph.nodes[node]

        latitude = data.get("y")
        longitude = data.get("x")

        assert latitude is not None
        assert longitude is not None

        assert -90 <= float(latitude) <= 90
        assert -180 <= float(longitude) <= 180


# ============================================================
# TEST RESPONSE TYPE MAPPING
# ============================================================

def test_response_types():

    valid_types = {
        "Fire_Rescue",
        "Ambulance",
        "Rescue_Team"
    }

    for resource_type in valid_types:

        matches = get_resources_by_type(
            resources,
            resource_type
        )

        assert isinstance(
            matches,
            list
        )


# ============================================================
# RUN ALL TESTS
# ============================================================

print()
print("=" * 60)
print("S4 ROUTING SYSTEM - FINAL VALIDATION")
print("=" * 60)

print(
    f"\nGraph: "
    f"{GRAPH_FILE}"
)

print(
    f"Resources: "
    f"{RESOURCES_FILE}"
)

print(
    f"Emergency ID: "
    f"{EMERGENCY_ID}"
)

print(
    f"Emergency Location: "
    f"{EMERGENCY_LATITUDE}, "
    f"{EMERGENCY_LONGITUDE}"
)

print()


test(
    "Road graph file",
    test_graph_file
)

test(
    "Road graph attributes",
    test_graph_attributes
)

test(
    "Resource data",
    test_resource_file
)

test(
    "Resource node mapping",
    test_resource_node_mapping
)

test(
    "Emergency node mapping",
    test_emergency_node_mapping
)

test(
    "Dijkstra shortest path",
    test_dijkstra
)

test(
    "A* shortest path",
    test_a_star
)

test(
    "Dijkstra vs A* correctness",
    test_dijkstra_vs_a_star
)

test(
    "Resource loading and filtering",
    test_resource_loading
)

test(
    "Best resource selection",
    test_resource_selection
)

test(
    "Route validity",
    test_route_validity
)

test(
    "Route coordinates",
    test_route_coordinates
)

test(
    "Response type handling",
    test_response_types
)


# ============================================================
# FINAL SUMMARY
# ============================================================

print()
print("=" * 60)
print("FINAL RESULT")
print("=" * 60)

passed = sum(
    1
    for _, success, _ in results
    if success
)

failed = sum(
    1
    for _, success, _ in results
    if not success
)

print(
    f"\nPassed: {passed}"
)

print(
    f"Failed: {failed}"
)

if failed == 0:

    print()
    print("==========================================")
    print("ALL TESTS PASSED")
    print("S4 ROUTING SYSTEM IS READY")
    print("==========================================")

else:

    print()
    print("==========================================")
    print("SOME TESTS FAILED")
    print("CHECK THE ERRORS ABOVE")
    print("==========================================")

    print("\nFailed tests:")

    for name, success, error in results:

        if not success:

            print()
            print(f"- {name}")
            print(f"  {error}")
            
'''
   
#api testing in powershell         
#py -m pip install "uvicorn[standard]"
#py -m uvicorn src.api.routing_api:app --reload