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
$query = "SELECT s.nombre, s.categoria, s.cantidad, s.descripcion, s.estado 
          FROM solicitudes s
          INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
          WHERE u.id_tipos = 2"; // Cambia el número 5 según sea el ID del tipo de usuario Gerente en tu base de datos

$statement = $con->prepare($query);
$statement->execute();
$solicitudes = $statement->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>
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
        <h2 class="text-center mt-4 mb-4">Solicitudes de Productos</h2>
        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripción</th> <!-- Nueva columna para la descripción -->
                    <th scope="col">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitudes as $solicitud): ?>
                <tr>
                    <td><?php echo htmlspecialchars($solicitud['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($solicitud['descripcion']); ?></td> <!-- Mostrar la descripción -->
                    <td><?php echo htmlspecialchars($solicitud['estado']); ?></td>
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