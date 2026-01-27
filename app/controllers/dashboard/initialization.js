document.addEventListener('DOMContentLoaded', function () {
    // Se inicializa el componente Sidenav para que funcione el menú lateral.
    M.Sidenav.init(document.querySelectorAll('.sidenav'));
    // Se inicializa el componente Dropdown para que funcione la lista desplegable en los menús.
    M.Dropdown.init(document.querySelectorAll('.dropdown-trigger'));
    // Se inicializa el componente Tooltip asignado a botones y enlaces para que funcionen las sugerencias textuales.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
    // Se inicializa el componente Modal para que funcionen las cajas de dialogo.
    M.Modal.init(document.querySelectorAll('.modal'));
});