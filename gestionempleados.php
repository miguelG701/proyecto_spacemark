<?php
include_once 'conexion.php';
session_start();
include_once("sweetarch.php");

if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}

// Procesar el formulario de búsqueda
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = trim($_GET['search']);
}

// Gestionar empleados ini
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar_empleado'])) {
    foreach ($_POST['usuario'] as $id => $usuario) {
        $nombre = $_POST['nombre'][$id] ?? null;
        $telefono = $_POST['telefono'][$id] ?? null;
        $correo_electronico = $_POST['correo_electronico'][$id] ?? null;
        $tipo_usuario = $_POST['tipo_usuario'][$id] ?? null;
  
        if ($usuario && $nombre && $telefono && $correo_electronico && $tipo_usuario) {
            $query = "UPDATE usuarios SET usuario = ?, nombre = ?, telefono = ?, correo_electronico = ?, id_tipos = ? WHERE id_usuario = ?";
            $statement = $con->prepare($query);
            $statement->execute([$usuario, $nombre, $telefono, $correo_electronico, $tipo_usuario, $id]);
        } else {
            // Manejar el error si faltan datos
            echo "Faltan datos para el usuario con ID $id";
        }
    }
  
    // Redirigir a la página anterior
    echo "<script>
            window.onload = function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Empleados actualizados correctamente.',
                    onClose: () => {
                        window.location.href = 'gestionempleados.php';
                    }
                });
            }
          </script>";
}

// Obtener los empleados (con o sin búsqueda)
$query_empleados = "SELECT * FROM usuarios WHERE id_tipos = 4 AND aceptado = 'si' ORDER BY Nombre ASC";
if ($searchQuery) {
    $query_empleados .= " AND (usuario LIKE :searchQuery OR nombre LIKE :searchQuery)";
}
$statement_empleados = $con->prepare($query_empleados);
if ($searchQuery) {
    $statement_empleados->bindValue(':searchQuery', '%' . $searchQuery . '%');
}
$statement_empleados->execute();
$empleados = $statement_empleados->fetchAll(PDO::FETCH_ASSOC);

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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            max-width: 100%; /* Asegura que el contenedor no exceda el ancho disponible */
            overflow-x: auto; /* Permite desplazamiento horizontal si es necesario */
        }
        .table-dark {
            background-color: #343a40; /* Color de fondo oscuro para la tabla */
            color: #ffffff; /* Texto blanco */
            border-radius: 10px; /* Bordes redondeados para la tabla */
            margin-top: 20px; /* Margen superior */
            width: 100%; /* Asegura que la tabla ocupe todo el ancho disponible */
        }
        .form-control {
            background-color: #495057; /* Color de fondo para controles de formulario */
            color: #ffffff; /* Texto blanco */
            border-color: #495057; /* Color del borde */
        }
        .form-control:focus {
            background-color: #495057; /* Color de fondo al enfocar */
            border-color: #495057; /* Color del borde al enfocar */
            box-shadow: none; /* Sin sombra */
        }
        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
        }
        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: #ffffff;
        }
        @media (max-width: 576px) {
            .container {
                padding: 15px; /* Menos espaciado en pantallas pequeñas */
            }
            .table-dark th, .table-dark td {
                padding: 0.5rem; /* Menos padding en celdas de la tabla en pantallas pequeñas */
            }
            .btn {
                font-size: 0.875rem; /* Tamaño de fuente más pequeño para botones en pantallas pequeñas */
            }
            .modal-body h2 {
                font-size: 1.5rem; /* Tamaño de fuente más pequeño para el título en pantallas pequeñas */
            }
            .form-control {
                font-size: 0.875rem; /* Tamaño de fuente más pequeño para inputs en pantallas pequeñas */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4 text-center">
            <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
            Gestión de Empleados
        </h2>
    
        <!-- Botón de regresar -->
        <div class="row mb-3">
            <div class="col-12 text-center">
                <a href="index.php" class="btn btn-danger">Regresar</a>
            </div>
        </div>

        <!-- Formulario de búsqueda -->
        <form method="GET" action="">
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="search" name="search" placeholder="Nombre o usuario" value="<?php echo htmlspecialchars($searchQuery); ?>" aria-label="Buscar" aria-describedby="search-label">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-outline-danger">Buscar</button>
                </div>
            </div>
        </form>


        <!-- Ver Empleados lista ini -->
        <form action="" method="POST">
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th scope="col">Número</th>
                                <th scope="col">Usuario</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Teléfono</th>
                                <th scope="col">Correo electrónico</th>
                                <th scope="col">Tipo de Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Mostrar en la tabla Empleados aceptados
                            $numero = 1;
                            foreach ($empleados as $empleado) {
                                echo "<tr>";
                                echo "<td>" . $numero++ . "</td>";
                                echo "<td><input type='text' class='form-control' name='usuario[" . htmlspecialchars($empleado['id_usuario']) . "]' value='" . htmlspecialchars($empleado['usuario']) . "' readonly></td>";
                                echo "<td><input type='text' class='form-control' name='nombre[" . htmlspecialchars($empleado['id_usuario']) . "]' value='" . htmlspecialchars($empleado['nombre']) . "' readonly></td>";
                                echo "<td><input type='text' class='form-control' name='telefono[" . htmlspecialchars($empleado['id_usuario']) . "]' value='" . htmlspecialchars($empleado['telefono']) . "'></td>";
                                echo "<td><input type='email' class='form-control' name='correo_electronico[" . htmlspecialchars($empleado['id_usuario']) . "]' value='" . htmlspecialchars($empleado['correo_electronico']) . "'></td>";
                                echo "<td>";
                                echo "<select name='tipo_usuario[" . htmlspecialchars($empleado['id_usuario']) . "]' class='form-control'>";
                                echo "<option value='1'" . ($empleado['id_tipos'] == 1 ? ' selected' : '') . ">Administrador</option>";
                                echo "<option value='2'" . ($empleado['id_tipos'] == 2 ? ' selected' : '') . ">Cliente</option>";
                                echo "<option value='3'" . ($empleado['id_tipos'] == 3 ? ' selected' : '') . ">Proveedor</option>";
                                echo "<option value='4'" . ($empleado['id_tipos'] == 4 ? ' selected' : '') . ">Empleado</option>";
                                echo "<option value='5'" . ($empleado['id_tipos'] == 5 ? ' selected' : '') . ">Gerente</option>";
                                echo "</select>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-danger btn-sm" name="guardar_empleado">Guardar Cambios</button>
            </div>
        </form>
        <!-- Ver Empleados lista fin -->
    </div>
</body>
</html>
