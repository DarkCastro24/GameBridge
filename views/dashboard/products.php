<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/dashboard_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Dashboard_Page::headerTemplate('Administrar productos');
?>

<head>
    <link type="text/css" rel="stylesheet" href="../../resources/css/styles.css" />
</head>


<div class="container">
    <div class="row">
        <div class="col l6 pull-l2">
            <h5 class="h5">
                <img src="../../resources/img/productos_icono.png" height="40" width="40" alt="">
                Gestión de productos
            </h5>

        </div>

    </div>
</div>

<div class="container">
    <div class="row">
        <!-- Formulario de búsqueda -->
        <form method="post" id="search-form">
            <div class="input-field col s6 m4">
                <i class="material-icons prefix">search</i>
                <input id="search" type="text" name="search" required />
                <label for="search">Buscar por nombre de producto y categoría</label>
            </div>
            <div class="input-field col s6 m4">
                <button type="submit" class="btn waves-effect green tooltipped" data-tooltip="Buscar"><i class="material-icons">search</i></button>
                <a href="../../app/reports/dashboard/productos.php" target="_blank" class="btn waves-effect amber tooltipped" data-tooltip="Reporte de productos más vendidos"><i class="material-icons">assignment</i></a>
            </div>
        </form>
        <div class="input-field center-align col s12 m4">
            <!-- Enlace para abrir la caja de dialogo (modal) al momento de crear un nuevo registro -->
            <button class="right button" href="!#" onclick="openCreateDialog()"><i class="material-icons left">add</i>
                Agregar producto</button> <!-- Enlace para generar un reporte en formato PDF -->
        </div>
    </div>
</div>
<br>
<div class="container">
    <table class="highlight centered responsive-table" id="miTabla">
        <!-- Cabeza de la tabla para mostrar los títulos de las columnas -->
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Marca</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <!-- Cuerpo de la tabla para mostrar un registro por fila -->
        <tbody id="tbody-rows">
        </tbody>
    </table>
</div>
<!-- Componente Modal para mostrar una caja de dialogo -->
<div id="save-modal" class="modal">
    <div class="modal-content">
        <!-- Título para la caja de dialogo -->
        <h4 id="modal-title" class="center-align">Gestionar Producto</h4>
        <!-- Formulario para crear o actualizar un registro -->
        <form method="post" id="save-form" enctype="multipart/form-data">
            <!-- Campo oculto para asignar el id del registro al momento de modificar -->
            <input class="hide" type="number" id="txtId" name="txtId" />
            <div class="row">
                <div class="input-field col s12 m6">
                    <input id="txtProducto" type="text" name="txtProducto" class="validate" required />
                    <label for="txtProducto">Producto</label>
                </div>
                <div class="input-field col s12 m6">
                    <input id="txtPrecio" type="number" name="txtPrecio" class="validate" max="9999.99" min="0.01" step="any" required />
                    <label for="txtPrecio">Precio (US$)</label>
                </div>
                <div class="input-field col s12 m12">
                    <input id="txtDescripcion" type="text" name="txtDescripcion" class="validate" required />
                    <label for="txtDescripcion">Descripción</label>
                </div>
                <div id="bb" class="input-field col s12 m6">
                    <select id="cmbCategoria" name="cmbCategoria">
                    </select>
                    <label>Categoría</label>
                </div>
                <div id="aa" class="input-field col s12 m6">
                    <select id="cmbMarca" name="cmbMarca">
                    </select>
                    <label>Marca</label>
                </div>
                <div class="input-field col s12 m6">
                    <select id="cmbEstado" name="cmbEstado">
                    </select>
                    <label>Estado</label>
                </div>
                <div class="file-field input-field col s12 m6">
                    <div class="btn waves-effect tooltipped" data-tooltip="Seleccione una imagen (se redimensionará a 500x500)">
                        <span><i class="material-icons">image</i></span>
                        <input id="archivo_producto" type="file" name="archivo_producto" accept=".gif, .jpg, .png" />
                    </div>
                    <div class="file-path-wrapper">
                        <input type="text" class="file-path validate" placeholder="Formatos aceptados: gif, jpg y png" />
                    </div>
                </div>
            </div>
            <div class="row center-align">
                <a href="#" class="btn waves-effect grey tooltipped modal-close" data-tooltip="Cancelar"><i class="material-icons">cancel</i></a>
                <button type="submit" class="btn waves-effect blue tooltipped" data-tooltip="Guardar"><i class="material-icons">save</i></button>
            </div>
        </form>
    </div>
</div>
<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Dashboard_Page::footerTemplate('productos.js');
?>