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
$query = "SELECT hv.ID, p.IDP, p.Nombre as NombreProducto, hv.Cantidad, hv.Metodo_pago, hv.Total, hv.Estado
          FROM historial_ventas hv
          INNER JOIN productos p ON hv.IDP = p.IDP
          WHERE hv.id_usuario = :usuario_id AND hv.Estado = 'En Carrito'";
$statement = $con->prepare($query);
$statement->bindParam(':usuario_id', $usuario_id);
$statement->execute();
$historial_ventas = $statement->fetchAll(PDO::FETCH_ASSOC);

// Función para actualizar el estado a "Pendiente" al finalizar la compra
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['finalizar_compra'])) {
    try {
        $con->beginTransaction();

        // Actualizar el estado de los productos en el carrito a "Pendiente"
        $update_query = "UPDATE historial_ventas SET Estado = 'Pendiente' WHERE id_usuario = :usuario_id AND Estado = 'En Carrito'";
        $update_statement = $con->prepare($update_query);
        $update_statement->bindParam(':usuario_id', $usuario_id);
        $update_statement->execute();

        $con->commit();
    } catch (PDOException $e) {
        // Manejo de errores si falla la transacción
        $con->rollback();
        echo "Error al finalizar la compra: " . $e->getMessage();
    }
    header("Location: carritouser.php");
    exit();
}

// Función para cancelar la compra y devolver la cantidad al stock
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancelar_compra'])) {
    $id_historial = $_POST['id_historial'];
    $cantidad = $_POST['cantidad'];
    $id_producto = $_POST['id_producto'];

    try {
        $con->beginTransaction();

        // Eliminar el registro de historial_ventas
        $delete_query = "DELETE FROM historial_ventas WHERE ID = :id_historial";
        $delete_statement = $con->prepare($delete_query);
        $delete_statement->bindParam(':id_historial', $id_historial);
        $delete_statement->execute();

        // Devolver la cantidad al stock en productos
        $update_stock_query = "UPDATE productos SET Cantidad = Cantidad + :cantidad WHERE IDP = :id_producto";
        $update_stock_statement = $con->prepare($update_stock_query);
        $update_stock_statement->bindParam(':cantidad', $cantidad);
        $update_stock_statement->bindParam(':id_producto', $id_producto);
        $update_stock_statement->execute();

        $con->commit();
    } catch (PDOException $e) {
        // Manejo de errores si falla la transacción
        $con->rollback();
        echo "Error al cancelar la compra: " . $e->getMessage();
    }
    header("Location: carritouser.php");
    exit();
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
    <title>SpaceMark - Carrito de Compras</title>
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
        <h2>Carrito de Compras</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th scope="col">Nombre del Producto</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Método de Pago</th>
                        <th scope="col">Total</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
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
                            <td>
                                <?php if ($venta['Estado'] === 'En Carrito'): ?>
                                    <button type="submit" name="finalizar_compra" class="btn btn-primary btn-sm" onclick="return confirm('¿Estás seguro de finalizar la compra?')">Finalizar Compra</button>
                                    <button type="submit" name="cancelar_compra" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de cancelar la compra?')" value="<?= $venta['ID'] ?>">Cancelar Compra</button>
                                    <input type="hidden" name="id_historial" value="<?= htmlspecialchars($venta['ID']) ?>">
                                    <input type="hidden" name="cantidad" value="<?= htmlspecialchars($venta['Cantidad']) ?>">
                                    <input type="hidden" name="id_producto" value="<?= htmlspecialchars($venta['IDP']) ?>">
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
    <!-- Botón de regresar -->
    <div class="row mt-3">
        <div class="col-12 text-center">
            <a href="index.php" class="btn btn-danger">Regresar</a>
        </div>
    </div>
</body>
</html>
