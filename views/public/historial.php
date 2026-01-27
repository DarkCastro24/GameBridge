<?php
require_once('../../app/helpers/public_page.php');
Public_Page::headerTemplate('Hardware','historial');
?>
<head>  <!-- Seccion para incluir CSS -->
    <link type="text/css" rel="stylesheet" href="../../resources/css/styles.css" />
</head>
<div id="Seleccion-Catalogo" class="container-fluid margen">
    <div class="container">
        <center><h4>Historial de compras</h4></center><br>
        <table class="highlight centered responsive-table" id="miTabla">
            <thead>
                <tr id="tableHeader">
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Valoraciones</th>
                </tr>
            </thead>
            <tbody id="tbody-rows">
            </tbody>
        </table>
    </div> 
</div>
    <!-- Componente Modal para mostrar una caja de dialogo -->
    <div id="save-modal" class="modal">
        <div class="modal-content">
            <h5 id="modal-title" class="center-align"></h4>
            <form method="post" id="save-form" enctype="multipart/form-data">
                <input class="hide" type="number" id="txtId" name="txtId" />
                <div class="row"> <!-- Seccion de campos de texto. -->
                    <div class="input-field col s12 m6">
                        <input id="txtProducto" type="text" name="txtProducto" class="validate" required />
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="txtCalificacion" type="number" name="txtCalificacion" min="1" max="10" class="validate" required />
                        <label for="txtCalificacion">Calificación</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="txtComentario" type="text" name="txtComentario" class="validate" required />
                        <label for="txtComentario">Comentario</label>
                    </div>
                </div> <!-- Cierra seccion de campos de texto. -->
                <div class="row">
                    <div class="col s12  colconfig">
                        <a class="waves-effect waves btn cancelButton modal-close" href="#!"><i
                                class="material-icons left">clear</i>Cancelar</a>
                        <button type="submit" class="waves-effect waves btn addButton "><i
                                class="material-icons left">check</i>Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Componente Modal para mostrar una caja de dialogo -->
    <div id="update-modal" class="modal">
        <div class="modal-content">
            <h5 id="modal-title2" class="center-align"></h4>
            <form method="post" id="update-form" enctype="multipart/form-data">
                <input class="hide" type="number" id="txtIdx" name="txtIdx" />
                <div class="row"> <!-- Seccion de campos de texto. -->
                    <div class="input-field col s12 m6">
                        <input id="txtProducto2" type="text" name="txtProducto2" class="validate" required />
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="txtCalificacion2" type="number" name="txtCalificacion2" min="1" max="10" class="validate" required />
                        <label for="txtCalificacion2">Calificación</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="txtComentario2" type="text" name="txtComentario2" class="validate" required />
                        <label for="txtComentario2">Comentario</label>
                    </div>
                </div> <!-- Cierra seccion de campos de texto. -->
                <div class="row">
                    <div class="col s12  colconfig">
                        <a class="waves-effect waves btn cancelButton modal-close" href="#!"><i
                                class="material-icons left">clear</i>Cancelar</a>
                        <button type="submit" class="waves-effect waves btn addButton "><i
                                class="material-icons left">autorenew</i>Actualizar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>                                                                                                                                                               <br><br><br><br><br><br><br><br><br><br><br>
<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('historial.js');
?>