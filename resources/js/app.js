import "./bootstrap";
import "../css/app.css";

(function () {
    const btnClose = document.getElementById('sidebarClose');
    const btnOpen = document.getElementById('sidebarOpen');
    
    // Safety check
    if (!btnClose || !btnOpen) return;

    // Load state
    const saved = localStorage.getItem('sidebarCollapsed');
    if (saved === '1') {
        document.body.classList.add('sidebar-collapsed');
    }

    // Handlers
    btnClose.addEventListener('click', () => {
        document.body.classList.add('sidebar-collapsed');
        localStorage.setItem('sidebarCollapsed', '1');
    });

    btnOpen.addEventListener('click', () => {
        document.body.classList.remove('sidebar-collapsed');
        localStorage.setItem('sidebarCollapsed', '0');
    });
})();