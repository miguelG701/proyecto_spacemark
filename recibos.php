<?php
include_once 'conexion.php';
session_start();
include_once("sweetarch.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: pgindex.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

// Obtener el término de búsqueda desde el formulario
$searchFecha = isset($_POST['search_fecha']) ? $_POST['search_fecha'] : '';
$searchProducto = isset($_POST['search_producto']) ? $_POST['search_producto'] : '';
$searchMetodoPago = isset($_POST['search_metodo_pago']) ? $_POST['search_metodo_pago'] : '';

// Consulta para obtener los datos de las compras del usuario con el estado "Entregado"
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
        AND hv.Estado = 'Entregado'";

// Agregar filtros a la consulta según los términos de búsqueda
$params = [$id_usuario];
if ($searchFecha) {
    $query .= " AND DATE(hv.Fecha) = ?";
    $params[] = $searchFecha;
}
if ($searchProducto) {
    $query .= " AND p.Nombre LIKE ?";
    $params[] = '%' . $searchProducto . '%';
}
if ($searchMetodoPago) {
    $query .= " AND hv.Metodo_pago LIKE ?";
    $params[] = '%' . $searchMetodoPago . '%';
}

$query .= " ORDER BY hv.Fecha DESC";

$stmt = $con->prepare($query);
$stmt->execute($params);
$compras = $stmt->fetchAll(PDO::FETCH_ASSOC);



$agrupadas = [];
foreach ($compras as $compra) {
    $key = $compra['Fecha'];
    if (!isset($agrupadas[$key])) {
        $agrupadas[$key] = [
            'Fecha' => $compra['Fecha'],
            'Metodo_pago' => $compra['Metodo_pago'],
            'Total' => 0,
            'items' => []
        ];
    }
    $agrupadas[$key]['Total'] += $compra['Total'];
    $agrupadas[$key]['items'][] = $compra;
}
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
    <div class="row mb-3">
        <div class="col-12 text-center">
        <h2 class="text-center mb-4">        <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
        Recibos de SpaceMark</h2>
            <a href="index.php" class="btn btn-danger">Regresar</a>
        </div>
    </div>

    <!-- Barra de búsqueda -->
    <form action="" method="post" class="mb-4">
        <div class="input-group">
            <input type="date" name="search_fecha" class="form-control" placeholder="Buscar por Fecha" value="<?php echo htmlspecialchars($searchFecha); ?>">
            <input type="text" name="search_producto" class="form-control" placeholder="Buscar por Producto" value="<?php echo htmlspecialchars($searchProducto); ?>">
            <input type="text" name="search_metodo_pago" class="form-control" placeholder="Buscar por Método de Pago" value="<?php echo htmlspecialchars($searchMetodoPago); ?>">
            <button type="submit" class="btn btn-outline-primary">Buscar</button>
        </div>
    </form>

    <div class="row mt-4">
        <?php foreach ($agrupadas as $fecha => $data): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Fecha del Recibo: <?php echo $data['Fecha']; ?></h5>
                        <hr>
                        <?php foreach ($data['items'] as $item): ?>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo $item['Nombre']; ?></h6>
                            <p class="card-text">Cantidad: <?php echo $item['Cantidad']; ?></p>
                            <p class="card-text">Precio: <?php echo $item['Total']; ?></p>
                            <hr>
                        <?php endforeach; ?>
                        <p class="card-text">Método de pago: <?php echo $data['Metodo_pago']; ?></p>
                        <h4 class="card-title">Total: <?php echo $data['Total']; ?></h4>
                        <a href="generar_pdf.php?id=<?php echo $item['ID']; ?>" class="card-link">Descargar</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

</body>
</html>
