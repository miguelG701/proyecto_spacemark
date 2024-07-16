<?php
include_once 'conexion.php';
session_start();
include_once("sweetarch.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: pgindex.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

// Obtener los datos de las compras del usuario con el estado "Entregado"
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
        AND hv.Estado = 'Entregado'
    ORDER BY 
        hv.Fecha DESC";

$stmt = $con->prepare($query);
$stmt->execute([$id_usuario]);
$compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($compras)) {
    echo "No se encontraron compras con estado 'Entregado' para este usuario.";
    exit;
}

$agrupadas = [];
foreach ($compras as $compra) {
    $key = $compra['Fecha'];
    if (!isset($agrupadas[$key])) {
        $agrupadas[$key] = [
            'Metodo_pago' => $compra['Metodo_pago'],
            'Total' => 0,
            'items' => []
        ];
    }
    $agrupadas[$key]['Total'] += $compra['Total'];
    $agrupadas[$key]['items'][] = $compra;
}

// Para depuración, puedes imprimir el array $agrupadas
// echo "<pre>";
// print_r($agrupadas);
// echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/bootstrap.css">
    <link rel="stylesheet" href="CSS/alerta.css">
    <link rel="shortcut icon" href="IMG/Spacemark ico_transparent.ico">
    <title>SpaceMark</title>
    <style>
        body {
            background-color: #212529;
            color: #ffffff;
            padding-top: 20px;
        }
        .container {
            background-color: #343a40;
            color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .table-dark {
            background-color: #343a40;
            color: #ffffff;
            border-radius: 10px;
            margin-top: 20px;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</head>
<body>
    
<div class="container">
    <div class="row mt-3">
        <div class="col-12 text-center">
            <a href="index.php" class="btn btn-danger">Regresar</a>
        </div>
    </div>

    <div class="row mt-4">
        <?php foreach ($agrupadas as $fecha => $data): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">SpaceMark</h5>
                        <hr>
                        <?php foreach ($data['items'] as $item): ?>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo $item['Nombre']; ?></h6>
                            <p class="card-text">Cantidad: <?php echo $item['Cantidad']; ?></p>
                            <p class="card-text">Precio: <?php echo $item['Total']; ?></p>
                            <hr>
                        <?php endforeach; ?>
                        <p class="card-text">Método de pago: <?php echo $data['Metodo_pago']; ?></p>
                        <h4 class="card-title">Total: <?php echo $data['Total']; ?></h4>
                        <a href="#" class="card-link">En proceso</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

</body>
</html>
