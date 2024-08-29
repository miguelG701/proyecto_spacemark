<<<<<<< HEAD
<?php
include_once 'conexion.php';
session_start();
include_once("sweetarch.php");

if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id']; // Obtener el ID de usuario autenticado desde la sesión

// Procesar el formulario de actualización si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación básica (puedes agregar más validaciones según necesites)
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    // Validación del nombre (no debe contener números)
    if (preg_match('/[0-9]/', $nombre)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El nombre no debe contener números.'
                }).then(function() {
                    window.location.href = 'perfil.php';
                });
             </script>";
        exit;
    }

    // Validación del correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Formato de correo electrónico inválido.'
                }).then(function() {
                    window.location.href = 'perfil.php';
                });
             </script>";
        exit;
    }

    // Validación del teléfono (debe ser numérico)
    if (!ctype_digit($telefono)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El teléfono debe contener solo números.'
                }).then(function() {
                    window.location.href = 'perfil.php';
                });
             </script>";
        exit;
    }

    // Manejo de la carga de la foto si se ha seleccionado un archivo nuevo
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $rutaTemporal = $_FILES['foto']['tmp_name'];
        $nombreArchivo = $_FILES['foto']['name']; // Nombre original del archivo

        // Definir la ruta de destino para guardar el archivo (en la misma carpeta que este script PHP)
        $rutaDestino = dirname(__FILE__) . "/C:/xampp/htdocs/SpaceMark2/IMG" . $nombreArchivo;

        // Mover el archivo de la carpeta temporal a la carpeta definitiva
        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
            // Actualizar los datos del usuario en la base de datos, incluyendo la foto
            $query = "UPDATE usuarios SET 
                        nombre = :nombre,
                        correo_electronico = :correo,
                        telefono = :telefono,
                        Foto = :foto
                      WHERE id_usuario = :usuario_id";
            $statement = $con->prepare($query);
            $statement->bindParam(':nombre', $nombre);
            $statement->bindParam(':correo', $correo);
            $statement->bindParam(':telefono', $telefono);
            // $statement->bindParam(':foto', $nombreArchivo); // Solo guardamos el nombre del archivo en la base de datos
            $statement->bindParam(':usuario_id', $usuario_id);
            $result = $statement->execute();

            if ($result) {
                echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: 'Perfil actualizado correctamente.',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.href = 'opcionesuser.php';
                        });
                     </script>";
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al actualizar el perfil.',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.href = 'perfil.php';
                        });
                     </script>";
            }
            exit;
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al mover el archivo.'
                    }).then(function() {
                        window.location.href = 'perfil.php';
                    });
                 </script>";
            exit;
        }
    } else {
        // Si no se seleccionó una nueva foto, actualizar los datos sin la foto
        $query = "UPDATE usuarios SET 
                    nombre = :nombre,
                    correo_electronico = :correo,
                    telefono = :telefono
                  WHERE id_usuario = :usuario_id";
        $statement = $con->prepare($query);
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':correo', $correo);
        $statement->bindParam(':telefono', $telefono);
        $statement->bindParam(':usuario_id', $usuario_id);
        $result = $statement->execute();

        if ($result) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Perfil actualizado correctamente.',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.href = 'opcionesuser.php';
                    });
                 </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al actualizar el perfil.',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.href = 'perfil.php';
                    });
                 </script>";
        }
        exit;
    }
}

// Obtener los datos actuales del usuario para mostrar en el formulario
$query_usuario = "SELECT * FROM usuarios WHERE id_usuario = :usuario_id";
$statement_usuario = $con->prepare($query_usuario);
$statement_usuario->bindParam(':usuario_id', $usuario_id);
$statement_usuario->execute();
$usuario = $statement_usuario->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/bootstrap.css">
    <link rel="stylesheet" href="CSS/alerta.css">
    <link rel="shortcut icon" href="IMG/Spacemark ico_transparent.ico">
    <title>SpaceMark - Perfil de Usuario</title>
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
            max-width: 600px; /* Ancho máximo para evitar que sea demasiado ancho en pantallas grandes */
            margin-left: auto; /* Centrar el contenedor en la página */
            margin-right: auto;
        }
        .form-group {
            margin-bottom: 15px; /* Espacio entre grupos de formulario */
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .btn-block {
            width: 100%; /* Botones de ancho completo */
        }
        /* Estilos para dispositivos móviles */
        @media (max-width: 576px) {
            .container {
                padding: 15px; /* Menos espaciado en pantallas pequeñas */
            }
            .form-control {
                width: 100%; /* Ancho completo para controles de formulario en pantallas pequeñas */
            }
            .btn-block {
                font-size: 0.875rem; /* Tamaño de fuente más pequeño para botones en pantallas pequeñas */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">
            <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
            Perfil de Usuario
        </h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" title="Ingrese un nombre válido (solo letras y espacios)" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" class="form-control" id="correo" name="correo" value="<?= htmlspecialchars($usuario['correo_electronico']) ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" class="form-control" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" pattern="[0-9]+" title="Ingrese un número de teléfono válido (solo números)" required>
            </div>
            <!-- <div class="form-group">
                <label for="foto">Cambiar Foto de Perfil:</label>
                <input type="file" class="form-control-file" id="foto" name="foto">
            </div> -->
            <button type="submit" class="btn btn-info btn-block">Guardar Cambios</button>
        </form>
    </div>
    <!-- Botón de regresar -->
    <div class="container mt-3">
        <div class="row">
            <div class="col-12 text-center">
                <a href="index.php" class="btn btn-danger">Regresar</a>
            </div>
        </div>
    </div>
    <!-- Scripts de Bootstrap y otros -->
    <script src="JS/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
=======
<?php
include_once 'conexion.php';
session_start();
include_once("sweetarch.php");

if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id']; // Obtener el ID de usuario autenticado desde la sesión

// Procesar el formulario de actualización si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación básica (puedes agregar más validaciones según necesites)
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    // Validación del nombre (no debe contener números)
    if (preg_match('/[0-9]/', $nombre)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El nombre no debe contener números.'
                }).then(function() {
                    window.location.href = 'perfil.php';
                });
             </script>";
        exit;
    }

    // Validación del correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Formato de correo electrónico inválido.'
                }).then(function() {
                    window.location.href = 'perfil.php';
                });
             </script>";
        exit;
    }

    // Validación del teléfono (debe ser numérico)
    if (!ctype_digit($telefono)) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El teléfono debe contener solo números.'
                }).then(function() {
                    window.location.href = 'perfil.php';
                });
             </script>";
        exit;
    }

    // Manejo de la carga de la foto si se ha seleccionado un archivo nuevo
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $rutaTemporal = $_FILES['foto']['tmp_name'];
        $nombreArchivo = $_FILES['foto']['name']; // Nombre original del archivo

        // Definir la ruta de destino para guardar el archivo (en la misma carpeta que este script PHP)
        $rutaDestino = dirname(__FILE__) . "/C:/xampp/htdocs/SpaceMark2/IMG" . $nombreArchivo;

        // Mover el archivo de la carpeta temporal a la carpeta definitiva
        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
            // Actualizar los datos del usuario en la base de datos, incluyendo la foto
            $query = "UPDATE usuarios SET 
                        nombre = :nombre,
                        correo_electronico = :correo,
                        telefono = :telefono,
                        Foto = :foto
                      WHERE id_usuario = :usuario_id";
            $statement = $con->prepare($query);
            $statement->bindParam(':nombre', $nombre);
            $statement->bindParam(':correo', $correo);
            $statement->bindParam(':telefono', $telefono);
            // $statement->bindParam(':foto', $nombreArchivo); // Solo guardamos el nombre del archivo en la base de datos
            $statement->bindParam(':usuario_id', $usuario_id);
            $result = $statement->execute();

            if ($result) {
                echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: 'Perfil actualizado correctamente.',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.href = 'opcionesuser.php';
                        });
                     </script>";
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al actualizar el perfil.',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.href = 'perfil.php';
                        });
                     </script>";
            }
            exit;
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al mover el archivo.'
                    }).then(function() {
                        window.location.href = 'perfil.php';
                    });
                 </script>";
            exit;
        }
    } else {
        // Si no se seleccionó una nueva foto, actualizar los datos sin la foto
        $query = "UPDATE usuarios SET 
                    nombre = :nombre,
                    correo_electronico = :correo,
                    telefono = :telefono
                  WHERE id_usuario = :usuario_id";
        $statement = $con->prepare($query);
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':correo', $correo);
        $statement->bindParam(':telefono', $telefono);
        $statement->bindParam(':usuario_id', $usuario_id);
        $result = $statement->execute();

        if ($result) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Perfil actualizado correctamente.',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.href = 'opcionesuser.php';
                    });
                 </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al actualizar el perfil.',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.href = 'perfil.php';
                    });
                 </script>";
        }
        exit;
    }
}

// Obtener los datos actuales del usuario para mostrar en el formulario
$query_usuario = "SELECT * FROM usuarios WHERE id_usuario = :usuario_id";
$statement_usuario = $con->prepare($query_usuario);
$statement_usuario->bindParam(':usuario_id', $usuario_id);
$statement_usuario->execute();
$usuario = $statement_usuario->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/bootstrap.css">
    <link rel="stylesheet" href="CSS/alerta.css">
    <link rel="shortcut icon" href="IMG/Spacemark ico_transparent.ico">
    <title>SpaceMark - Perfil de Usuario</title>
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
            max-width: 600px; /* Ancho máximo para evitar que sea demasiado ancho en pantallas grandes */
            margin-left: auto; /* Centrar el contenedor en la página */
            margin-right: auto;
        }
        .form-group {
            margin-bottom: 15px; /* Espacio entre grupos de formulario */
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .btn-block {
            width: 100%; /* Botones de ancho completo */
        }
        /* Estilos para dispositivos móviles */
        @media (max-width: 576px) {
            .container {
                padding: 15px; /* Menos espaciado en pantallas pequeñas */
            }
            .form-control {
                width: 100%; /* Ancho completo para controles de formulario en pantallas pequeñas */
            }
            .btn-block {
                font-size: 0.875rem; /* Tamaño de fuente más pequeño para botones en pantallas pequeñas */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">
            <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
            Perfil de Usuario
        </h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" title="Ingrese un nombre válido (solo letras y espacios)" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" class="form-control" id="correo" name="correo" value="<?= htmlspecialchars($usuario['correo_electronico']) ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" class="form-control" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" pattern="[0-9]+" title="Ingrese un número de teléfono válido (solo números)" required>
            </div>
            <!-- <div class="form-group">
                <label for="foto">Cambiar Foto de Perfil:</label>
                <input type="file" class="form-control-file" id="foto" name="foto">
            </div> -->
            <button type="submit" class="btn btn-info btn-block">Guardar Cambios</button>
        </form>
    </div>
    <!-- Botón de regresar -->
    <div class="container mt-3">
        <div class="row">
            <div class="col-12 text-center">
                <a href="index.php" class="btn btn-danger">Regresar</a>
            </div>
        </div>
    </div>
    <!-- Scripts de Bootstrap y otros -->
    <script src="JS/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
>>>>>>> 7ba2cf3d847245476291b52426604944cd704857
