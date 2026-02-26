<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/dashboard_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Dashboard_Page::headerTemplate('Administrar categorías');
?>

<head>
    <!-- Seccion para incluir CSS -->
    <link type="text/css" rel="stylesheet" href="../../resources/css/styles.css" />
</head>
<div class="container">
    <div class="row">
        <div class="col l6 ">
            <h5 class="h5">
                <img src="../../resources/img/detalle_factura.png" height="40" width="40" alt="">
                Gestión de categorías
            </h5>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <!-- Formulario de búsqueda -->
        <div class="row">
            <!-- Formulario de búsqueda -->
            <form method="post" id="search-form">
                <div class="input-field col s6 m4 ">
                    <input id="search" type="text" name="search" required />
                    <label for="search">Buscar por nombre de categoría</label>
                </div>
                <div class="input-field col s6 m4">
                    <button type="submit" class="btn waves-effect green tooltipped" data-tooltip="Buscar"><i class="material-icons">search</i></button>
                    <a href="../../app/reports/dashboard/categorias.php" target="_blank" class="btn waves-effect amber tooltipped" data-tooltip="Reporte de existencias agrupado por categorías"><i class="material-icons">assignment</i></a>
                </div>
            </form>
            <div class="input-field center-align col s9 m4">
                <!-- Enlace para abrir la caja de dialogo (modal) al momento de crear un nuevo registro -->
                <button class="right button" href="!#" onclick="openCreateDialog()"><i class="material-icons left">add</i>
                    Agregar categoría</button>
            </div>
        </div>
    </div> <br>
    <div class="container">
        <table class="highlight centered responsive-table" id="miTabla">
            <thead>
                <tr id="tableHeader">
                    <th>Categoría</th>
                    <th>Sección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tbody-rows">
            </tbody>
        </table>
    </div> <br><br>
    <!-- Componente Modal para mostrar una caja de dialogo -->
    <div id="save-modal" class="modal">
        <div class="modal-content">
            <h4 id="modal-title" class="center-align">Gestionar Categoría</h4>
            <form method="post" id="save-form" enctype="multipart/form-data">
                <input class="hide" type="number" id="txtId" name="txtId" />

                <div class="row">
                    <div class="input-field col s12 m12">
                        <input id="txtusuario" type="text" name="txtusuario" class="validate" required maxlength="40" />
                        <label for="txtusuario">Nombre de la categoría</label>
                    </div>

                    <div class="input-field col s12 m12">
                        <select id="cmbTipo" name="cmbTipo" required></select>
                        <label>Sección</label>
                    </div>

                    <div class="input-field col s12 m12">
                        <textarea id="txtDescripcion" name="txtDescripcion"
                            class="materialize-textarea validate" required maxlength="150"
                            placeholder="Ejemplo: Consolas, tarjetas gráficas y componentes para PC"></textarea>
                        <label for="txtDescripcion">Descripción</label>
                    </div>

                    <div class="file-field input-field col s12 m12">
                        <div class="btn waves-effect tooltipped" data-tooltip="Seleccione una imagen (se redimensionará a 500x500)">
                            <span><i class="material-icons">image</i></span>
                            <input id="archivo_categoria" type="file" name="archivo_categoria" accept=".gif, .jpg, .png" />
                        </div>
                        <div class="file-path-wrapper">
                            <input type="text" class="file-path validate" placeholder="Formatos aceptados: gif, jpg y png" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 colconfig">
                        <a class="waves-effect waves btn cancelButton modal-close" href="#!">
                            <i class="material-icons left">clear</i>Cancelar
                        </a>
                        <button type="submit" class="waves-effect waves btn addButton">
                            <i class="material-icons left">check</i>Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
    // Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
    Dashboard_Page::footerTemplate('categorias.js');
    ?>