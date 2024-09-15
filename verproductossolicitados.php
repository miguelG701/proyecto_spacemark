<?php 
include_once 'conexion.php';
session_start();
include_once("sweetarch.php");

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
          WHERE u.id_tipos = 5 ORDER BY Nombre ASC"; // Cambia el número 5 según sea el ID del tipo de usuario Gerente en tu base de datos

$statement = $con->prepare($query);
$statement->execute();
$solicitudes = $statement->fetchAll(PDO::FETCH_ASSOC); // Asegurarse de que fetchAll devuelva un array asociativo

// Procesar la actualización o eliminación de solicitudes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['solicitud_id'])) {
    $solicitud_id = $_POST['solicitud_id'];
    $nuevo_estado = $_POST['estado'];

    if ($nuevo_estado === 'Cancelar') {
        // Eliminar la solicitud
        $query_delete = "DELETE FROM solicitudes WHERE id_solicitud = ?";
        $statement_delete = $con->prepare($query_delete);
        $statement_delete->execute([$solicitud_id]);
        $message = 'Solicitud eliminada.';
    } else {
        // Actualizar el estado de la solicitud
        $query_update = "UPDATE solicitudes SET estado = ? WHERE id_solicitud = ?";
        $statement_update = $con->prepare($query_update);
        $statement_update->execute([$nuevo_estado, $solicitud_id]);
        $message = 'Estado de solicitud actualizado.';
    }

    // Redirigir con un mensaje de SweetAlert
    echo "<script>
            Swal.fire({
                title: 'Éxito',
                text: '$message',
                icon: 'success'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'verproductossolicitados.php';
                }
            });
          </script>";
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
        .container {
            background-color: #343a40; /* Fondo oscuro para el contenedor */
            padding: 20px; /* Espaciado interior */
            border-radius: 10px; /* Bordes redondeados para el contenedor */
            margin: 20px auto; /* Centrar el contenedor en la página y agregar margen */
            max-width: 1000px; /* Ancho máximo del contenedor */
        }
        .table-dark {
            background-color: #343a40; /* Color de fondo oscuro para la tabla */
            color: #ffffff; /* Texto blanco */
            border-radius: 10px; /* Bordes redondeados para la tabla */
        }
        .table-responsive {
            overflow-x: auto; /* Permite desplazamiento horizontal en pantallas pequeñas */
        }
        .form-control {
            width: auto;
            display: inline-block;
        }
        .btn-primary {
            margin-left: 10px; /* Espaciado entre el botón y el campo de selección */
        }
        /* Estilos para dispositivos móviles */
        @media (max-width: 576px) {
            .container {
                padding: 15px; /* Menos espaciado en pantallas pequeñas */
            }
            .table {
                font-size: 0.875rem; /* Tamaño de fuente más pequeño para la tabla en pantallas pequeñas */
            }
            .form-control {
                width: 100%; /* Ancho completo para controles de formulario en pantallas pequeñas */
                margin-bottom: 10px; /* Espacio entre campos de formulario */
            }
            .btn-primary {
                width: 100%; /* Botones de ancho completo en pantallas pequeñas */
            }
        }
    </style>
    <!-- Scripts de SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-4 mb-4">
            <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
            Solicitudes de Productos Pedidos
        </h2>
        <div class="table-responsive">
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
