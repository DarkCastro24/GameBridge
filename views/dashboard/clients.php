<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/dashboard_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Dashboard_Page::headerTemplate('Administrar clientes');
?>

<head>
    <link type="text/css" rel="stylesheet" href="../../resources/css/styles.css" />
</head>
<div class="container">
    <div class="row">
        <div class="col l6 ">
            <h5 class="h5">
                <img src="../../resources/img/usuario_icono.png" height="40" width="40" alt="">
                Gestión de clientes
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
                    <label for="search">Buscar por nombre, apellido o correo</label>
                </div>
                <div class="input-field col s6 m4">
                    <button type="submit" class="btn waves-effect green tooltipped" data-tooltip="Buscar"><i class="material-icons">search</i></button>
                    <a href="../../app/reports/dashboard/clientes.php" target="_blank" class="btn waves-effect amber tooltipped" data-tooltip="Reporte de clientes ordenado por compras"><i class="material-icons">assignment</i></a>

                </div>
            </form>
            <div class="input-field center-align col s9 m4">
                <!-- Enlace para abrir la caja de dialogo (modal) al momento de crear un nuevo registro -->
                <button class="right button" href="!#" onclick="openCreateDialog()"><i class="material-icons left">add</i>
                    Agregar cliente</button>
            </div>
        </div>
    </div> <br>
    <div class="container">
        <table class="highlight centered responsive-table" id="miTabla">
            <thead>
                <tr id="tableHeader">
                    <!-- Cabecera de la tabla -->
                    <th>Estado</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>DUI</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tbody-rows">
                <!-- Contenido de la tabla. -->
            </tbody>
        </table>
    </div> <br><br>
    <!-- Componente Modal para mostrar una caja de dialogo -->
    <div id="save-modal" class="modal">
        <div class="modal-content">
            <h4 id="modal-title" class="center-align">Gestionar Cliente</h4>
            <form method="post" id="save-form" enctype="multipart/form-data">
                <input class="hide" type="number" id="txtId" name="txtId" />
                <div class="row">
                    <!-- Seccion de campos de texto. -->
                    <div class="input-field col s12 m6">
                        <input id="txtNombre" type="text" name="txtNombre" autocomplete="off" class="validate" required />
                        <label for="txtNombre">Nombre</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="txtApellido" type="text" name="txtApellido" autocomplete="off" class="validate" required />
                        <label for="txtApellido">Apellido</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="txtDui" type="text" name="txtDui" autocomplete="off" class="validate" required />
                        <label for="txtDui">Dui</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <select id="cmbEstado" name="cmbEstado">
                            <option value="1">a</option>
                            <option value="2">b</option>
                        </select>
                        <label>Estado</label>
                    </div>
                    <div class="input-field col s12 m12">
                        <input id="txtCorreo" type="text" name="txtCorreo" autocomplete="off" class="validate" required />
                        <label for="txtCorreo">Correo</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="txtClave" type="password" name="txtClave" autocomplete="off" class="validate" required />
                        <label for="txtClave">Clave</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="txtClave2" type="password" name="txtClave2" autocomplete="off" class="validate" required />
                        <label for="txtClave2">Confirmar Clave</label>
                    </div>
                </div> <!-- Cierra seccion de campos de texto. -->
                <div class="row">
                    <div class="col s12  colconfig">
                        <a class="waves-effect waves btn cancelButton modal-close" href="#!"><i class="material-icons left">clear</i>Cancelar</a>
                        <button type="submit" class="waves-effect waves btn addButton "><i class="material-icons left">check</i>Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Componente Modal para mostrar una caja de dialogo -->
    <div id="address-modal" class="modal">
        <div class="modal-content">
            <h4 id="modal-title" class="center-align">Direcciones del cliente</h4><br>
            <form method="post" id="address-form" enctype="multipart/form-data">
                <input class="hide" type="number" id="txtIdx" name="txtIdx" />
            </form>
            <table class="striped centered responsive-table" id="miTabla">
                <thead>
                    <tr id="tableHeader">
                        <th>Direccion</th>
                        <th>Codigo Postal</th>
                        <th>Telefono</th>
                    </tr>
                </thead>
                <tbody id="tbody-rows2">
                </tbody>
            </table>
            <br>
            <div class="row">
                <div class="col s12  colconfig">
                    <a class="waves-effect waves btn cancelButton modal-close" href="#!"><i class="material-icons left">clear</i>Salir
                    </a>
                </div>
            </div>
            </form>
        </div>
    </div>
    <?php
    // Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
    Dashboard_Page::footerTemplate('clientes.js');
    ?>