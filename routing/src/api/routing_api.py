import sys
from pathlib import Path

import osmnx as ox
from fastapi import FastAPI
from pydantic import BaseModel, Field

# ============================================================
# PATHS
# ============================================================

BASE_DIR = Path(__file__).resolve().parents[2]

SRC_DIR = BASE_DIR / "src"

if str(SRC_DIR) not in sys.path:
    sys.path.insert(0, str(SRC_DIR))

GRAPH_FILE = (
    BASE_DIR
    / "data"
    / "maps"
    / "sanaa_drive.graphml"
)

# ============================================================
# IMPORT RESOURCE SELECTION
# ============================================================

from resource_selection.select_resource import (
    load_resources,
    select_best_resource
)

# ============================================================
# FASTAPI
# ============================================================

app = FastAPI(
    title="Emergency Routing API",
    description="A* emergency resource selection and routing service",
    version="1.0.0"
)

# ============================================================
# LOAD GRAPH AND RESOURCES ONCE
# ============================================================

print("Loading road graph...")

graph = ox.load_graphml(GRAPH_FILE)

print(
    f"Graph loaded: "
    f"{len(graph.nodes)} nodes, "
    f"{len(graph.edges)} edges"
)

print("Loading resources...")

resources = load_resources()

print(
    f"Resources loaded: "
    f"{len(resources)}"
)

# ============================================================
# REQUEST MODELS
# ============================================================


class Location(BaseModel):

    latitude: float = Field(
        ...,
        ge=-90,
        le=90
    )

    longitude: float = Field(
        ...,
        ge=-180,
        le=180
    )


class RoutingRequest(BaseModel):

    emergency_id: str

    response_type: str

    location: Location


# ============================================================
# ROUTING ENDPOINT
# ============================================================


@app.post("/routing/route")
def analyze_routing(request: RoutingRequest):

    # --------------------------------------------------------
    # 1. Find nearest road node
    # --------------------------------------------------------

    emergency_node = ox.distance.nearest_nodes(
        graph,
        X=request.location.longitude,
        Y=request.location.latitude
    )

    # --------------------------------------------------------
    # 2. Select best resource using A*
    # --------------------------------------------------------

    result = select_best_resource(
        graph,
        resources,
        emergency_node,
        request.response_type
    )

    # --------------------------------------------------------
    # 3. No resource / route found
    # --------------------------------------------------------

    if result is None:

        return {
            "emergency_id": request.emergency_id,
            "resource_available": False
        }

    # --------------------------------------------------------
    # 4. Extract selected resource
    # --------------------------------------------------------

    resource = result["resource"]

    # --------------------------------------------------------
    # 5. Convert route nodes to coordinates
    # --------------------------------------------------------

    route_coordinates = []

    for node in result["path"]:

        latitude = float(
            graph.nodes[node]["y"]
        )

        longitude = float(
            graph.nodes[node]["x"]
        )

        route_coordinates.append({
            "latitude": latitude,
            "longitude": longitude
        })

    # --------------------------------------------------------
    # 6. Return routing result
    # --------------------------------------------------------

    return {
        "emergency_id": request.emergency_id,

        "resource_available": True,

        "resource": {
            "id": resource["resource_id"],
            "name": resource["resource_name"],
            "type": resource["resource_type"],
            "latitude": float(resource["latitude"]),
            "longitude": float(resource["longitude"])
        },

        "route": route_coordinates,

        "distance_km": round(
            result["distance_km"],
            2
        ),

        "estimated_time_minutes": round(
            result["estimated_time_minutes"],
            2
        )
    }


# ============================================================
# HEALTH CHECK
# ============================================================


@app.get("/health")
def health_check():

    return {
        "status": "ok",
        "service": "Emergency Routing API"
    }