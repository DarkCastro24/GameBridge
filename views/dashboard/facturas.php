<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/dashboard_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Dashboard_Page::headerTemplate('Administrar facturas');
?>

<head>
  <!-- Seccion para incluir CSS -->
  <link type="text/css" rel="stylesheet" href="../../resources/css/styles.css" />
</head>
<div class="container">
  <!-- Componente Modal para mostrar una caja de dialogo -->
  <div class="row">
    <div class="col l6">
      <h5 class="h5">
        <img src="../../resources/img/factura_logo.png" height="40" width="40" alt="">
        Gestión de facturas
      </h5>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <form method="post" id="search-form">
      <div class="input-field col s6 m4 ">
        <input id="search" type="text" name="search" required />
        <label for="search">Buscar por nombre de cliente</label>
      </div>
      <div class="input-field col s6 m4">
        <button type="submit" class="btn waves-effect green tooltipped" data-tooltip="Buscar"><i class="material-icons">search</i></button>
        <a href="../../app/reports/dashboard/facturas.php" target="_blank" class="btn waves-effect amber tooltipped" data-tooltip="Reporte de compras realizadas en un día"><i class="material-icons">assignment</i></a>
      </div>
    </form>
  </div>
</div> <br><br>
<div class="container">
  <!-- Seccion tabla -->
  <table class="highlight centered responsive-table" id="miTabla">
    <thead>
      <tr id="tableHeader">
        <th>Cliente</th>
        <th>Estado de factura</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody id="tbody-rows">
    </tbody>
  </table>
</div>

<div id="save-modal" class="modal">
  <!-- Componente modal para modificar y agregar -->
  <div class="modal-content">
    <h4 id="modal-title" class="center-align"></h4>
    <form method="post" id="save-form">
      <input class="hide" type="number" id="txtId" name="txtId" />
      <div class="row">
        <div class="input-field col l12 s12 m12">
          <select id="cmbTipo" name="cmbTipo">
          </select>
          <label>Usuario vendedor</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col l12 s12 m12">
          <select id="cmbEstado" name="cmbEstado">
          </select>
          <label>Estado de la factura</label>
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


<!-- Componente Modal para mostrar productos de una factura -->
<div id="address-modal" class="modal">
  <div class="modal-content">
    <h4 id="modal-title" class="center-align">Detalles de la factura </h4><br>

    <form method="post" id="address-form" enctype="multipart/form-data">
      <input class="hide" type="number" id="txtIdx" name="txtIdx" />
    </form>

    <table class="striped centered responsive-table" id="miTabla2">
      <thead>
        <tr id="tableHeader">
          <th>Producto</th>
          <th>Precio unitario</th>
          <th>Cantidad</th>
          <th>Total unitario</th>
        </tr>
      </thead>
      <tbody id="tbody-rows2">
      </tbody>
    </table>
    <div class="row right-align">
      <p>TOTAL A PAGAR (US$) <b id="pago"></b></p>
    </div>
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
Dashboard_Page::footerTemplate('facturas.js');
?>