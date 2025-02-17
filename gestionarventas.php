<?php
include_once 'conexion.php';
session_start();
include_once("sweetarch.php"); // Asegúrate de incluir SweetAlert si no lo has hecho aún

if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}

// Procesar la actualización si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $id_historial = $_POST['actualizar'];
    $cantidad = $_POST['cantidad'][$id_historial];
    $estado = $_POST['estado'][$id_historial];
    
    // Obtener datos actuales de la compra
    $query = "SELECT IDP, Cantidad, Total, Estado, id_usuario FROM historial_ventas WHERE ID = :id_historial";
    $statement = $con->prepare($query);
    $statement->bindParam(':id_historial', $id_historial);
    $statement->execute();
    $venta = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$venta) {
        echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al obtener los datos de la compra.',
                    icon: 'error'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'gestionarventas.php';
                    }
                });
              </script>";
        exit;
    }

    $id_producto = $venta['IDP'];
    $cantidad_actual = $venta['Cantidad'];
    $total_actual = $venta['Total'];
    $estado_actual = $venta['Estado'];
    $id_usuario = $venta['id_usuario'];

    // Verificar si no hubo cambios en cantidad y estado
    if ($cantidad == $cantidad_actual && $estado == $estado_actual) {
        echo "<script>
                Swal.fire({
                    title: 'Sin Cambios',
                    text: 'No se realizaron cambios en los datos de la compra.',
                    icon: 'info'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'gestionarventas.php';
                    }
                });
              </script>";
        exit;
    }

    // Obtener datos del producto
    $query = "SELECT Precio, Cantidad FROM productos WHERE IDP = :id_producto";
    $statement = $con->prepare($query);
    $statement->bindParam(':id_producto', $id_producto);
    $statement->execute();
    $producto = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al obtener los datos del producto.',
                    icon: 'error'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'gestionarventas.php';
                    }
                });
              </script>";
        exit;
    }

    $precio_producto = $producto['Precio'];
    $cantidad_stock = $producto['Cantidad'];

    // Calcular nueva cantidad de stock
    $cantidad_diferencia = $cantidad - $cantidad_actual;
    $nueva_cantidad_stock = $cantidad_stock - $cantidad_diferencia;

    if ($nueva_cantidad_stock < 0) {
        echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Cantidad excede el stock disponible.',
                    icon: 'error'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'gestionarventas.php';
                    }
                });
              </script>";
        exit;
    }

    $nuevo_total = $cantidad * $precio_producto;

    if ($estado == 'Cancelado') {
        // Si el estado es "Cancelado", devolver la cantidad al stock y eliminar el registro
        $nueva_cantidad_stock += $cantidad;
        $query = "UPDATE productos SET Cantidad = :nueva_cantidad_stock WHERE IDP = :id_producto";
        $statement = $con->prepare($query);
        $statement->bindParam(':nueva_cantidad_stock', $nueva_cantidad_stock);
        $statement->bindParam(':id_producto', $id_producto);
        $statement->execute();

        $query = "DELETE FROM historial_ventas WHERE ID = :id_historial";
        $statement = $con->prepare($query);
        $statement->bindParam(':id_historial', $id_historial);
        $statement->execute();

        echo "<script>
                Swal.fire({
                    title: 'Éxito',
                    text: 'Compra cancelada y cantidad devuelta al stock.',
                    icon: 'success'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'gestionarventas.php';
                    }
                });
              </script>";
        exit;
    }

    // Actualizar todos los productos del usuario a "Entregado" si el nuevo estado es "Entregado"
    if ($estado == 'Entregado') {
        $query = "UPDATE historial_ventas SET 
                    Estado = :estado,
                    Total = :nuevo_total
                  WHERE id_usuario = :id_usuario AND Estado != 'Cancelado'";
        $statement = $con->prepare($query);
        $statement->bindParam(':estado', $estado);
        $statement->bindParam(':nuevo_total', $nuevo_total);
        $statement->bindParam(':id_usuario', $id_usuario);
        $statement->execute();
    } else {
        // Actualizar solo el registro específico
        $query = "UPDATE historial_ventas SET 
                    Cantidad = :cantidad,
                    Estado = :estado,
                    Total = :nuevo_total
                  WHERE ID = :id_historial";
        $statement = $con->prepare($query);
        $statement->bindParam(':cantidad', $cantidad);
        $statement->bindParam(':estado', $estado);
        $statement->bindParam(':nuevo_total', $nuevo_total);
        $statement->bindParam(':id_historial', $id_historial);
        $statement->execute();
    }

    // Actualizar la cantidad de stock del producto
    $query = "UPDATE productos SET Cantidad = :nueva_cantidad_stock WHERE IDP = :id_producto";
    $statement = $con->prepare($query);
    $statement->bindParam(':nueva_cantidad_stock', $nueva_cantidad_stock);
    $statement->bindParam(':id_producto', $id_producto);
    $statement->execute();

    echo "<script>
            Swal.fire({
                title: 'Éxito',
                text: 'Historial de ventas actualizado correctamente.',
                icon: 'success'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'gestionarventas.php';
                }
            });
          </script>";
    exit;
}

// Configurar búsqueda
$nombre_usuario_buscar = isset($_GET['nombre_usuario_buscar']) ? $_GET['nombre_usuario_buscar'] : '';
$nombre_producto_buscar = isset($_GET['nombre_producto_buscar']) ? $_GET['nombre_producto_buscar'] : '';
$metodo_pago_buscar = isset($_GET['metodo_pago_buscar']) ? $_GET['metodo_pago_buscar'] : '';

// Obtener el historial de ventas
$query = "SELECT hv.*, p.Nombre AS nombre_producto, u.nombre AS nombre_usuario
          FROM historial_ventas hv
          JOIN productos p ON hv.IDP = p.IDP
          JOIN usuarios u ON hv.id_usuario = u.id_usuario
          WHERE u.nombre LIKE :nombre_usuario AND p.Nombre LIKE :nombre_producto AND hv.Metodo_pago LIKE :metodo_pago ORDER BY hv.ID DESC";
$statement = $con->prepare($query);
$statement->execute([
    ':nombre_usuario' => '%' . $nombre_usuario_buscar . '%',
    ':nombre_producto' => '%' . $nombre_producto_buscar . '%',
    ':metodo_pago' => '%' . $metodo_pago_buscar . '%'
]);
$historial = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/alerta.css">
    <link rel="shortcut icon" href="IMG/Spacemark ico_transparent.ico">
    <title>SpaceMark - Historial de Ventas</title>
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
        .table-container {
            overflow-x: auto; /* Permite desplazamiento horizontal en pantallas pequeñas */
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
        <h2 class="text-center mb-4">
            <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
            Historial de Ventas
        </h2>

        <!-- Formulario de búsqueda -->
        <form action="" method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="nombre_usuario_buscar" class="form-control" placeholder="Buscar por nombre del usuario" value="<?php echo htmlspecialchars($nombre_usuario_buscar); ?>">
                </div>
                <div class="col-md-4">
                    <input type="text" name="nombre_producto_buscar" class="form-control" placeholder="Buscar por nombre del producto" value="<?php echo htmlspecialchars($nombre_producto_buscar); ?>">
                </div>
                <div class="col-md-4">
                    <input type="text" name="metodo_pago_buscar" class="form-control" placeholder="Buscar por método de pago" value="<?php echo htmlspecialchars($metodo_pago_buscar); ?>">
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary w-100">Buscar</button>
                    <a href="index.php" class="btn btn-danger w-100 mt-2">Regresar</a>
                </div>
            </div>
        </form>

        <!-- Formulario de historial de ventas -->
        <form action="" method="POST">
            <div class="table-container">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Nombre del Usuario</th>
                            <th scope="col">Nombre del Producto</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Método de Pago</th>
                            <th scope="col">Total</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial as $venta): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($venta['nombre_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($venta['nombre_producto']); ?></td>
                            <td><input type="number" name="cantidad[<?php echo $venta['ID']; ?>]" value="<?php echo htmlspecialchars($venta['Cantidad']); ?>" class="form-control" min="0"></td>
                            <td><?php echo htmlspecialchars($venta['Metodo_pago']); ?></td>
                            <td><?php echo htmlspecialchars($venta['Total']); ?></td>
                            <td>
                                <select class="form-control" name="estado[<?php echo $venta['ID']; ?>]">
                                    <option value="Pendiente" <?php echo $venta['Estado'] == 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                    <option value="Entregado" <?php echo $venta['Estado'] == 'Entregado' ? 'selected' : ''; ?>>Entregado</option>
                                    <option value="Cancelado" <?php echo $venta['Estado'] == 'Cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                </select>
                            </td>
                            <td><button type="submit" name="actualizar" value="<?php echo $venta['ID']; ?>" class="btn btn-primary w-100">Actualizar</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
