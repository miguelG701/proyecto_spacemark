<?php 
include_once 'conexion.php';
session_start();
include_once("sweetarch.php"); // Asegúrate de incluir SweetAlert si no lo has hecho aún

if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}

// Obtener categorías
$query_categorias = "SELECT Nombre_Categoria FROM categorias";
$statement_categorias = $con->prepare($query_categorias);
$statement_categorias->execute();
$categorias = $statement_categorias->fetchAll(PDO::FETCH_ASSOC);

// Procesar la actualización si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $id_producto = $_POST['actualizar'];
    $precio = $_POST['precio'][$id_producto];
    $categoria = $_POST['categoria'][$id_producto];
    $descripcion = $_POST['descripcion'][$id_producto];

    $query = "UPDATE productos SET 
                Precio = :precio,
                Categoria = :categoria,
                Descripcion = :descripcion
              WHERE IDP = :id_producto";
    $statement = $con->prepare($query);
    $statement->bindParam(':precio', $precio);
    $statement->bindParam(':categoria', $categoria);
    $statement->bindParam(':descripcion', $descripcion);
    $statement->bindParam(':id_producto', $id_producto);

    if ($statement->execute()) {
        echo "<script>
                Swal.fire({
                    title: 'Éxito',
                    text: 'Producto actualizado correctamente.',
                    icon: 'success'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'gestionproductos.php';
                    }
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al actualizar el producto.',
                    icon: 'error'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'index.php';
                    }
                });
              </script>";
    }
    exit;
}

// Configurar búsqueda por nombre y categoría
$nombre_buscar = isset($_GET['nombre_buscar']) ? $_GET['nombre_buscar'] : '';
$categoria_buscar = isset($_GET['categoria_buscar']) ? $_GET['categoria_buscar'] : '';

// Obtener los productos de la base de datos con estado "Aceptado"
$query = "SELECT * FROM productos WHERE Estado = 'Aceptado' AND Nombre LIKE :nombre AND Categoria LIKE :categoria";
$statement = $con->prepare($query);
$statement->execute([
    ':nombre' => '%' . $nombre_buscar . '%',
    ':categoria' => '%' . $categoria_buscar . '%'
]);
$productos = $statement->fetchAll(PDO::FETCH_ASSOC);
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
    </style>
    <!-- Script de SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-4 mb-4">        <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
        Gestión de Productos</h2>

        <!-- Formulario de búsqueda -->
        <form action="" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="nombre_buscar" class="form-control" placeholder="Buscar por nombre" value="<?php echo htmlspecialchars($nombre_buscar); ?>">
                </div>
                <div class="col-md-4">
                    <input type="text" name="categoria_buscar" class="form-control" placeholder="Buscar por categoría" value="<?php echo htmlspecialchars($categoria_buscar); ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a href="index.php" class="btn btn-danger">Regresar</a>
                </div>
            </div>
        </form>

        <form action="" method="POST" onsubmit="return validateForm()">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['Nombre']); ?></td>
                        <td><input type="number" step="0.01" name="precio[<?php echo $producto['IDP']; ?>]" value="<?php echo htmlspecialchars($producto['Precio']); ?>" class="form-control" min="0"></td>
                        <td>
                            <select class="form-control" name="categoria[<?php echo $producto['IDP']; ?>]">
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?php echo htmlspecialchars($categoria['Nombre_Categoria']); ?>" <?php echo $producto['Categoria'] == $categoria['Nombre_Categoria'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($categoria['Nombre_Categoria']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="text" name="descripcion[<?php echo $producto['IDP']; ?>]" value="<?php echo htmlspecialchars($producto['Descripcion']); ?>" class="form-control"></td>
                        <td><button type="submit" name="actualizar" value="<?php echo $producto['IDP']; ?>" class="btn btn-primary">Actualizar</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
    <!-- Scripts de Bootstrap y otros -->
    <script src="JS/bootstrap.bundle.min.js"></script>
    <script>
        function validateForm() {
            const inputs = document.querySelectorAll('input[type="number"]');
            for (let input of inputs) {
                if (input.value < 0) {
                    Swal.fire({
                        title: 'Error',
                        text: 'El precio no puede ser negativo.',
                        icon: 'error'
                    });
                    return false;
                }
            }
            return true;
        }
    </script>
</body>
</html>