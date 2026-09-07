/* =========================
   LOAD MOCK DATA
========================= */

function loadData() {
    const path = window.location.pathname;

    const dataPath =
        path.includes("/citizen/") || path.includes("/admin/")
            ? "../mock-data/data.json"
            : "mock-data/data.json";

    return fetch(dataPath).then(response => {
        if (!response.ok) {
            throw new Error("Failed to load mock data.");
        }

        return response.json();
    });
}


/* =========================
   HTML HELPERS
========================= */

function escapeHtml(value) {

    return String(value ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;");
}


function badge(text, type = "") {

    const safeText = escapeHtml(text);

    const className =
        type ||
        String(text)
            .toLowerCase()
            .replaceAll(" ", "-");

    return `
        <span class="badge badge-${className}">
            ${safeText}
        </span>
    `;
}


function priorityBadge(priority) {

    return badge(
        priority,
        priority.toLowerCase()
    );
}


function statusBadge(status) {

    return badge(
        status,
        status.toLowerCase()
    );
}


/* =========================
   NAVIGATION
========================= */

function citizenNav(active = "home") {

    return `
        <header class="topbar">

            <div class="container nav-inner">

                <a
                    class="brand"
                    href="../index.html"
                >

                    <span class="brand-mark">
                        ER
                    </span>

                    Emergency Response

                </a>


                <nav class="nav-links">

                    <a
                        class="${active === "home" ? "active" : ""}"
                        href="../index.html"
                    >
                        Home
                    </a>

                    <a
                        class="${active === "report" ? "active" : ""}"
                        href="report.html"
                    >
                        Report Emergency
                    </a>

                    <a
                        class="${active === "tracking" ? "active" : ""}"
                        href="tracking.html?id=EMG-000001"
                    >
                        Track
                    </a>

                    <a
                        class="${active === "history" ? "active" : ""}"
                        href="history.html"
                    >
                        History
                    </a>

                    <a
                        class="emergency-btn"
                        href="report.html"
                    >
                        Emergency Report
                    </a>

                </nav>

            </div>

        </header>
    `;
}


/* =========================
   ADMIN NAVIGATION
========================= */

function adminNav(active) {

    const links = [

        [
            "dashboard",
            "Dashboard",
            "dashboard.html"
        ],

        [
            "emergencies",
            "Emergencies",
            "emergencies.html"
        ],

        [
            "map",
            "Live Map",
            "map.html"
        ],

        [
            "resources",
            "Resources",
            "resources.html"
        ],

        [
            "analytics",
            "Analytics",
            "analytics.html"
        ],

        [
            "users",
            "Users",
            "users.html"
        ],

        [
            "settings",
            "Settings",
            "settings.html"
        ]

    ];


    return `

        <aside
            class="sidebar"
            id="sidebar"
        >

            <div class="sidebar-brand">

                <span class="brand-mark">
                    ER
                </span>

                Emergency Response

            </div>


            ${links.map(link => `

                <a
                    class="side-link ${
                        active === link[0]
                            ? "active"
                            : ""
                    }"
                    href="${link[2]}"
                >

                    <span>•</span>

                    ${link[1]}

                </a>

            `).join("")}


            <div style="height:20px"></div>


            <a
                class="side-link"
                href="../index.html"
            >

                <span>↗</span>

                Citizen View

            </a>

        </aside>


        <header class="admin-header">

            <div class="admin-header-left">

                <button
                    class="menu-toggle"
                    onclick="toggleSidebar()"
                >
                    ☰
                </button>

                <strong>
                    ${
                        links.find(
                            link => link[0] === active
                        )?.[1] || "Admin"
                    }
                </strong>

            </div>


            <div class="muted">

                Admin User ·

                <a href="login.html">
                    Logout
                </a>

            </div>

        </header>
    `;
}


function toggleSidebar() {

    const sidebar =
        document.getElementById("sidebar");

    if (sidebar) {

        sidebar.classList.toggle("open");

    }
}


/* =========================
   URL PARAMETERS
========================= */

function getParam(name) {

    const params =
        new URLSearchParams(
            window.location.search
        );

    return params.get(name);
}


/* =========================
   TOAST
========================= */

function showToast(message) {

    const toast =
        document.getElementById("toast");

    if (!toast) return;

    toast.textContent = message;

    toast.classList.add("show");

    setTimeout(() => {

        toast.classList.remove("show");

    }, 2500);
}


/* =========================
   EMERGENCY ICON
========================= */

function emergencyIcon(type) {

    const icons = {

        "Fire": "🔥",

        "Flood": "🌊",

        "Earthquake": "🌍",

        "Traffic Accident": "🚗",

        "Medical Emergency": "✚",

        "Other": "⚠",

        "Normal": "✓"

    };

    return icons[type] || "⚠";
}