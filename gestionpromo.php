<?php
include_once 'conexion.php';
session_start();
include_once("sweetarch.php");

if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}

// Consulta para obtener las promociones
$query_promociones = "SELECT pr.*, p.Nombre as NombreProducto FROM promociones pr INNER JOIN productos p ON pr.ID_Producto = p.IDP";
$statement_promociones = $con->prepare($query_promociones);
$statement_promociones->execute();
$promociones = $statement_promociones->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener todos los productos
$query_productos = "SELECT IDP, Nombre, Precio FROM productos WHERE Estado = 'Aceptado' AND Cantidad > 0";
$statement_productos = $con->prepare($query_productos);
$statement_productos->execute();
$productos = $statement_productos->fetchAll(PDO::FETCH_ASSOC);

// Array para guardar el precio original de cada producto
$productos_precios_originales = [];

// Obtener y guardar el precio original de cada producto
foreach ($productos as $producto) {
    $productos_precios_originales[$producto['IDP']] = $producto['Precio'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['guardar_promocion'])) {
        // Procesar el formulario para actualizar las promociones
        foreach ($_POST['promociones'] as $id_promocion => $promocion) {
            $estado = htmlspecialchars($promocion['Estado']);

            if ($estado == 'Eliminar') {
                // Restaurar el precio original del producto al eliminar la promoción
                $query_restaurar_precio = "UPDATE productos p
                                           INNER JOIN promociones pr ON p.IDP = pr.ID_Producto
                                           SET p.Precio = pr.Precio_Normal
                                           WHERE pr.ID = ?";
                $statement_restaurar_precio = $con->prepare($query_restaurar_precio);
                $statement_restaurar_precio->execute([$id_promocion]);

                // Eliminar promoción
                $query_delete = "DELETE FROM promociones WHERE ID = ?";
                $statement_delete = $con->prepare($query_delete);
                $statement_delete->execute([$id_promocion]);
            } else {
                // Actualizar estado de la promoción
                $query_update = "UPDATE promociones SET Estado = ? WHERE ID = ?";
                $statement_update = $con->prepare($query_update);
                $statement_update->execute([$estado, $id_promocion]);
            }
        }

        // Redirigir para evitar reenvío del formulario
        echo "<script>
                Swal.fire({
                    title: '¡Promoción actualizada exitosamente!',
                    icon: 'success'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'gestionpromo.php';
                    }
                });
              </script>";
        exit;
    } elseif (isset($_POST['nueva_promocion'])) {
        // Procesar el formulario para agregar una nueva promoción
        $id_producto = intval($_POST['ID_Producto']);
        $descuento = floatval($_POST['Descuento']);
        $fecha_inicio = htmlspecialchars($_POST['Fecha_Inicio']);
        $fecha_final = htmlspecialchars($_POST['Fecha_Final']);
        $estado = htmlspecialchars($_POST['Estado']);

        // Obtener el precio normal del producto
        $query_precio_producto = "SELECT Precio FROM productos WHERE IDP = ?";
        $statement_precio_producto = $con->prepare($query_precio_producto);
        $statement_precio_producto->execute([$id_producto]);
        $precio_producto = $statement_precio_producto->fetchColumn();

        // Guardar el precio actual del producto como Precio_Normal en promociones
        $query_insert = "INSERT INTO promociones (ID_Producto, Descuento, Fecha_Inicio, Fecha_Final, Estado, Precio_Normal) VALUES (?, ?, ?, ?, ?, ?)";
        $statement_insert = $con->prepare($query_insert);
        $statement_insert->execute([$id_producto, $descuento, $fecha_inicio, $fecha_final, $estado, $precio_producto]);

        // Si la promoción está activa, actualizar el precio del producto
        if ($estado == 'Activa') {
            $precio_descuento = $precio_producto * (1 - $descuento / 100);
            $query_update_producto = "UPDATE productos SET Precio = ? WHERE IDP = ?";
            $statement_update_producto = $con->prepare($query_update_producto);
            $statement_update_producto->execute([$precio_descuento, $id_producto]);
        }

        // Redirigir para evitar reenvío del formulario
        echo "<script>
                Swal.fire({
                    title: '¡Nueva promoción agregada exitosamente!',
                    icon: 'success'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'gestionpromo.php';
                    }
                });
              </script>";
        exit;
    }
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
            color: #ffffff; /* Color de texto blanco para contrastar con el fondo oscuro */
            padding-top: 20px; /* Espaciado superior */
        }
        .container {
            background-color: #343a40; /* Fondo oscuro para el contenedor */
            color: #ffffff; /* Texto blanco */
            padding: 20px; /* Espaciado interior */
            border-radius: 10px; /* Bordes redondeados para el contenedor */
            margin-top: 20px; /* Margen superior */
            max-width: 900px; /* Ancho máximo para evitar que sea demasiado ancho en pantallas grandes */
            margin-left: auto; /* Centrar el contenedor en la página */
            margin-right: auto;
        }
        .table-dark {
            background-color: #343a40; /* Color de fondo oscuro para la tabla */
            color: #ffffff; /* Texto blanco */
            border-radius: 10px; /* Bordes redondeados para la tabla */
            margin-top: 20px; /* Margen superior */
        }
        .form-group {
            margin-bottom: 15px; /* Espacio entre grupos de formulario */
        }
        .modal-footer {
            text-align: center;
        }
        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
        }
        .btn-outline-danger:hover {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .form-control.estado {
            width: auto;
            display: inline-block;
        }
        .form-row, .modal-body, .modal-footer {
            justify-content: center;
        }
    </style>
    <!-- Scripts de SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h2 class="text-center">        <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
        Gestión de Promociones</h2>
        <form action="" method="POST">
            <div class="modal-body">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Descuento (%)</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Final</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($promociones as $promocion): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($promocion['NombreProducto']); ?></td>
                                <td><?php echo htmlspecialchars($promocion['Descuento']); ?></td>
                                <td><?php echo htmlspecialchars($promocion['Fecha_Inicio']); ?></td>
                                <td><?php echo htmlspecialchars($promocion['Fecha_Final']); ?></td>
                                <td>
                                    <select class="form-control" name="promociones[<?php echo $promocion['ID']; ?>][Estado]">
                                        <option value="Activa" <?php if ($promocion['Estado'] == 'Activa') echo 'selected'; ?>>Activa</option>
                                        <option value="Eliminar" <?php if ($promocion['Estado'] == 'Eliminar') echo 'selected'; ?>>Eliminar</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-outline-danger btn-sm" name="guardar_promocion">Guardar Cambio</button>
                                </td>
                                <input type="hidden" name="promociones[<?php echo $promocion['ID']; ?>][ID]" value="<?php echo $promocion['ID']; ?>">
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </form>
        <div class="row mt-3">
        <div class="col-12 text-center">
            <a href="index.php" class="btn btn-danger">Regresar</a>
        </div>
    </div>

        <!-- Formulario para agregar nueva promoción -->
        <h3 class="text-center mt-4">Agregar Nueva Promoción</h3>
        <form action="" method="POST">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="ID_Producto">Producto:</label>
                    <select class="form-control" id="ID_Producto" name="ID_Producto" required>
                        <?php foreach ($productos as $producto): ?>
                            <option value="<?php echo htmlspecialchars($producto['IDP']); ?>"><?php echo htmlspecialchars($producto['Nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="Descuento">Descuento (%):</label>
                    <input type="number" step="0.01" class="form-control" id="Descuento" name="Descuento" required min="0">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="Fecha_Inicio">Fecha Inicio:</label>
                    <input type="date" class="form-control" id="Fecha_Inicio" name="Fecha_Inicio" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="Fecha_Final">Fecha Final:</label>
                    <input type="date" class="form-control" id="Fecha_Final" name="Fecha_Final" required>
                </div>
            </div>
            <div class="form-group text-center">
                <label for="Estado">Estado:</label>
                <select class="form-control estado" id="Estado" name="Estado" required>
                    <option value="Activa">Activa</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-danger btn-sm" name="nueva_promocion">Agregar Promoción</button>
            </div>
        </form>
    </div>
    <!-- Scripts de Bootstrap -->
    <script src="JS/bootstrap.bundle.min.js"></script>
</body>
</html>