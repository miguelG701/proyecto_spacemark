<?php
include_once 'conexion.php';
session_start();
include_once("sweetarch.php");

if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}

// Confirmar producto ini
if (isset($_POST['guardar_producto'])) {
    // Recorremos los valores recibidos del formulario
    foreach ($_POST['Estado'] as $id => $estado) {
        // Actualizamos el estado de cada producto
        $query = "UPDATE productos SET Estado = :estado WHERE IDP = :id";
        $statement = $con->prepare($query);
        $statement->bindParam(':estado', $estado);
        $statement->bindParam(':id', $id);
        $statement->execute();

        // Si el estado es 'No aceptado', eliminamos el producto
        if ($estado === 'No aceptado') {
            $query_delete = "DELETE FROM productos WHERE IDP = :id";
            $statement_delete = $con->prepare($query_delete);
            $statement_delete->bindParam(':id', $id);
            $statement_delete->execute();
        }
    }

    // Preparamos el mensaje para SweetAlert
    $message = "Estados actualizados correctamente.";
    echo "<script>
            window.onload = function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: '$message',
                    onClose: () => {
                        window.location.href = 'index.php';
                    }
                });
            }
          </script>";
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

    <!-- Incluir SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        .btn-regresar {
            margin-top: 20px; /* Espacio superior */
            margin-bottom: 20px; /* Espacio inferior */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- confirmar productos ini -->

        <form action="" method="POST">
            <div class="modal-body">
            <h2 class="mb-4">Confirmar Producto</h2>

                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM productos WHERE Estado = 'Pendiente'";
                        $statement = $con->prepare($query);
                        $statement->execute();
                        $productos = $statement->fetchAll();

                        foreach ($productos as $producto) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($producto['Nombre']) . "</td>";
                            echo "<td>" . htmlspecialchars($producto['Precio']) . "</td>";
                            echo "<td>" . htmlspecialchars($producto['Categoria']) . "</td>";
                            echo "<td>" . htmlspecialchars($producto['Cantidad']) . "</td>";
                            echo "<td>";
                            echo "<select name='Estado[" . $producto['IDP'] . "]'>";
                            echo "<option value='Pendiente'>Pendiente</option>";
                            echo "<option value='Aceptado'>Aceptado</option>";
                            echo "<option value='No aceptado'>No aceptado</option>";
                            echo "</select>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-danger btn-sm" name="guardar_producto">Guardar Producto</button>
            </div>
        </form>

        <!-- confirmar productos fin -->

        <!-- Botón de regresar -->
        <div class="row">
            <div class="col-12 text-center">
                <a href="index.php" class="btn btn-danger btn-regresar">Regresar</a>
            </div>
        </div>
    </div>

    <!-- Script adicional -->
    <script>
        // Puedes agregar scripts adicionales aquí si es necesario
    </script>
</body>
</html>
