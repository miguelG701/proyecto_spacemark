<?php
include_once 'conexion.php';
session_start();
include_once("sweetarch.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: pgindex.php");
    exit;
}

// Manejar la eliminación de imágenes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && isset($_POST['imagen_id'])) {
    $imagen_id = $_POST['imagen_id'];
    
    // Consulta para obtener la información de la imagen
    $query = "SELECT imagen FROM imagenes WHERE id = :id";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id', $imagen_id);
    $stmt->execute();
    $imagen = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($imagen) {
        $imagenRuta = 'uploads/' . $imagen['imagen'];
        
        // Eliminar el archivo físico
        if (file_exists($imagenRuta)) {
            unlink($imagenRuta);
        }

        // Eliminar el registro de la base de datos
        $query = "DELETE FROM imagenes WHERE id = :id";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':id', $imagen_id);
        $stmt->execute();
        
        echo '<script>Swal.fire("Éxito", "Imagen eliminada con éxito", "success");</script>';
    } else {
        echo '<script>Swal.fire("Error", "Imagen no encontrada", "error");</script>';
    }
}

// Manejar la carga de nuevas imágenes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen'])) {
    $tipo = $_POST['tipo'];
    $imagen = $_FILES['imagen'];

    if ($imagen['error'] === UPLOAD_ERR_OK) {
        $imagenDir = 'uploads/';
        if (!file_exists($imagenDir)) {
            mkdir($imagenDir, 0777, true);
        }

        $imagenNombre = basename($imagen['name']);
        $imagenRuta = $imagenDir . $imagenNombre;

        if (move_uploaded_file($imagen['tmp_name'], $imagenRuta)) {
            try {
                $sql = 'INSERT INTO imagenes (imagen, tipo) VALUES (:imagen, :tipo)';
                $stmt = $con->prepare($sql);
                $stmt->bindParam(':imagen', $imagenNombre);
                $stmt->bindParam(':tipo', $tipo);
                $stmt->execute();

                echo '<script>Swal.fire("Éxito", "Imagen subida con éxito", "success");</script>';
            } catch (PDOException $e) {
                echo '<script>Swal.fire("Error", "Error al guardar en la base de datos: ' . $e->getMessage() . '", "error");</script>';
            }
        } else {
            echo '<script>Swal.fire("Error", "Error al mover el archivo", "error");</script>';
        }
    } else {
        echo '<script>Swal.fire("Error", "Error al subir el archivo", "error");</script>';
    }
}

// Obtener las imágenes para mostrar en la tabla
$query = "SELECT id, imagen, tipo FROM imagenes";
$stmt = $con->prepare($query);
$stmt->execute();
$imagenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
            color: #ffffff;
            padding-top: 20px;
        }
        .container {
            background-color: #343a40;
            color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            margin: 20px auto;
            max-width: 900px;
        }
        .table-dark {
            background-color: #343a40;
            color: #ffffff;
            border-radius: 10px;
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .modal-footer {
            text-align: center;
        }
        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
        }
        .btn-outline-danger:hover {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .form-control.estado {
            width: auto;
            display: inline-block;
        }
        @media (max-width: 576px) {
            .container {
                padding: 15px;
            }
            .table-dark {
                font-size: 0.875rem;
            }
            .form-group, .form-row, .modal-footer {
                text-align: center;
            }
            .btn {
                font-size: 0.875rem;
            }
            .form-control {
                width: 100%;
            }
        }
        @media (min-width: 768px) {
            .col-md-6 {
                max-width: 45%;
            }
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h2 class="text-center">
            <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
            Gestión de Imágenes Promocionales
        </h2>
        <form action="" method="POST" class="form-container" enctype="multipart/form-data">
            <div class="form-group">
                <label for="imagen">Seleccionar Imagen:</label>
                <input type="file" name="imagen" id="imagen" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de Imagen:</label>
                <select name="tipo" id="tipo" class="form-control" required>
                    <option value="carrusel">Carrusel</option>
                    <option value="promocion">Promoción</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Subir Imagen</button>
        </form>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <a href="index.php" class="btn btn-danger">Regresar</a>
            </div>
        </div>
    </div>



<!-- Tabla para ver los registros de la tabla "imagenes" -->
<div class="container">
        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">Imagen</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($imagenes as $imagen): ?>
                    <tr>
                        <td><img src="uploads/<?php echo htmlspecialchars($imagen['imagen']); ?>" alt="Imagen" style="width: 100px;"></td>
                        <td><?php echo htmlspecialchars($imagen['tipo']); ?></td>
                        <td>
                            <form action="" method="POST" style="display: inline;">
                                <input type="hidden" name="imagen_id" value="<?php echo htmlspecialchars($imagen['id']); ?>">
                                <button type="submit" name="delete" class="btn btn-outline-danger">Borrar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    
    <script src="JS/bootstrap.bundle.min.js"></script>
</body>
</html>
