<?php 
include_once 'conexion.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}

// Verificar si hay un término de búsqueda
$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';

// Construir la consulta SQL con la cláusula WHERE para buscar por Usuario, Nombre o Tipo de Usuario
$query = "SELECT u.*, t.nom_tipos FROM usuarios u 
          JOIN tipos_usuarios t ON u.id_tipos = t.id_tipos 
          WHERE u.id_tipos != 1 AND u.aceptado = 'si' 
          AND (u.usuario LIKE :searchTerm 
          OR u.nombre LIKE :searchTerm 
          OR t.nom_tipos LIKE :searchTerm)
          ORDER BY u.id_usuario ASC LIMIT 100"; // Excluye a los administradores (id_tipos = 1)

$statement = $con->prepare($query);
$statement->execute(['searchTerm' => '%' . $searchTerm . '%']);
$usuarios = $statement->fetchAll(PDO::FETCH_ASSOC);
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
    <!-- ver usuarios ini -->
    <form action="" method="post">
        <div class="modal-body">
            <h2 class="mb-4">        <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
            Lista de Usuarios</h2>
            <!-- Barra de búsqueda -->
            <div class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar por Usuario, Nombre o Tipo de Usuario" value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type="submit" class="btn btn-outline-primary">Buscar</button>
                </div>
            </div>
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th scope="col">Número</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Tipo de Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Iterar sobre los usuarios obtenidos y mostrarlos en la tabla
                    $numero = 1;
                    foreach ($usuarios as $usuario) {
                        echo "<tr>";
                        echo "<th scope='row'>" . $numero++ . "</th>";
                        echo "<td>" . htmlspecialchars($usuario['usuario']) . "</td>";
                        echo "<td>" . htmlspecialchars($usuario['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($usuario['nom_tipos']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
        </div>
    </form>
    <!-- ver usuarios fin -->

    <!-- Botón de regresar -->
    <div class="row mt-3">
        <div class="col-12 text-center">
            <a href="index.php" class="btn btn-danger">Regresar</a>
        </div>
    </div>

</div>
</body>
</html>
