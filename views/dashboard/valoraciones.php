<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/dashboard_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Dashboard_Page::headerTemplate('Administrar clientes');
?>
<head> <!-- Seccion para incluir el CSS -->
    <link type="text/css" rel="stylesheet" href="../../resources/css/styles.css" />
</head>
<div class="container"> <!-- Cabecera del formulario -->
    <div class="row">
        <div class="col l6 ">
            <h5 class="h5">
                <img src="../../resources/img/reseña_icono.png" height="40" width="40" alt="">
                 Gestión de reseñas
            </h5>
        </div>
    </div>
</div>
<div class="container"> <!-- Seccion de busqueda filtrada -->
    <div class="row">
        <!-- Formulario de búsqueda -->
        <div class="row">
            <!-- Formulario de búsqueda -->
            <form method="post" id="search-form">
                <div class="input-field col s10 m4 ">
                    <input id="search" type="text" name="search" required />
                    <label for="search">Buscar por nombre de cliente</label>
                </div>
                <div class="input-field col s1 m4">
                    <button type="submit" class="btn waves-effect green tooltipped" data-tooltip="Buscar"><i
                        class="material-icons">search</i>
                    </button>
                </div>
            </form>
        </div>
    </div>                                                                                                                                                                                                                                                                              <br>
    <div class="container"> <!-- Seccion de tabla -->
        <table class="highlight centered responsive-table" id="miTabla">
            <thead>
                <tr id="tableHeader">
                    <th>Imagen</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Calificación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tbody-rows">
            </tbody>
        </table>
    </div>                                                                                                                                                                                                                                              <br><br>
    <!-- Componente Modal para mostrar una caja de dialogo -->
    <div id="save-modal" class="modal">
        <div class="modal-content">
            <h4 id="modal-title" class="center-align">Gestionar Valoración</h4>
            <form method="post" id="save-form" enctype="multipart/form-data">
                <input class="hide" type="number" id="txtId" name="txtId" />
                <div class="row">
                    <div class="input-field col s12 m12">
                        <input id="txtComentario" type="text" name="txtComentario" class="validate" required />
                        <label for="txtComentario">Comentario</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <select id="cmbDetalle" name="cmbDetalle">
                        </select>
                        <label>Detalle</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="txtCalificacion" type="number" name="txtCalificacion" class="validate" required />
                        <label for="txtCalificacion">Calificacion</label>
                    </div>
                    <div class="col s12 m6">
                        <p>
                        <div class="switch">
                            <span>Estado:</span>
                            <label>
                                <i class="material-icons">visibility_off</i>
                                <input id="estado_producto" type="checkbox" name="estado_producto" checked />
                                <span class="lever"></span>
                                <i class="material-icons">visibility</i>
                            </label>
                        </div>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12  colconfig">
                        <a class="waves-effect waves btn cancelButton modal-close" href="#!"><i
                                class="material-icons left">clear</i>Cancelar</a>
                        <button type="submit" class="waves-effect waves btn addButton "><i
                                class="material-icons left">check</i>Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Dashboard_Page::footerTemplate('valoraciones.js');
?>