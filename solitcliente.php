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

// Consulta SQL para seleccionar las solicitudes de productos de los usuarios tipo Gerente (tipo de usuario con ID 2)
$query = "SELECT s.id_solicitud, s.nombre, s.categoria, s.cantidad, s.descripcion, s.estado 
          FROM solicitudes s
          INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
          WHERE u.id_tipos = 2"; // Cambia el número según sea el ID del tipo de usuario Gerente en tu base de datos

$statement = $con->prepare($query);
$statement->execute();
$solicitudes = $statement->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar la actualización del estado o eliminación
    $id_solicitud = $_POST['id_solicitud'];
    $accion = $_POST['accion'];

    if ($accion == 'actualizar') {
        $nuevo_estado = $_POST['estado'];

        $update_query = "UPDATE solicitudes SET estado = :estado WHERE id_solicitud = :id_solicitud";
        $update_statement = $con->prepare($update_query);
        $update_statement->bindParam(':estado', $nuevo_estado);
        $update_statement->bindParam(':id_solicitud', $id_solicitud);
        $update_statement->execute();
    } elseif ($accion == 'eliminar') {
        $delete_query = "DELETE FROM solicitudes WHERE id_solicitud = :id_solicitud";
        $delete_statement = $con->prepare($delete_query);
        $delete_statement->bindParam(':id_solicitud', $id_solicitud);
        $delete_statement->execute();
    }

    // Redirigir de nuevo a la página para ver los cambios
    echo "<script>alert('Ejecución exitosa.'); window.location.href='solitcliente.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpaceMark - Solicitudes de Cliente</title>
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
        <h2 class="text-center mt-4 mb-4">Solicitudes de Clientes</h2>
        <form method="post" action="">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acción</th>
                        <th scope="col"></th> <!-- Espacio adicional para el botón -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($solicitudes as $solicitud): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($solicitud['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['descripcion']); ?></td>
                        <td>
                            <select name="estado" class="form-control">
                                <option value="Pendiente" <?php echo ($solicitud['estado'] == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="En proceso" <?php echo ($solicitud['estado'] == 'En proceso') ? 'selected' : ''; ?>>En proceso</option>
                                <option value="Listo" <?php echo ($solicitud['estado'] == 'Listo') ? 'selected' : ''; ?>>Listo</option>
                            </select>
                        </td>
                        <td>
                            <input type="hidden" name="id_solicitud" value="<?php echo $solicitud['id_solicitud']; ?>">
                            <select name="accion" class="form-control">
                                <option value="actualizar">Actualizar</option>
                                <option value="eliminar">Eliminar</option>
                            </select>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary">Ejecutar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
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
