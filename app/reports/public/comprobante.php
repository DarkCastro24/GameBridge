<?php
require('../../helpers/report_client.php');
require('../../models/historial_pedidos.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Comprobante de compra');
// Se instancia el módelo Categorías para obtener los datos.
$categoria = new Pedidos;

if ($dataProductos2 = $categoria->readReportCabecera()) {
    foreach ($dataProductos2 as $rowProducto2) {
        // Se establece un color de relleno para los encabezados.
        $pdf->SetFillColor(225);
        // Se establece la fuente para los encabezados.
        $pdf->SetFont('Helvetica', 'B', 10);
        // Se establece el color del texto 
        $pdf->SetTextColor(9,9,9);
        // Se imprimen las celdas con los encabezados.
        $pdf->Cell(70, 6, utf8_decode('Fecha de compra'), 1, 0, 'C', 1);
        // Se imprimen las celdas con los datos de los productos.
        $pdf->Cell(115, 6, utf8_decode($rowProducto2['fecha']), 1, 1);
        // Se imprimen las celdas con los encabezados.
        $pdf->Cell(70, 6, utf8_decode('Correo del cliente'), 1, 0, 'C', 1);
        // Se imprimen las celdas con los datos de los productos.
        $pdf->Cell(115, 6, utf8_decode($rowProducto2['cliente']), 1, 1);
        // Se establece el espacio entre elementos 
        $pdf->Ln(0);
        // Se imprimen las celdas con los encabezados.
        $pdf->Cell(70, 6, utf8_decode('Nombre del cliente'), 1, 0, 'C', 1);
        // Se imprimen las celdas con los datos de los productos.
        $pdf->Cell(115, 6, utf8_decode($rowProducto2['nombre']), 1, 1);
        // Se establece el espacio entre elementos 
        $pdf->Ln(0);
        // Se imprimen las celdas con los encabezados.
        $pdf->Cell(70, 6, utf8_decode('DUI del cliente'), 1, 0, 'C', 1);
        // Se imprimen las celdas con los datos de los productos.
        $pdf->Cell(115, 6, utf8_decode($rowProducto2['dui']), 1, 1);
        // Se establece el espacio entre elementos 
        $pdf->Ln(0);        
        // Se imprimen las celdas con los encabezados.
        $pdf->Cell(70, 6, utf8_decode('Vendedor'), 1, 0, 'C', 1);
        // Se imprimen las celdas con los datos de los productos.
        $pdf->Cell(115, 6, utf8_decode($rowProducto2['vendedor']), 1, 1);
        // Se establece el espacio entre elementos 
        $pdf->Ln(0);
    }
} else {
    $pdf->SetTextColor(9,9,9);
    $pdf->Cell(0, 10, utf8_decode('No hay clientes para mostrar'), 1, 1);
}
    // Se establece el espacio entre elementos 
    $pdf->Ln(10);    
    $pdf->SetFillColor(0, 31, 97);
    // Se establece la fuente para el nombre de la categoría.
    $pdf->SetFont('Helvetica', 'B', 12);
    $pdf->SetTextColor(255);
    // Se imprime una celda con el nombre de la categoría.
    $pdf->Cell(0, 10, utf8_decode('Productos adquiridos'), 1, 1, 'C', 1);
    // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
    if ($dataProductos = $categoria->readReport()) {
        // Se establece un color de relleno para los encabezados.
        $pdf->SetFillColor(225);
        // Se establece la fuente para los encabezados.
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->SetTextColor(9,9,9);
        // Se imprimen las celdas con los encabezados.
        $pdf->Cell(76, 10, utf8_decode('Nombre producto'), 1, 0, 'C', 1);
        $pdf->Cell(35, 10, utf8_decode('Precio (USD)'), 1, 0, 'C', 1);
        $pdf->Cell(30, 10, utf8_decode('Cantidad (Uds)'), 1, 0, 'C', 1);
        $pdf->Cell(45, 10, utf8_decode('Precio total (USD)'), 1, 1, 'C', 1);
        // Se establece la fuente para los datos de los productos.
        $pdf->SetFont('Helvetica', '', 11);
        // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
        foreach ($dataProductos as $rowProducto) {
            // Se imprimen las celdas con los datos de los productos.
            $pdf->SetTextColor(9,9,9);
            $pdf->Cell(76, 10, utf8_decode($rowProducto['producto']), 1, 0);
            $pdf->Cell(35, 10, utf8_decode('$'.$rowProducto['preciounitario']), 1, 0);
            $pdf->Cell(30, 10, utf8_decode($rowProducto['cantidad']), 1, 0);
            $pdf->Cell(45, 10, utf8_decode('$'.$rowProducto['total']), 1, 1);
        }
        $pdf->Ln(5);
        $pdf->Cell(20);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(290, 10, ('Costo total (USD): $'. $_SESSION['preciototal']), 0, 1, 'C');
    } else {
        $pdf->SetTextColor(9,9,9);
        $pdf->Cell(0, 10, utf8_decode('No hay clientes para mostrar'), 1, 1);
    }
// Se envía el documento al navegador y se llama al método Footer()
$pdf->Output();
?>