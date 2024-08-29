<<<<<<< HEAD
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

// Verificar que el usuario autenticado es de tipo Proveedor (id_tipos = 3)
$query_tipo = "SELECT id_tipos FROM usuarios WHERE id_usuario = ?";
$statement_tipo = $con->prepare($query_tipo);
$statement_tipo->execute([$usuario_id]);
$tipo_usuario = $statement_tipo->fetchColumn();

if ($tipo_usuario != 3) {
    // Si el usuario no es un proveedor, redirige a una página de error o de acceso denegado
    header("Location: acceso_denegado.php");
    exit;
}

// Consulta SQL para seleccionar las solicitudes asignadas al proveedor autenticado
$query = "SELECT s.nombre, s.categoria, s.cantidad, s.descripcion, s.estado 
          FROM solicitudes s
          WHERE s.id_proveedor = ?"; 

$statement = $con->prepare($query);
$statement->execute([$usuario_id]);
$solicitudes = $statement->fetchAll();

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
        .table-container {
            overflow-x: auto; /* Permite el desplazamiento horizontal en pantallas pequeñas */
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
        <h2 class="text-center mt-4 mb-4">
            <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
            Solicitudes de Productos
        </h2>
        <div class="table-container">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Estado</th>
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
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="container text-center mt-4">
        <a href="index.php" class="btn btn-danger">Regresar</a>
    </div>
    <!-- Scripts de Bootstrap y otros -->
    <script src="JS/bootstrap.bundle.min.js"></script>
</body>
=======
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

// Verificar que el usuario autenticado es de tipo Proveedor (id_tipos = 3)
$query_tipo = "SELECT id_tipos FROM usuarios WHERE id_usuario = ?";
$statement_tipo = $con->prepare($query_tipo);
$statement_tipo->execute([$usuario_id]);
$tipo_usuario = $statement_tipo->fetchColumn();

if ($tipo_usuario != 3) {
    // Si el usuario no es un proveedor, redirige a una página de error o de acceso denegado
    header("Location: acceso_denegado.php");
    exit;
}

// Consulta SQL para seleccionar las solicitudes asignadas al proveedor autenticado
$query = "SELECT s.nombre, s.categoria, s.cantidad, s.descripcion, s.estado 
          FROM solicitudes s
          WHERE s.id_proveedor = ?"; 

$statement = $con->prepare($query);
$statement->execute([$usuario_id]);
$solicitudes = $statement->fetchAll();

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
        .table-container {
            overflow-x: auto; /* Permite el desplazamiento horizontal en pantallas pequeñas */
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
        <h2 class="text-center mt-4 mb-4">
            <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
            Solicitudes de Productos
        </h2>
        <div class="table-container">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Estado</th>
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
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="container text-center mt-4">
        <a href="index.php" class="btn btn-danger">Regresar</a>
    </div>
    <!-- Scripts de Bootstrap y otros -->
    <script src="JS/bootstrap.bundle.min.js"></script>
</body>
>>>>>>> 7ba2cf3d847245476291b52426604944cd704857
</html>