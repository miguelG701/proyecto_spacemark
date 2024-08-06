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
            padding-top: 20px; /* Espaciado superior */
        }
        .container {
            background-color: #343a40; /* Fondo oscuro para el contenedor */
            color: #ffffff; /* Texto blanco */
            padding: 20px; /* Espaciado interior */
            border-radius: 10px; /* Bordes redondeados para el contenedor */
            margin-top: 20px; /* Margen superior */
        }
        .table-container {
            overflow-x: auto; /* Permite el desplazamiento horizontal en pantallas pequeñas */
        }
        .table-dark {
            background-color: #343a40; /* Color de fondo oscuro para la tabla */
            color: #ffffff; /* Texto blanco */
            border-radius: 10px; /* Bordes redondeados para la tabla */
            margin-top: 20px; /* Margen superior */
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
        <h2 class="text-center mb-4">
            <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
            Productos Enviados
        </h2>
        <!-- Formulario de búsqueda -->
        <form action="" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-9">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar en todos los campos" value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Buscar</button>
                    <a href="index.php" class="btn btn-danger w-100 mt-2">Regresar</a>
                </div>
            </div>
        </form>

        <!-- Tabla de productos -->
        <form action="" method="POST">
            <div class="table-container">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Categoría</th>
                            <th scope="col">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Capturar valor de búsqueda
                        $buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

                        // Construir la consulta SQL con parámetros de búsqueda y el id_usuario
                        $query = "SELECT * FROM productos WHERE Estado = 'Pendiente' AND 
                                  usuario_id = :id_usuario AND 
                                  (Nombre LIKE :buscar OR 
                                   Precio LIKE :buscar OR 
                                   Categoria LIKE :buscar OR 
                                   Cantidad LIKE :buscar)";
                        $statement = $con->prepare($query);
                        $statement->execute([
                            ':id_usuario' => $usuario_id,
                            ':buscar' => '%' . $buscar . '%'
                        ]);
                        $productos = $statement->fetchAll();

                        foreach ($productos as $producto) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($producto['Nombre']) . "</td>";
                            echo "<td>" . htmlspecialchars($producto['Precio']) . "</td>";
                            echo "<td>" . htmlspecialchars($producto['Categoria']) . "</td>";
                            echo "<td>" . htmlspecialchars($producto['Cantidad']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</body>
</html>
