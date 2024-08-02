<?php
include_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Consulta para obtener el historial de ventas del usuario con el estado "En Carrito"
$query = "SELECT hv.ID, p.Nombre as NombreProducto, hv.Cantidad, hv.Metodo_pago, hv.Total, hv.Estado
          FROM historial_ventas hv
          INNER JOIN productos p ON hv.IDP = p.IDP
          WHERE hv.id_usuario = :usuario_id AND hv.Estado = 'Pendiente'";
$statement = $con->prepare($query);
$statement->bindParam(':usuario_id', $usuario_id);
$statement->execute();
$historial_ventas = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/bootstrap.css">
    <link rel="stylesheet" href="CSS/alerta.css">
    <link rel="shortcut icon" href="IMG/Spacemark ico_transparent.ico">
    <title>SpaceMark - Historial de Compras</title>
    <style>
        body {
            background-color: #212529;
            color: #ffffff; /* Color de texto blanco para contrastar con el fondo oscuro */
            padding-top: 20px; /* Espaciado superior */
        }
        .container {
            background-color: #343a40; /* Fondo oscuro para el contenedor */
            color: #ffffff; /* Texto blanco */
            padding: 20px; /* Espaciado interior */
            border-radius: 10px; /* Bordes redondeados para el contenedor */
            margin-top: 20px; /* Margen superior */
        }
        .table-dark {
            background-color: #343a40; /* Color de fondo oscuro para la tabla */
            color: #ffffff; /* Texto blanco */
            border-radius: 10px; /* Bordes redondeados para la tabla */
            margin-top: 20px; /* Margen superior */
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
        <h2>        <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
        Historial de Compras</h2>
        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">Nombre del Producto</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Método de Pago</th>
                    <th scope="col">Total</th>
                    <th scope="col">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historial_ventas as $venta): ?>
                    <tr>
                        <td><?= htmlspecialchars($venta['NombreProducto']) ?></td>
                        <td><?= htmlspecialchars($venta['Cantidad']) ?></td>
                        <td><?= htmlspecialchars($venta['Metodo_pago']) ?></td>
                        <td><?= htmlspecialchars($venta['Total']) ?></td>
                        <td><?= htmlspecialchars($venta['Estado']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
        <!-- Botón de regresar -->
        <div class="row mt-3">
        <div class="col-12 text-center">
            <a href="index.php" class="btn btn-danger">Regresar</a>
        </div>
    </div>
</body>
</html>
