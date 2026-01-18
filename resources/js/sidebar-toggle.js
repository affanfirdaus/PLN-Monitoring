document.addEventListener("DOMContentLoaded", () => {
    const app = document.getElementById("appRoot");
    const btn = document.getElementById("sidebarEdgeToggle");

    if (!app || !btn) return;

    // restore state
    const saved = localStorage.getItem("sidebarCollapsed");
    if (saved === "1") app.classList.add("sidebar-collapsed");

    btn.addEventListener("click", () => {
        app.classList.toggle("sidebar-collapsed");
        localStorage.setItem(
            "sidebarCollapsed",
            app.classList.contains("sidebar-collapsed") ? "1" : "0",
        );
    });
});
