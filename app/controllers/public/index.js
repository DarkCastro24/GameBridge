// Constante para establecer la ruta de la API
const API_CATALOGO = '../../app/api/public/catalogo.php?action=';

// Cargar los datos cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    // Cargar las tres secciones de categorías
    loadHardwareCategories();
    loadPerifericosCategories();
    loadAccesoriosCategories();
    loadTopSoldProducts();
});

/**
 * Cargar y mostrar categorías de Hardware
 */
function loadHardwareCategories() {
    fetch(API_CATALOGO + 'readAllHardware')
        .then(response => response.json())
        .then(data => {
            if (data.status && data.dataset && data.dataset.length > 0) {
                renderCategorySection(data.dataset, 'hardware-categories', 'Hardware');
            } else {
                showEmptyMessage('hardware-categories', 'Hardware');
            }
        })
        .catch(error => {
            console.error('Error cargando Hardware:', error);
            showEmptyMessage('hardware-categories', 'Hardware');
        });
}

/**
 * Cargar y mostrar categorías de Periféricos
 */
function loadPerifericosCategories() {
    fetch(API_CATALOGO + 'readAllPerifericos')
        .then(response => response.json())
        .then(data => {
            if (data.status && data.dataset && data.dataset.length > 0) {
                renderCategorySection(data.dataset, 'perifericos-categories', 'Periféricos');
            } else {
                showEmptyMessage('perifericos-categories', 'Periféricos');
            }
        })
        .catch(error => {
            console.error('Error cargando Periféricos:', error);
            showEmptyMessage('perifericos-categories', 'Periféricos');
        });
}

/**
 * Cargar y mostrar categorías de Accesorios
 */
function loadAccesoriosCategories() {
    fetch(API_CATALOGO + 'readAllAccesorios')
        .then(response => response.json())
        .then(data => {
            if (data.status && data.dataset && data.dataset.length > 0) {
                renderCategorySection(data.dataset, 'accesorios-categories', 'Accesorios');
            } else {
                showEmptyMessage('accesorios-categories', 'Accesorios');
            }
        })
        .catch(error => {
            console.error('Error cargando Accesorios:', error);
            showEmptyMessage('accesorios-categories', 'Accesorios');
        });
}

/**
 * Cargar y mostrar los TOP 4 productos más vendidos
 */
function loadTopSoldProducts() {
    fetch(API_CATALOGO + 'getTopSold')
        .then(response => response.json())
        .then(data => {
            if (data.status && data.dataset && data.dataset.length > 0) {
                renderTopSoldProducts(data.dataset);
            } else {
                showEmptyMessage('top-sold-container', 'Productos Más Vendidos');
            }
        })
        .catch(error => {
            console.error('Error cargando Productos Más Vendidos:', error);
            showEmptyMessage('top-sold-container', 'Productos Más Vendidos');
        });
}

/**
 * Renderizar sección de categorías
 */
function renderCategorySection(categories, containerId, sectionTitle) {
    const container = document.getElementById(containerId);
    if (!container) return;

    let html = `<div class="container">
                    <div class="row">
                        <div class="col s12">
                            <center><h3 class="centrar tamañoTitulos"><b>${sectionTitle}</b></h3></center>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <div class="container-fluid">`;

    categories.forEach(category => {
        html += `<div class="col s12 m6 l4">
                    <div class="card z-depth-3 category-card">
                        <div class="card-image">
                            <img src="../../resources/img/categorias/${category.imagen || 'default.jpg'}" alt="${category.categoria}" class="responsive-img">
                        </div>
                        <div class="card-content center-align">
                            <p class="tituloCarta"><strong>${category.categoria}</strong></p>
                            <a href="catalogo.php?categoria=${category.idcategoria}" class="btn btn-small btn-primary">Ver Categoría</a>
                        </div>
                    </div>
                </div>`;
    });

    html += `            </div>
                        </div>
                    </div>
                </div>`;

    container.innerHTML = html;
}

/**
 * Renderizar productos más vendidos
 */
function renderTopSoldProducts(products) {
    const container = document.getElementById('top-sold-container');
    if (!container) return;

    let html = `<div class="container">
                    <div class="row">
                        <div class="col s12">
                            <center><h3 class="centrar tamañoTitulos"><b>Top 4 Productos Más Vendidos</b></h3></center>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <div class="container-fluid">`;

    products.forEach((product, index) => {
        html += `<div class="col s12 m6 l3">
                    <div class="card z-depth-3 product-card">
                        <div class="card-image">
                            <img src="../../resources/img/productos/${product.imagen || 'default.jpg'}" alt="${product.producto}" class="responsive-img">
                        </div>
                        <div class="card-content tamañoCarta justificar">
                            <p class="tituloCarta">${product.producto}</p>
                            <p><strong>Marca:</strong> ${product.marca}</p>
                            <p><strong>Categoría:</strong> ${product.categoria}</p>
                            <p><strong>Vendidos:</strong> <span class="badge">${product.cantidadVendida}</span></p>
                            <p><b>Precio:</b> $${parseFloat(product.precio).toFixed(2)}</p>
                            <a href="producto.php?id=${product.id}" class="btn btn-small btn-primary">Ver Producto</a>
                        </div>
                    </div>
                </div>`;
    });

    html += `            </div>
                        </div>
                    </div>
                </div>`;

    container.innerHTML = html;
}

/**
 * Mostrar mensaje cuando no hay contenido
 */
function showEmptyMessage(containerId, sectionName) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = `<div class="container">
                                <div class="row">
                                    <div class="col s12">
                                        <h2 class="center-align" style="color: #999; margin: 40px 0;">
                                            No hay contenido que mostrar - ${sectionName}
                                        </h2>
                                    </div>
                                </div>
                            </div>`;
}
