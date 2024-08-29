<?php 
include_once 'conexion.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}

// Procesar búsqueda por fecha
$buscarFecha = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar_fecha'])) {
    $buscarFecha = $_POST['fecha'];
    $buscarFecha = date('Y-m', strtotime($buscarFecha));
}

// Obtener productos del mes actual o del mes buscado
$mesActual = date('Y-m');
$query = "SELECT * FROM productos WHERE Estado = 'Aceptado' AND DATE_FORMAT(fecha_de_entrega, '%Y-%m') = :mes";
$statement = $con->prepare($query);
$statement->execute(['mes' => ($buscarFecha ? $buscarFecha : $mesActual)]);
$productos = $statement->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/alerta.css">
    <link rel="shortcut icon" href="IMG/Spacemark ico_transparent.ico">
    <title>SpaceMark</title>
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
        .navbar-brand img {
            margin-right: 10px; /* Espacio a la derecha del logo */
        }
        .input-group-append {
            margin-left: 10px; /* Espacio entre el botón y el input */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Buscar productos por mes -->
        <nav class="navbar navbar-dark navbar-expand-lg">
            <a class="navbar-brand">
                <img src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
                Buscar por mes
            </a>
            <form class="form-inline ml-auto" method="POST">
                <div class="input-group">
                    <input class="form-control mr-sm-2" type="month" name="fecha" placeholder="Fecha" aria-label="Fecha" value="<?php echo htmlspecialchars($buscarFecha); ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" type="submit" name="buscar_fecha">Buscar</button>
                        <a href="index.php" class="btn btn-danger ml-2">Regresar</a>
                    </div>
                </div>
            </form>
        </nav>
        
        <!-- Mostrar productos -->
        <div class="mt-3 table-container">
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
    </div>
</body>
</html>

