<?php
include_once 'conexion.php';
session_start();
include_once("sweetarch.php");

if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $id_usuario = $_POST['id_usuario'];

    // Validar que el nombre no esté vacío
    if (empty($nombre)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El campo nombre es obligatorio.'
                }).then(function() {
                    window.location.href = 'index.php';
                });
              </script>";
        exit;
    }

    // Insertar la solicitud en la base de datos
    $query = "INSERT INTO solicitudes (nombre, descripcion, estado, id_usuario) 
              VALUES (:nombre, :descripcion, 'Pendiente', :id_usuario)";
    $statement = $con->prepare($query);
    $statement->bindParam(':nombre', $nombre);
    $statement->bindParam(':descripcion', $descripcion);
    $statement->bindParam(':id_usuario', $id_usuario);

    if ($statement->execute()) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Solicitud enviada correctamente.',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(function() {
                    window.location.href = 'index.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al enviar la solicitud.'
                }).then(function() {
                    window.location.href = 'index.php';
                });
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpaceMark</title>
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
        <h2 class="text-center mt-4 mb-4">Solicitud de Usuario</h2>
        <form action="" method="post" enctype="multipart/form-data" class="bg-dark p-4 rounded">
            <div class="row mb-3">
                <div class="col-md-4 mb-3">
                    <label for="nombre" class="form-label">Nombre del usuario</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
            </div>

            <div class="row mb-3">
               <div class="col-12">
                   <label for="descripcion" class="form-label">Descripción <small>(opcional)</small></label>
                   <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Ingrese una breve descripción (opcional)"></textarea>
               </div>
            </div>

            <!-- Campo oculto para el ID de usuario -->
            <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($_SESSION['usuario_id']); ?>">

            <div class="row">
                <div class="col-12 text-center">
                    <a href="index.php" class="btn btn-danger">Regresar</a>
                    <button type="submit" class="btn btn-primary">Mandar solicitud</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Scripts de Bootstrap y otros -->
    <script src="JS/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>