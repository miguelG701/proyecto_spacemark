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
    $reporte_tipo = $_POST['reporte_tipo'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // Variables para almacenar la consulta SQL y los parámetros
    $query = "";
    $params = [];

    // Generar la consulta SQL según el tipo de informe seleccionado
    switch ($reporte_tipo) {
        case 'productos_mas_comprados':
            $query = "SELECT p.Nombre, SUM(v.Cantidad) as total_comprado 
                      FROM historial_ventas v 
                      INNER JOIN productos p ON v.IDP = p.IDP 
                      WHERE v.Fecha BETWEEN :fecha_inicio AND :fecha_fin
                      GROUP BY p.Nombre
                      ORDER BY total_comprado DESC";
            $params = ['fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin];
            break;

        case 'proveedores_mas_solicitados':
            $query = "SELECT pr.nombre, COUNT(s.id_solicitud) as total_solicitudes
                      FROM solicitudes s
                      INNER JOIN proveedores pr ON s.id_proveedor = pr.id_proveedor
                      WHERE s.fecha_solicitud BETWEEN :fecha_inicio AND :fecha_fin
                      GROUP BY pr.nombre
                      ORDER BY total_solicitudes DESC";
            $params = ['fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin];
            break;

        case 'ganancias_dia':
        case 'ganancias_total':
            $query = "SELECT SUM(v.Total) as ganancias
                      FROM historial_ventas v
                      WHERE v.Fecha BETWEEN :fecha_inicio AND :fecha_fin";
            $params = ['fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin];
            break;

        case 'flujo_usuarios':
            $query = "SELECT u.nombre, COUNT(l.id_login) as total_logins
                      FROM logins l
                      INNER JOIN usuarios u ON l.id_usuario = u.id_usuario
                      WHERE l.fecha BETWEEN :fecha_inicio AND :fecha_fin
                      GROUP BY u.nombre
                      ORDER BY total_logins DESC";
            $params = ['fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin];
            break;

        case 'productos_menos_comprados':
            $query = "SELECT p.Nombre, SUM(v.Cantidad) as total_comprado 
                      FROM historial_ventas v 
                      INNER JOIN productos p ON v.IDP = p.IDP 
                      WHERE v.Fecha BETWEEN :fecha_inicio AND :fecha_fin
                      GROUP BY p.Nombre
                      ORDER BY total_comprado ASC";
            $params = ['fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin];
            break;

        case 'empleados_mas_horas':
            $query = "SELECT u.nombre, SUM(l.duracion) as total_horas
                      FROM logins l
                      INNER JOIN usuarios u ON l.id_usuario = u.id_usuario
                      WHERE l.fecha BETWEEN :fecha_inicio AND :fecha_fin
                      GROUP BY u.nombre
                      ORDER BY total_horas DESC";
            $params = ['fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin];
            break;
    }

    // Ejecutar la consulta y mostrar los resultados
    $statement = $con->prepare($query);
    $statement->execute($params);
    $resultados = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo '<div class="container mt-4">';
    echo '<h3>Resultados del Informe</h3>';
    echo '<table class="table table-dark">';
    echo '<thead><tr>';

    // Mostrar los encabezados según el tipo de informe
    switch ($reporte_tipo) {
        case 'productos_mas_comprados':
        case 'productos_menos_comprados':
            echo '<th>Producto</th><th>Total Comprado</th>';
            break;
        case 'proveedores_mas_solicitados':
            echo '<th>Proveedor</th><th>Total Solicitudes</th>';
            break;
        case 'ganancias_dia':
        case 'ganancias_total':
            echo '<th>Ganancias</th>';
            break;
        case 'flujo_usuarios':
            echo '<th>Usuario</th><th>Total Logins</th>';
            break;
        case 'empleados_mas_horas':
            echo '<th>Empleado</th><th>Total Horas</th>';
            break;
    }

    echo '</tr></thead>';
    echo '<tbody>';

    // Mostrar los datos
    foreach ($resultados as $resultado) {
        echo '<tr>';
        foreach ($resultado as $campo) {
            echo '<td>' . htmlspecialchars($campo) . '</td>';
        }
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
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
            Informes Dinámicos
        </h2>
        <div class="container form-container">
            <form method="post" action="">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="reporte_tipo" class="form-label">Tipo de Informe</label>
                        <select name="reporte_tipo" id="reporte_tipo" class="form-control">
                            <option value="productos_mas_comprados">Productos Más Comprados</option>
                            <option value="proveedores_mas_solicitados">Proveedores Más Solicitados</option>
                            <option value="ganancias_dia">Ganancias del Día</option>
                            <option value="ganancias_total">Ganancias Totales</option>
                            <option value="flujo_usuarios">Flujo de Usuarios</option>
                            <option value="productos_menos_comprados">Productos Menos Comprados</option>
                            <option value="empleados_mas_horas">Empleados con Más Horas</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                    </div>

                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary mt-3">Generar Informe</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts de Bootstrap y otros -->
    <script src="JS/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
