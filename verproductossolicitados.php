<?php 
include_once 'conexion.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}

// Obtener el ID del usuario autenticado
$usuario_id = $_SESSION['usuario_id'];

// Consulta SQL para seleccionar las solicitudes de productos de los usuarios tipo Gerente (tipo de usuario con ID 5)
$query = "SELECT s.id_solicitud, s.nombre, s.categoria, s.cantidad, s.descripcion, s.estado 
          FROM solicitudes s
          INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
          WHERE u.id_tipos = 5"; // Cambia el número 5 según sea el ID del tipo de usuario Gerente en tu base de datos

$statement = $con->prepare($query);
$statement->execute();
$solicitudes = $statement->fetchAll();

// Procesar la actualización o eliminación de solicitudes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['solicitud_id'])) {
    $solicitud_id = $_POST['solicitud_id'];
    $nuevo_estado = $_POST['estado'];

    if ($nuevo_estado === 'Cancelar') {
        // Eliminar la solicitud
        $query_delete = "DELETE FROM solicitudes WHERE id_solicitud = ?";
        $statement_delete = $con->prepare($query_delete);
        $statement_delete->execute([$solicitud_id]);
    } else {
        // Actualizar el estado de la solicitud
        $query_update = "UPDATE solicitudes SET estado = ? WHERE id_solicitud = ?";
        $statement_update = $con->prepare($query_update);
        $statement_update->execute([$nuevo_estado, $solicitud_id]);
    }

    // Recargar la página para reflejar los cambios
    echo "<script>alert('Estado de solicitud actualizado.'); window.location.href='verproductossolicitados.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpaceMark - Solicitudes de Productos</title>
    <link rel="stylesheet" href="CSS/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/alerta.css">
    <link rel="shortcut icon" href="IMG/Spacemark ico_transparent.ico">
    <style>
        body {
            background-color: #212529;
            color: #ffffff; /* Color de texto blanco para contrastar con el fondo oscuro */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-4 mb-4">Solicitudes de Productos Pedidos</h2>
        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitudes as $solicitud): ?>
                <tr>
                    <td><?php echo htmlspecialchars($solicitud['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($solicitud['categoria']); ?></td>
                    <td><?php echo htmlspecialchars($solicitud['cantidad']); ?></td>
                    <td><?php echo htmlspecialchars($solicitud['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($solicitud['estado']); ?></td>
                    <td>
                        <form action="" method="post" class="d-inline">
                            <input type="hidden" name="solicitud_id" value="<?php echo htmlspecialchars($solicitud['id_solicitud']); ?>">
                            <select name="estado" class="form-control d-inline w-auto">
                                <option value="Pendiente" <?php if ($solicitud['estado'] == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                                <option value="Entregado" <?php if ($solicitud['estado'] == 'Entregado') echo 'selected'; ?>>Entregado</option>
                                <option value="Cancelar">Cancelar</option>
                            </select>
                            <button type="submit" class="btn btn-primary d-inline">Actualizar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-12 text-center">
            <a href="index.php" class="btn btn-danger">Regresar</a>
        </div>
    </div>
    <!-- Scripts de Bootstrap y otros -->
    <script src="JS/bootstrap.bundle.min.js"></script>
</body>
</html>
