<?php
require_once('../../app/helpers/public_page.php');
Public_Page::headerTemplate('Hardware','historial');
?>
<head>  <!-- Seccion para incluir CSS -->
    <link type="text/css" rel="stylesheet" href="../../resources/css/styles.css" />
</head>
<div id="Seleccion-Catalogo" class="container-fluid margen">
    <div class="container">
        <center><h4>Historial de pedidos</h4></center><br>
        <table class="highlight centered responsive-table" id="miTabla">
            <thead>
                <tr id="tableHeader">
                    <th>Dirección</th>
                    <th>Fecha</th>
                    <th>Comprobante</th>
                </tr>
            </thead>
            <tbody id="tbody-rows">
            </tbody>
        </table>
    </div> 
</div>                                                                                                                                                   <br><br><br><br><br><br><br><br><br><br><br>
<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('pedidos.js');
?>