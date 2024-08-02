<?php
require_once('vendor/tecnickcom/tcpdf/tcpdf.php'); // Asegúrate de que la ruta sea correcta
include_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: pgindex.php");
    exit;
}

$id_historial = $_GET['id'];

// Obtener la fecha y hora de la compra específica
$query = "
    SELECT 
        hv.Fecha
    FROM 
        historial_ventas hv
    WHERE 
        hv.ID = ?";

$stmt = $con->prepare($query);
$stmt->execute([$id_historial]);
$compra_especifica = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$compra_especifica) {
    echo "No se encontró la compra.";
    exit;
}

$fecha_hora = $compra_especifica['Fecha'];

// Obtener todas las compras que comparten la misma fecha y hora
$query = "
    SELECT 
        hv.ID, 
        hv.Metodo_pago, 
        hv.Total, 
        hv.Fecha, 
        hv.IDP, 
        p.Nombre, 
        hv.Cantidad
    FROM 
        historial_ventas hv
    JOIN 
        productos p ON hv.IDP = p.IDP
    WHERE 
        hv.id_usuario = ? 
        AND hv.Fecha = ?";

$stmt = $con->prepare($query);
$stmt->execute([$_SESSION['usuario_id'], $fecha_hora]);
$compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($compras)) {
    echo "No se encontraron compras.";
    exit;
}

// Crear nuevo PDF
$pdf = new TCPDF();

// Establecer información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SpaceMark');
$pdf->SetTitle('Recibo de Compra');
$pdf->SetSubject('Recibo');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Establecer margenes
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// Establecer auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Añadir una página
$pdf->AddPage();

// Establecer título
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 15, 'SpaceMark - Recibo de Compra', 0, 1, 'C', 0, '', 0, false, 'T', 'M');

// Establecer contenido
$pdf->SetFont('helvetica', '', 12);
$html = '<h2>Detalles de la Compra</h2>';
$total_general = 0;

foreach ($compras as $compra) {
    $html .= '<p><b>Producto:</b> ' . $compra['Nombre'] . '</p>';
    $html .= '<p><b>Cantidad:</b> ' . $compra['Cantidad'] . '</p>';
    $html .= '<p><b>Total:</b> ' . $compra['Total'] . '</p>';
    $html .= '<hr>';
    $total_general += $compra['Total'];
}

$html .= '<p><b>Método de Pago:</b> ' . $compras[0]['Metodo_pago'] . '</p>';
$html .= '<h4>Total General: ' . $total_general . '</h4>';
$html .= '<p><b>Fecha:</b> ' . $fecha_hora . '</p>';

// Escribir contenido en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Cerrar y generar el PDF
$pdf->Output('recibo_compra.pdf', 'D');
?>