<?php 
include_once 'conexion.php';
session_start();
include_once("sweetarch.php");
if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}

// Procesamiento de la nueva categoría
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_nueva_categoria'])) {
    $nueva_categoria = $_POST['nueva_categoria'];

    // Verificar si la categoría ya existe
    $query_check_categoria = "SELECT COUNT(*) FROM categorias WHERE Nombre_Categoria = ?";
    $statement_check_categoria = $con->prepare($query_check_categoria);
    $statement_check_categoria->execute([$nueva_categoria]);
    $categoria_existente = $statement_check_categoria->fetchColumn();

    if ($categoria_existente > 0) {
        // Si la categoría ya existe, mostrar alerta y no insertar
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'La categoría ya existe.',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href='mandarproducto.php';
                });
              </script>";
    } else {
        // Insertar la nueva categoría si no existe
        $query_insert_categoria = "INSERT INTO categorias (Nombre_Categoria) VALUES (?)";
        $statement_insert_categoria = $con->prepare($query_insert_categoria);
        $statement_insert_categoria->execute([$nueva_categoria]);

        // Redirigir para recargar la página y actualizar las opciones del select
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Añadió la categoría de forma exitosa.',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href='mandarproducto.php';
                });
              </script>";
    }
}

// Agregar productos
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['btn_nueva_categoria'])) {
    $nombre = $_POST['nombre'];
    $precio = floatval($_POST['precio']);  // Convertir a número flotante para asegurar precisión
    $categoria = $_POST['categoria'];
    $cantidad = intval($_POST['cantidad']);  // Convertir a número entero
    $descripcion = $_POST['descripcion'];
    $foto = '';
  
    // Validar que precio y cantidad no sean negativos
    if ($precio < 0 || $cantidad < 0) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'El precio y la cantidad no pueden ser negativos.',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href='mandarproducto.php';
                });
              </script>";
        exit;
    }
  
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_dir = 'uploads/';
        // Crear el directorio si no existe
        if (!file_exists($foto_dir)) {
            mkdir($foto_dir, 0777, true);
        }
        $foto_name = basename($_FILES['foto']['name']);
        $foto = $foto_dir . $foto_name;
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    }
  
    $estado = 'Pendiente';  // Estado inicial predeterminado
  
    $query_insert = "INSERT INTO productos (Nombre, Precio, Categoria, Cantidad, Descripcion, Foto, Estado) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
    $statement_insert = $con->prepare($query_insert);
    $statement_insert->execute([$nombre, $precio, $categoria, $cantidad, $descripcion, $foto_name, $estado]);
  
    echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Añadió el producto de forma exitosa.',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href='mandarproducto.php';
            });
          </script>";
    exit;
}

// Consultar las categorías existentes para el select
$query_categorias = "SELECT Nombre_Categoria FROM categorias";
$statement_categorias = $con->prepare($query_categorias);
$statement_categorias->execute();
$categorias = $statement_categorias->fetchAll(PDO::FETCH_ASSOC);

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
<h2 class="mb-4">        <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
Enviar Productos</h2>
<form action="" method="post" enctype="multipart/form-data">
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="nombre" class="form-label text-white">Nombre producto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="col-md-6">
            <label for="precio" class="form-label text-white">Precio</label>
            <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="categoria" class="form-label text-white">Categoría</label>
            <select class="form-control" id="categoria" name="categoria">
                <?php foreach ($categorias as $categoria) { 
                    echo '<option value="' . $categoria['Nombre_Categoria'] . '">' . $categoria['Nombre_Categoria'] . '</option>';
                } ?>
            </select> 
        </div>
        <div class="col-md-6">
            <label for="cantidad" class="form-label text-white">Cantidad</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <label for="descripcion" class="form-label text-white">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <label for="foto" class="form-label text-white">Foto</label>
            <input type="file" class="form-control" id="foto" name="foto">
        </div>
    </div>

    <div class="row">
        <div class="col-12 text-center">
            <a href="index.php" class="btn btn-danger">Regresar</a>
            <button type="submit" class="btn btn-primary">Enviar Producto</button>
        </div>
    </div>
</form>

<form action="" method="post" enctype="multipart/form-data">
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="nueva_categoria" class="form-label text-white">Nueva Categoría</label>
            <input type="text" class="form-control" id="nueva_categoria" name="nueva_categoria" required>
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <button type="submit" class="btn btn-primary" name="btn_nueva_categoria">Agregar Categoría</button>
        </div>
    </div>
</form>

</div>

</body>
</html>