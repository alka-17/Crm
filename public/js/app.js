function toggleSubmenu(submenuId, arrowSelector) {
    const submenu = document.getElementById(submenuId);
    const arrow = document.querySelector(arrowSelector);

    const isVisible = submenu.style.display === "block";

    submenu.style.display = isVisible ? "none" : "block";
    arrow.classList.toggle("rotate", !isVisible);
}
