<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/dashboard_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Dashboard_Page::headerTemplate('Administrar usuarios');
?>

<head>
    <!-- Seccion para incluir CSS -->
    <link type="text/css" rel="stylesheet" href="../../resources/css/styles.css" />
</head>
<div class="container">
    <div class="row">
        <div class="col l6 ">
            <h5 class="h5">
                <img src="../../resources/img/usuario_icono.png" height="40" width="40" alt="">
                Gestión de usuarios
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
                    <label for="search">Buscar por usuario</label>
                </div>
                <div class="input-field col s6 m4">
                    <button type="submit" class="btn waves-effect green tooltipped" data-tooltip="Buscar"><i class="material-icons">search</i></button>
                    <a href="../../app/reports/dashboard/usuarios.php" target="_blank" class="btn waves-effect amber tooltipped" data-tooltip="Reporte de usuarios por tipo"><i class="material-icons">assignment</i></a>

                </div>
            </form>
            <div class="input-field center-align col s9 m4">
                <!-- Enlace para abrir la caja de dialogo (modal) al momento de crear un nuevo registro -->
                <button class="right button" href="!#" onclick="openCreateDialog()"><i class="material-icons left">add</i>
                    Agregar usuario</button>
                   
            </div>
        </div>
    </div> <br>
    <div class="container">
        <table class="highlight centered responsive-table" id="miTabla">
            <thead>
                <tr id="tableHeader">
                    <th>Nombre de usuario</th>
                    <th>Estado</th>
                    <th>Correo electrónico</th>
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
            <h4 id="modal-title" class="center-align"></h4>
            <form method="post" id="save-form">
                <input class="hide" type="number" id="txtId" name="txtId" />
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="txtusuario" type="text" autocomplete="off" name="txtusuario" class="validate" required />
                        <label for="txtusuario">Nombre de usuario</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <select id="cmbTipo" name="cmbTipo">
                        </select>
                        <label>Tipo de usuario</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="txtcorreo" type="text" autocomplete="off" name="txtcorreo" class="validate" required />
                        <label for="txtcorreo">Correo</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <select id="cmbEstado" name="cmbEstado">
                        </select>
                        <label>Estado de usuario</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="txtClave" type="password" autocomplete="off" name="txtClave" class="validate" required />
                        <label for="txtClave">Clave</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="txtClave2" type="password" autocomplete="off"  name="txtClave2" class="validate" required />
                        <label for="txtClave2">Confirmar Clave</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12  colconfig">
                        <a class="waves-effect waves btn cancelButton modal-close" href="#!"><i class="material-icons left">clear</i>Cancelar</a>
                        <button type="submit" class="waves-effect waves btn addButton "><i class="material-icons left">check</i>Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
    // Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
    Dashboard_Page::footerTemplate('usuarios.js');
    ?>