<?php
include_once 'conexion.php';
session_start();
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
$query_productos = "SELECT IDP, Nombre FROM productos WHERE Estado = 'Aceptado' AND Cantidad > 0";
$statement_productos = $con->prepare($query_productos);
$statement_productos->execute();
$productos = $statement_productos->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar_inventario'])) {
    // Procesar el formulario para actualizar las promociones
    foreach ($_POST['promociones'] as $promocion) {
        $id_promocion = intval($promocion['ID']);
        $id_producto = intval($promocion['ID_Producto']);
        $descuento = floatval($promocion['Descuento']);
        $fecha_inicio = htmlspecialchars($promocion['Fecha_Inicio']);
        $fecha_final = htmlspecialchars($promocion['Fecha_Final']);
        $estado = htmlspecialchars($promocion['Estado']);

        $query_update = "UPDATE promociones SET ID_Producto = ?, Descuento = ?, Fecha_Inicio = ?, Fecha_Final = ?, Estado = ? WHERE ID = ?";
        $statement_update = $con->prepare($query_update);
        $statement_update->execute([$id_producto, $descuento, $fecha_inicio, $fecha_final, $estado, $id_promocion]);
    }
    
    // Redirigir para evitar reenvío del formulario
    header("Location: index.php");
    exit;
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
            padding: 20px; /* Espaciado interior */
            border-radius: 10px; /* Bordes redondeados para el contenedor */
            margin-top: 20px; /* Margen superior */
        }
        .table-dark {
            background-color: #343a40; /* Color de fondo oscuro para la tabla */
            border-radius: 10px; /* Bordes redondeados para la tabla */
            margin-top: 20px; /* Margen superior */
        }
        .table-dark th, .table-dark td {
            vertical-align: middle; /* Alinear contenido verticalmente en celdas */
        }
        .form-control {
            display: inline-block; /* Alinear controles de formulario en línea */
            width: auto; /* Ancho automático para controles */
        }
        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
        }
        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gestión de Promociones</h2>
        <form action="" method="POST">
            <div class="modal-body">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Descuento (%)</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Final</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($promociones as $promocion): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($promocion['ID']); ?></td>
                                <td><?php echo htmlspecialchars($promocion['NombreProducto']); ?></td>
                                <td><input type="number" step="0.01" class="form-control" name="promociones[<?php echo $promocion['ID']; ?>][Descuento]" value="<?php echo htmlspecialchars($promocion['Descuento']); ?>"></td>
                                <td><input type="date" class="form-control" name="promociones[<?php echo $promocion['ID']; ?>][Fecha_Inicio]" value="<?php echo htmlspecialchars($promocion['Fecha_Inicio']); ?>"></td>
                                <td><input type="date" class="form-control" name="promociones[<?php echo $promocion['ID']; ?>][Fecha_Final]" value="<?php echo htmlspecialchars($promocion['Fecha_Final']); ?>"></td>
                                <td>
                                    <select class="form-control" name="promociones[<?php echo $promocion['ID']; ?>][Estado]">
                                        <option value="Activa" <?php if ($promocion['Estado'] == 'Activa') echo 'selected'; ?>>Activa</option>
                                        <option value="Inactiva" <?php if ($promocion['Estado'] == 'Inactiva') echo 'selected'; ?>>Inactiva</option>
                                    </select>
                                </td>
                                <input type="hidden" name="promociones[<?php echo $promocion['ID']; ?>][ID]" value="<?php echo $promocion['ID']; ?>">
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-danger btn-sm" name="guardar_promocion">Guardar Cambios</button>
            </div>
        </form>

        <!-- Formulario para agregar nueva promoción -->
        <h3>Agregar Nueva Promoción</h3>
        <form action="" method="POST">
            <div class="form-group">
                <label for="ID_Producto">Producto:</label>
                <select class="form-control" id="ID_Producto" name="ID_Producto" required>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?php echo htmlspecialchars($producto['IDP']); ?>"><?php echo htmlspecialchars($producto['Nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="Descuento">Descuento (%):</label>
                <input type="number" step="0.01" class="form-control" id="Descuento" name="Descuento" required>
            </div>
            <div class="form-group">
                <label for="Fecha_Inicio">Fecha Inicio:</label>
                <input type="date" class="form-control" id="Fecha_Inicio" name="Fecha_Inicio" required>
            </div>
            <div class="form-group">
                <label for="Fecha_Final">Fecha Final:</label>
                <input type="date" class="form-control" id="Fecha_Final" name="Fecha_Final" required>
            </div>
            <div class="form-group">
                <label for="Estado">Estado:</label>
                <select class="form-control" id="Estado" name="Estado" required>
                    <option value="Activa">Activa</option>
                    <option value="Inactiva">Inactiva</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-danger btn-sm" name="nueva_promocion">Agregar Promoción</button>
            </div>
        </form>

        <div class="row">
            <div class="col-12 text-center">
                <a href="index.php" class="btn btn-danger">Regresar</a>
            </div>
        </div>
    </div>
</body>
</html>
