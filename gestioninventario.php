<?php
include_once 'conexion.php';
session_start();
include_once("sweetarch.php");

if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}

// Obtener categorías disponibles
$query_categorias = "SELECT Nombre_Categoria FROM categorias";
$statement_categorias = $con->prepare($query_categorias);
$statement_categorias->execute();
$categorias = $statement_categorias->fetchAll(PDO::FETCH_ASSOC);

// Gestionar inventario ini
if (isset($_POST['guardar_inventario'])) {
    $cambios = 0;
    foreach ($_POST['productos'] as $id => $producto) {
        if ($producto['estado'] === 'Eliminar' || $producto['nombre'] !== $_POST['original'][$id]['nombre'] || 
            $producto['precio'] != $_POST['original'][$id]['precio'] || 
            $producto['categoria'] !== $_POST['original'][$id]['categoria'] || 
            $producto['cantidad'] != $_POST['original'][$id]['cantidad'] || 
            $producto['descripcion'] !== $_POST['original'][$id]['descripcion'] || 
            $producto['estado'] !== $_POST['original'][$id]['estado']) {
            
            $cambios++;
            if ($cambios > 1) {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Solo se puede modificar un producto a la vez.'
                        }).then(() => {
                            window.location.href='gestioninventario.php';
                        });
                      </script>";
                exit;
            }
        }
    }

    foreach ($_POST['productos'] as $id => $producto) {
        if ($producto['estado'] === 'Eliminar') {
            // Eliminar el producto
            $query = "DELETE FROM productos WHERE IDP = :id";
            $statement = $con->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->execute();
        } else {
            // Validar precios y cantidades
            if ($producto['precio'] < 0 || $producto['cantidad'] < 0) {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'El precio y la cantidad no pueden ser menores a 0.'
                        }).then(() => {
                            window.location.href='gestioninventario.php';
                        });
                      </script>";
                exit;
            }
            
            // Actualizar el producto
            $query = "UPDATE productos SET 
                        Nombre = :nombre,
                        Precio = :precio,
                        Categoria = :categoria,
                        Cantidad = :cantidad,
                        Descripcion = :descripcion,
                        Estado = :estado 
                      WHERE IDP = :id";
            $statement = $con->prepare($query);
            $statement->bindParam(':nombre', $producto['nombre']);
            $statement->bindParam(':precio', $producto['precio']);
            $statement->bindParam(':categoria', $producto['categoria']);
            $statement->bindParam(':cantidad', $producto['cantidad']);
            $statement->bindParam(':descripcion', $producto['descripcion']);
            $statement->bindParam(':estado', $producto['estado']);
            $statement->bindParam(':id', $id);
            $statement->execute();
        }
    }
    // Redirigir o mostrar un mensaje de éxito
    echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Inventario actualizado correctamente.'
            }).then(() => {
                window.location.href='gestioninventario.php';
            });
          </script>";
    exit;
}
// Gestionar inventario fin

// Procesar la búsqueda
$busqueda_text = '';
if (isset($_POST['btn_buscar'])) {
    $busqueda_text = $_POST['busqueda'];
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>SpaceMark</title>
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
    </style>
</head>
<body>
    <div class="container">
        <!-- ver inventario lista ini -->
        <form action="" method="POST">
            <div class="modal-body">
            <h1 class="mt-5">        <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
            Gestion productos</h1>
                <div class="row justify-content-center">
                    <div class="col-5 mt-5">
                        <input type="text" name="busqueda" placeholder="Nombre o Categoría..." class="form-control" value="<?php echo htmlspecialchars($busqueda_text); ?>">
                    </div>
                    <div class="col-5 mt-5">
                        <button class="btn btn-primary" type="submit" name="btn_buscar">Buscar</button>
                        <a href="index.php" class="btn btn-danger">Regresar</a>
                        <button type="submit" class="btn btn-outline-danger btn-sm" name="guardar_inventario">Guardar Cambios</button>
                    </div>
                </div>
  
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Categoría</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($busqueda_text != '') {
                            $query_inventarios = "SELECT * FROM productos WHERE Estado = 'Aceptado' AND (Nombre LIKE :busqueda OR Categoria LIKE :busqueda)";
                            $statement_inventarios = $con->prepare($query_inventarios);
                            $statement_inventarios->bindValue(':busqueda', '%' . $busqueda_text . '%');
                        } else {
                            $query_inventarios = "SELECT * FROM productos WHERE Estado = 'Aceptado'";
                            $statement_inventarios = $con->prepare($query_inventarios);
                        }
                        
                        $statement_inventarios->execute();
                        $inventarios = $statement_inventarios->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($inventarios as $inventario) {
                            echo "<tr>";
                            echo "<td><input type='text' class='form-control' name='productos[" . htmlspecialchars($inventario['IDP']) . "][nombre]' value='" . htmlspecialchars($inventario['Nombre']) . "'></td>";
                            echo "<td><input type='number' step='0.01' min='0' class='form-control' name='productos[" . htmlspecialchars($inventario['IDP']) . "][precio]' value='" . htmlspecialchars($inventario['Precio']) . "'></td>";
                            echo "<td><select class='form-control' name='productos[" . htmlspecialchars($inventario['IDP']) . "][categoria]'>";
                            foreach ($categorias as $categoria) {
                                echo "<option value='" . htmlspecialchars($categoria['Nombre_Categoria']) . "'" . ($categoria['Nombre_Categoria'] == $inventario['Categoria'] ? ' selected' : '') . ">" . htmlspecialchars($categoria['Nombre_Categoria']) . "</option>";
                            }
                            echo "</select></td>";
                            echo "<td><input type='number' min='0' class='form-control' name='productos[" . htmlspecialchars($inventario['IDP']) . "][cantidad]' value='" . htmlspecialchars($inventario['Cantidad']) . "'></td>";
                            echo "<td><input type='text' class='form-control' name='productos[" . htmlspecialchars($inventario['IDP']) . "][descripcion]' value='" . htmlspecialchars($inventario['Descripcion']) . "'></td>";
                            
                            echo "<td>";
                            echo "<select class='form-control' name='productos[" . htmlspecialchars($inventario['IDP']) . "][estado]'>";
                            echo "<option value='Aceptado'" . ($inventario['Estado'] == 'Aceptado' ? ' selected' : '') . ">Aceptado</option>";
                            echo "<option value='Pendiente'" . ($inventario['Estado'] == 'Pendiente' ? ' selected' : '') . ">Pendiente</option>";
                            echo "<option value='Eliminar'>Eliminar</option>";
                            echo "</select>";
                            echo "</td>";
                            echo "</tr>";

                            // Agregar campos hidden para almacenar los valores originales
                            echo "<input type='hidden' name='original[" . htmlspecialchars($inventario['IDP']) . "][nombre]' value='" . htmlspecialchars($inventario['Nombre']) . "'>";
                            echo "<input type='hidden' name='original[" . htmlspecialchars($inventario['IDP']) . "][precio]' value='" . htmlspecialchars($inventario['Precio']) . "'>";
                            echo "<input type='hidden' name='original[" . htmlspecialchars($inventario['IDP']) . "][categoria]' value='" . htmlspecialchars($inventario['Categoria']) . "'>";
                            echo "<input type='hidden' name='original[" . htmlspecialchars($inventario['IDP']) . "][cantidad]' value='" . htmlspecialchars($inventario['Cantidad']) . "'>";
                            echo "<input type='hidden' name='original[" . htmlspecialchars($inventario['IDP']) . "][descripcion]' value='" . htmlspecialchars($inventario['Descripcion']) . "'>";
                            echo "<input type='hidden' name='original[" . htmlspecialchars($inventario['IDP']) . "][estado]' value='" . htmlspecialchars($inventario['Estado']) . "'>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-danger btn-sm" name="guardar_inventario">Guardar Cambios</button>
            </div>
        </form>

        <!-- ver inventario lista fin -->
    </div>
</body>
</html>