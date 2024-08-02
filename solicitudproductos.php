<?php
include_once 'conexion.php';
session_start();
include_once("sweetarch.php");

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

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $cantidad = $_POST['cantidad'];
    $descripcion = $_POST['descripcion'];
    $id_usuario = $_POST['id_usuario']; // Obtener el ID de usuario del campo oculto

    // Estado predeterminado
    $estado = 'Pendiente';

    // Validar que la cantidad no sea negativa
    if ($cantidad < 0) {
        echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'La cantidad no puede ser negativa.',
                    icon: 'error'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.back();
                    }
                });
              </script>";
        exit;
    }

    // Consulta SQL para insertar la solicitud en la tabla
    $query_insert = "INSERT INTO solicitudes (nombre, categoria, cantidad, descripcion, estado, id_usuario) 
                     VALUES (?, ?, ?, ?, ?, ?)";
    $statement_insert = $con->prepare($query_insert);
    $statement_insert->execute([$nombre, $categoria, $cantidad, $descripcion, $estado, $id_usuario]);

    // Redirigir a una página de confirmación o a donde sea necesario
    echo "<script>
            Swal.fire({
                title: 'Solicitud enviada',
                icon: 'success'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'solicitudproductos.php';
                }
            });
          </script>";
    exit;
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
    </style>
    <!-- Scripts de SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-4 mb-4">        <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
        Formulario de Solicitud de Producto</h2>
        <form action="" method="post" enctype="multipart/form-data" class="bg-dark p-4 rounded" onsubmit="return validateForm()">
            <div class="row mb-3">
                <div class="col-md-4 mb-3">
                    <label for="nombre" class="form-label">Nombre del producto</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <input list="categorias" class="form-control" id="categoria" name="categoria" required>
                    <datalist id="categorias">
                        <?php foreach ($categorias as $categoria) : ?>
                            <option value="<?php echo htmlspecialchars($categoria['Nombre_Categoria']); ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                </div>
            </div>
            <div class="row mb-3">
               <div class="col-12">
                   <label for="descripcion" class="form-label">Descripción <small>(opcional)</small></label>
                   <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Ingrese una breve descripción (opcional)"></textarea>
               </div>
            </div>
            <!-- Campo oculto para el ID de usuario -->
            <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($_SESSION['usuario_id']); ?>">
            <div class="row">
                <div class="col-12 text-center">
                    <a href="index.php" class="btn btn-danger">Regresar</a>
                    <button type="submit" class="btn btn-primary">Mandar solicitud</button>
                </div>
            </div>
        </form>
    </div>
    <!-- Scripts de Bootstrap y otros -->
    <script src="JS/bootstrap.bundle.min.js"></script>
    <script>
        function validateForm() {
            const cantidad = document.getElementById('cantidad').value;
            if (cantidad < 0) {
                Swal.fire({
                    title: 'Error',
                    text: 'La cantidad no puede ser negativa.',
                    icon: 'error'
                });
                return false;
            }
            return true;
        }
    </script>
</body>
</html>