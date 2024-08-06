<?php 

include_once 'conexion.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}
$busqueda_text = "";
$filtro = "nombre"; // Filtro predeterminado

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_buscar'])) {
    // Obtener término de búsqueda y filtro
    $busqueda_text = trim($_POST['busqueda']);
    $filtro = $_POST['filtro'];

    // Preparar consulta según el filtro seleccionado
    if ($filtro == "nombre") {
        $query = "SELECT * FROM productos WHERE Estado = 'Aceptado' AND Nombre LIKE :busqueda";
    } elseif ($filtro == "categoria") {
        $query = "SELECT * FROM productos WHERE Estado = 'Aceptado' AND Categoria LIKE :busqueda";
    }

    // Preparar y ejecutar la consulta
    $statement = $con->prepare($query);
    $statement->execute([':busqueda' => '%' . $busqueda_text . '%']);
    $productos = $statement->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Consulta predeterminada sin búsqueda
    $query = "SELECT * FROM productos WHERE Estado = 'Aceptado' ORDER BY Nombre ASC";
    $statement = $con->prepare($query);
    $statement->execute();
    $productos = $statement->fetchAll(PDO::FETCH_ASSOC);
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
        .table-dark {
            background-color: #343a40; /* Color de fondo oscuro para la tabla */
            color: #ffffff; /* Texto blanco */
            border-radius: 10px; /* Bordes redondeados para la tabla */
            margin-top: 20px; /* Margen superior */
            width: 100%; /* Asegura que la tabla ocupe todo el ancho disponible */
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
            .container {
                padding: 15px; /* Menos espaciado en pantallas pequeñas */
            }
            .form-control {
                font-size: 0.875rem; /* Tamaño de fuente más pequeño para inputs en pantallas pequeñas */
            }
            .btn {
                font-size: 0.875rem; /* Tamaño de fuente más pequeño para botones en pantallas pequeñas */
            }
            .row {
                margin-left: 0;
                margin-right: 0;
            }
            .col-5, .col-2 {
                width: 100%; /* Ocupa todo el ancho disponible en pantallas pequeñas */
                margin-bottom: 10px; /* Espacio inferior en pantallas pequeñas */
            }
        }
    </style>
</head>
<body>
    
<div class="container">
    <!-- ver productos ini -->
    <form action="" method="post">
        <div class="modal-body">
            <h2 class="mb-4 text-center">
                <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
                Lista de Productos
            </h2>
            <div class="row justify-content-center">
                <div class="col-md-5 mt-3">
                    <input type="text" name="busqueda" placeholder="Buscar por nombre o categoría..." class="form-control" value="<?php echo htmlspecialchars($busqueda_text); ?>">
                </div>
                <div class="col-md-5 mt-3">
                    <select class="form-control" name="filtro">
                        <option value="nombre">Nombre</option>
                        <option value="categoria">Categoría</option>
                    </select>
                </div>
                <div class="col-md-2 mt-3">
                    <button class="btn btn-primary" type="submit" name="btn_buscar">Buscar</button>
                    <a href="index.php" class="btn btn-danger">Regresar</a>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Categoría</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($productos as $producto) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($producto['Nombre']) . "</td>";
                            echo "<td>" . htmlspecialchars($producto['Precio']) . "</td>";
                            echo "<td>" . htmlspecialchars($producto['Cantidad']) . "</td>";
                            echo "<td>" . htmlspecialchars($producto['Categoria']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
    <!-- ver productos fin -->
</div>

</body>
</html>
