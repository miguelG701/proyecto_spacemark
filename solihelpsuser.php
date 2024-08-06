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
        .form-container {
            background-color: #343a40; /* Fondo oscuro para el contenedor del formulario */
            padding: 20px; /* Espaciado interior */
            border-radius: 10px; /* Bordes redondeados para el contenedor del formulario */
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        @media (max-width: 576px) {
            .form-container {
                padding: 15px; /* Menos espaciado en pantallas pequeñas */
            }
            .btn {
                font-size: 0.875rem; /* Tamaño de fuente más pequeño para botones en pantallas pequeñas */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-4 mb-4">
            <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
            Solicitud de Usuario
        </h2>
        <form action="" method="post" enctype="multipart/form-data" class="form-container">
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
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
                    <button type="submit" class="btn btn-primary">Enviar solicitud</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Scripts de Bootstrap y otros -->
    <script src="JS/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
