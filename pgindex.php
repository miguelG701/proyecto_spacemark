<?php
include_once 'conexion.php';
include_once "sweetarch.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['registrar'])) {
        if (isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['telefono']) && isset($_POST['usuario']) && isset($_POST['contrasena']) && isset($_POST['tipo_usuario'])) {
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $telefono = $_POST['telefono'];
            $usuario = $_POST['usuario'];
            $contrasena = $_POST['contrasena'];
            $tipo_usuario = $_POST['tipo_usuario'];
            $contrasena_cifrada = sha1($contrasena);

            // Verificar si el usuario ya existe
            $query_check_user = "SELECT * FROM usuarios WHERE usuario = ?";
            $statement_check_user = $con->prepare($query_check_user);
            $statement_check_user->execute([$usuario]);
            $usuario_existente = $statement_check_user->fetch();

            if ($usuario_existente) {
                // Si el usuario ya existe, mostrar una alerta
                echo "<script>
                swal.fire({ title:'¡ El usuario ya existe. !',
                icon: 'warning',
                button: 'Aceptar'}).then(function(){window.location.href='pgindex.php';});</script>";

            } else {
                // Si el usuario no existe, realizar la inserción en la base de datos
                $aceptado = ($tipo_usuario == 2) ? "si" : "no"; // Si es cliente (id_tipos = 2), aceptado por defecto "si"
                $query_insert = "INSERT INTO usuarios (usuario, nombre, correo_electronico, telefono, contraseña_usuario, id_tipos, aceptado) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $statement_insert = $con->prepare($query_insert);
                $statement_insert->execute([$usuario, $nombre, $correo, $telefono, $contrasena_cifrada, $tipo_usuario, $aceptado]);
                echo "<script>
                swal.fire({ title:'¡ El usuario ha sido registrado !',
                icon: 'success',
                button: 'Aceptar'}).then(function(){window.location.href='pgindex.php';});</script>";
                exit;
            }
        }
    }
}

// Inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['iniciar'])) {
        if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
            $usuario = $_POST['usuario'];
            $contrasena = $_POST['contrasena'];
            $contrasena_cifrada2= sha1($contrasena);

            // Consulta para obtener el usuario de la base de datos
            $query = "SELECT * FROM usuarios WHERE usuario = ?";
            $statement = $con->prepare($query);
            $statement->execute([$usuario]);
            $usuario_db = $statement->fetch();

            if ($usuario_db && $contrasena_cifrada2 === $usuario_db['contraseña_usuario']) {
                // Verificar si el usuario ha sido aceptado
                $aceptado = $usuario_db['aceptado'];
                if ($aceptado === 'si') {
                    // La contraseña es correcta y el usuario ha sido aceptado, iniciar sesión
                    $_SESSION['usuario_id'] = $usuario_db['id_usuario'];
                    $_SESSION['usuario_nombre'] = $usuario_db['nombre'];
                    $_SESSION['usuario_tipo'] = $usuario_db['id_tipos'];
                  
                    header("Location: index.php");
                    exit;
                } else {
                echo "<script>
                swal.fire({ title:'¡ El usuario no ha sido aceptado !',
                icon: 'warning',
                button: 'Aceptar'}).then(function(){window.location.href='pgindex.php';});</script>";
                }
            } else {
                echo "<script>
                swal.fire({ title:'¡ El usuario o contraseña son incorrectos. !',
                icon: 'warning',
                button: 'Aceptar'}).then(function(){window.location.href='pgindex.php';});</script>";           
            }
        } else {
            echo "<script>
            swal.fire({ title:'¡ Por favor, complete todos los campos. !',
            icon: 'warning',
            button: 'Aceptar'}).then(function(){window.location.href='pgindex.php';});</script>";
        }
    }
}

// Verificar si se envió el formulario para enviar el código de recuperación y guardar contraseña o actualizar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['guardar_contraseña'])) {
        $usuario = $_POST['usuario'];
        $nombre = $_POST['nombre'];
        $contrasena = $_POST['contrasena'];
        $contrasena_cifrada3 = sha1($contrasena);

        // Consultar si el usuario y el nombre coinciden en la base de datos
        $query = "SELECT * FROM usuarios WHERE usuario = ? AND nombre = ?";
        $statement = $con->prepare($query);
        $statement->execute([$usuario, $nombre]);
        $usuario_db = $statement->fetch();

        if ($usuario_db) {
            // Verificar si el usuario es administrador
            if ($usuario_db['id_tipos'] == 1) {
                // Si el usuario es administrador, mostrar una alerta indicando que no se puede actualizar la contraseña
                echo "<script>
                swal.fire({ title:'¡ El usuario que estás intentando actualizar es un administrador y no se puede cambiar la contraseña. !',
                icon: 'warning',
                button: 'Aceptar'}).then(function(){window.location.href='pgindex.php';});</script>";
            } else {
                // Si el usuario no es administrador, actualizar la contraseña en la base de datos para el usuario correspondiente
                $query_update = "UPDATE usuarios SET contraseña_usuario = ? WHERE usuario = ?";
                $statement_update = $con->prepare($query_update);
                $statement_update->execute([$contrasena_cifrada3, $usuario]);

                // Redirigir a alguna página después de guardar la contraseña
                header("Location: pgindex.php");
                exit;
            }
        } else {
            echo "<script>
            swal.fire ({ title:'¡ Usuario o nombre incorrectos. !',
            icon: 'warning',
            button: 'Aceptar'}).then(function(){window.location.href='pgindex.php';});</script>";   
        }
    }
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
        .navbar {
            margin-bottom: 20px;
            background-color: #343a40; /* Color de fondo oscuro */

        }
        .navbar-dark .form-control {
            background-color: #495057; /* Color de fondo oscuro para el campo de búsqueda */
            color: #ffffff; /* Color de texto blanco para el campo de búsqueda */
            border: none; /* Eliminar borde del campo de búsqueda */
        }
        .navbar-nav {
            margin-left: auto;
        }
        .form-inline {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            margin-left: auto;
            margin-right: auto;
        }
        .form-inline .form-control {
            flex: 1;
            margin-right: 10px;
        }
        .form-inline .btn {
            margin-left: 10px;
        }
        .btn-regresar {
            margin-top: 10px;
        }
        #search-input::placeholder {
            color: #fff; /* Cambia este color al deseado */
            opacity: 1; /* Asegúrate de que el placeholder sea opaco */
        }
    </style>
</head>
<body>


  <!-- navergador inicio -->

  <nav class="navbar navbar-expand-lg navbar-dark shadow ">
    <div class="container-fluid">
        <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
        <a class="navbar-brand" href="#">SpaceMark</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Buscador con filtro de categoría -->
            <div class="d-flex align-items-center mx-auto my-2 my-lg-0">
                <input id="search-input" class="form-control me-2" type="search" placeholder="¿ Qué deseas buscar ?" aria-label="Search">
                <select id="category-select" class="form-control me-2">
                    <option value="">Todas las Categorías</option>
                    <?php
                    // Consulta para obtener las categorías
                    $query_categorias = "SELECT DISTINCT Categoria FROM productos WHERE Estado = 'Aceptado' AND Cantidad > 0";
                    $stmt_categorias = $con->prepare($query_categorias);
                    $stmt_categorias->execute();
                    $categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($categorias as $categoria) {
                        echo "<option value='" . htmlspecialchars($categoria['Categoria']) . "'>" . htmlspecialchars($categoria['Categoria']) . "</option>";
                    }
                    ?>
                </select>
                <button id="search-button" class="btn btn-outline-primary" type="button">Buscar</button>
            </div>
            <!-- Botones de acción -->
            <div class="ms-auto">
                <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalreg0">Registro</button>
                <button class="btn btn-outline-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#modalini1">Iniciar Sesión</button>
            </div>
        </div>
    </div>
</nav>

<script src="js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('search-button').addEventListener('click', function(event) {
    event.preventDefault();
    var searchTerm = document.getElementById('search-input').value.toLowerCase();
    var selectedCategory = document.getElementById('category-select').value;
    var products = document.querySelectorAll('.col-md-3'); // Asegúrate de que tus productos tengan esta clase
    
    products.forEach(function(product) {
        var productName = product.querySelector('.product-name').textContent.toLowerCase();
        var productCategory = product.dataset.category; // Asegúrate de que tus productos tengan el atributo data-category
        
        var matchesSearchTerm = productName.includes(searchTerm);
        var matchesCategory = (selectedCategory === '' || productCategory === selectedCategory);
        
        if (matchesSearchTerm && matchesCategory) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
});
</script>

<!-- navergador fin -->











<!-- procesosa del nav ini-->

<!-- registro  -->
<div class="modal fade"
            id="modalreg0"
            tabindex="1"
            aria-hidden="true"
            aria-labelledby="label-modalreg0">
            <!-- caja de dialogo -->
            <div class="modal-dialog">
                <div class="modal-content">         
                               
                    <!-- cuerpo -->
                    <div class="modal-body">
                    <form action="" method="post">
                            <div class="modal-body">
                            <div class="form-text text-center">
                            <img src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="70" class="me-2">
                            </div>
                                <div class="form-text text-center"><u>Registro</u></div>
                                <div class="mt-3">
                                    <div class="row mb-3">
                                        <div class="col-12"> 
                                            <input type="text" class="form-control" name="nombre" placeholder="Nombre" required>
                                        </div>
                                        <div class="form-group form-text">
                                            <div id="ayuda-correo" class="form-text">
                                                Correo Personal
                                                <input type="email" class="form-control" name="correo" aria-describedby="ayuda-correo" placeholder="Correo" required>
                                            </div>
                                        </div>
                                    </div>
                            
                                    <div class="row mb-3">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-text">Numero Celular</div>
                                            <input type="number" name="telefono" placeholder="+57" class="form-control" id="inputZip" required>
                                        </div>
                                        
                                        <div class="col-lg-6">
                                            <div class="form-text">Tipo de Usuario</div>
                                            <select class="form-select" name="tipo_usuario" required>
                                                <option value="">Selecciona un tipo de usuario</option>
                                                <!-- <option value="1">Administrador</option> -->
                                                <option value="2">Cliente</option>
                                                <option value="3">Proveedor</option>
                                                <option value="4">Empleado</option>
                                                <option value="5">Gerente</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-6 mb-3">
                                            <div class="form-text">Usuario</div>
                                            <input type="text" name="usuario" placeholder="User" class="form-control" id="inputZip" required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="form-text">Contraseña</div>
                                            <input type="password" name="contrasena" placeholder="Password" class="form-control" id="inputZip" required>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                      <div class="form-check">
                                      <input class="form-check-input" type="checkbox" name="terminos" required>
                                        <label class="text-dark opacity-75 form-check-label" for="terminos">
                                            Acepta los términos y condiciones.
                                            <p>Consentimiento de Cookies. Al continuar navegando en Spacemark, aceptas el uso de cookies de acuerdo con nuestra política de cookies.</p>
                                            <div class="nav-item"><a href="CondicionesUso.php" class="nav-link px-2 text-body-secondary"><u>Condiciones de uso</u></a></div>

                                        </label>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="submit" class="mx-auto btn btn-outline-danger btn-sm" name="registrar">Registrar</button>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
          </div>
<!-- fin del registro  -->





<!-- inico de sesion -->

<div class="modal fade"
            id="modalini1"
            tabindex="2"
            aria-hidden="true"
            aria-labelledby="label-modalini1">
            <!-- caja de dialogo -->
            <div class="modal-dialog">
                <div class="modal-content">                    
                    <!-- cuerpo -->
                    <div class="modal-body">
                    <form action="" method="post">
                            <div class="modal-body">
                            <div class="form-text text-center">
                            <img src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="70" class="me-2">
                            </div>
                                <div class="form-text text-center"><u>Inicio de Sesion</u></div>
                                <div class="mt-3">
                                    <div class="row mb-3">
                                        <div class="col-12"> 
                                            <input type="text" class="form-control" name="usuario" placeholder="Usuario" required>
                                        </div>
                                        <div class="form-group form-text">
                                            <div id="ayuda-correo" class="form-text">
                                                Contraseña
                                                <input type="password" class="form-control" name="contrasena" aria-describedby="ayuda-correo" placeholder="Password" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="col-12 btn btn-outline-success btn-sm" name="iniciar">Iniciar Sesion</button>

                                </div>
                            </div>
                            <div class="modal-footer">
                                
                            
                                <div class="col-12 btn text-info" data-bs-toggle="modal" data-bs-target="#modalrecuper">
                                    <u>Olvidaste tu contraseña ?</u>
                                </div>
                                <div class="col-12 btn text-danger" data-bs-toggle="modal" data-bs-target="#modalreg0">
                                    <u>Regístrate si no lo has hecho</u>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                            
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
          </div>
          </div>
        </div>

<!-- fin del incio de sesion  -->


<!-- recupera con ini -->
<div class="modal fade" id="modalrecuper" tabindex="3" aria-labelledby="label-modalrecuper">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="" method="post">
                    <div class="modal-body">
                    <div class="form-text text-center">
                            <img src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="70" class="me-2">
                            </div>
                        <div class="form-text text-center"><u>Recupera tu contraseña</u></div>
                        <div class="mt-3">
                            <div class="row mb-3">
                                <div class="col-12"> 
                                    <input type="text" class="form-control" name="usuario" placeholder="Usuario" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12"> 
                                <input type="text" class="form-control" name="nombre" placeholder="Nombre" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12"> 
                                    <input type="password" class="form-control" name="contrasena" placeholder="Nueva Contraseña" required>
                            </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- Agregar campo oculto para enviar el ID del usuario -->
                        <input type="hidden" name="usuario_id" value="<?php echo isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : ''; ?>">
                        <button type="submit" class="mx-auto col-12 btn btn-outline-success btn-sm" name="guardar_contraseña">Guardar Contraseña</button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--recupera con fin-->

<!-- procesosa de los nav fin-->





<!-- carousel inicio-->
<div class="container">
        <div class="row mb-5">
            <div class="col">
            
            <?php
// Suponiendo que ya tienes la conexión establecida en $con

// Consulta para obtener las imágenes de tipo 'carrusel'
$query = "SELECT imagen FROM imagenes WHERE tipo = 'carrusel'";
$statement = $con->prepare($query);
$statement->execute();
$imagenes = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="carousel slide carousel-fade" id="mi-carousel" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php
        $active = 'active'; // La primera imagen debe ser la activa
        foreach ($imagenes as $index => $imagen): ?>
            <div class="carousel-item <?php echo $active; ?>">
                <img class="img-fluid rounded" src="uploads/<?php echo htmlspecialchars($imagen['imagen']); ?>" alt="Imagen carrusel">
            </div>
            <?php
            $active = ''; // Solo la primera imagen debe tener la clase 'active'
        endforeach;
        ?>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#mi-carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#mi-carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>

    <div class="carousel-indicators">
        <?php foreach ($imagenes as $index => $imagen): ?>
            <button type="button" data-bs-target="#mi-carousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>" aria-label="Slide <?php echo $index + 1; ?>"></button>
        <?php endforeach; ?>
    </div>
</div>               
            </div>
        </div>
<!-- carousel fin-->


<!-- inicio de Super mercado de SpaceMark -->

<?php
// Consulta para obtener los productos con cantidad mayor a cero
$query = "SELECT * FROM productos WHERE Estado = 'Aceptado' AND Cantidad > 0";
$statement = $con->prepare($query);
$statement->execute();
$productos = $statement->fetchAll(PDO::FETCH_ASSOC);

// Agrupar productos por categoría
$productosPorCategoria = [];
foreach ($productos as $producto) {
    $categoria = $producto['Categoria'];
    if (!isset($productosPorCategoria[$categoria])) {
        $productosPorCategoria[$categoria] = [];
    }
    $productosPorCategoria[$categoria][] = $producto;
}
?>
<style>
.card {
    height: 100%;
}

.fixed-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.card-body {
    display: flex;
    flex-direction: column;
}

.product-name {
    margin-bottom: auto;
}

.scrollable-row {
    display: flex;
    overflow-x: auto; /* Habilita el desplazamiento horizontal */
    gap: 1rem; /* Espacio entre las tarjetas */
    padding-bottom: 1rem; /* Espacio inferior para el desplazamiento */
}

.scrollable-row .card {
    flex: 0 0 auto; /* Evita que las tarjetas se reduzcan y permite desplazamiento lateral */
    width: 18rem; /* Ancho fijo para cada tarjeta */
}

.scrollable-row::-webkit-scrollbar {
    height: 8px; /* Altura de la barra de desplazamiento */
}

.scrollable-row::-webkit-scrollbar-thumb {
    background-color: #888; /* Color de la barra de desplazamiento */
    border-radius: 10px;
}

.scrollable-row::-webkit-scrollbar-track {
    background-color: #f1f1f1;
}

@media (max-width: 576px) {
    .scrollable-row .card {
        width: 14rem; /* Ajusta el ancho en pantallas pequeñas */
    }
}
</style>
<div class="container mt-4">
    <?php foreach ($productosPorCategoria as $categoria => $productos): ?>
        <h2><?php echo htmlspecialchars($categoria); ?></h2>
        <div class="scrollable-row">
            <?php foreach ($productos as $producto): ?>
                <div class="col-md-3 mb-4" data-category="<?php echo htmlspecialchars($categoria); ?>">
                    <div class="card h-100">
                        <?php if (!empty($producto['Foto']) && file_exists("uploads/" . htmlspecialchars($producto['Foto']))): ?>
                            <img class="card-img-top fixed-img" src="uploads/<?php echo htmlspecialchars($producto['Foto']); ?>" alt="Producto">
                        <?php else: ?>
                            <img class="card-img-top fixed-img" src="uploads/default.jpg" alt="Imagen no disponible">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <p class="h5 product-name"><?php echo htmlspecialchars($producto['Nombre']); ?></p>
                            <p class="h5 mt-auto">$<?php echo htmlspecialchars($producto['Precio']); ?></p>
                            <button type="button" class="btn btn-outline-danger btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modal-<?php echo $producto['IDP']; ?>">Comprar</button>
                        </div>
                    </div>
                    
                        <!-- Modal -->
                        <div class="modal fade" id="modal-<?php echo $producto['IDP']; ?>" tabindex="-1" aria-labelledby="label-modal-<?php echo $producto['IDP']; ?>" aria-hidden="true">
                          <div class="modal-dialog">
                              <div class="modal-content">
                                  <div class="modal-header">
                                  <img src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="30" class="me-2">
                                      <h5 class="modal-title" id="label-modal-<?php echo $producto['IDP']; ?>"> <?php echo htmlspecialchars($producto['Nombre']); ?></h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                  <div class="row">
                                            <div class="col-md-6">
                                                <?php if (!empty($producto['Foto']) && file_exists("uploads/" . htmlspecialchars($producto['Foto']))): ?>
                                                    <img class="img-fluid" src="uploads/<?php echo htmlspecialchars($producto['Foto']); ?>" alt="Producto">
                                                <?php else: ?>
                                                    <img class="img-fluid" src="uploads/default.jpg" alt="Imagen no disponible">
                                                <?php endif; ?>
                                            </div>
                                          <div class="col-md-6">
                                              <h5><?php echo htmlspecialchars($producto['Descripcion']); ?></h5>
                                              <p>Cantidad disponible: <?php echo htmlspecialchars($producto['Cantidad']); ?></p> <!-- Nueva línea para mostrar la cantidad disponible -->

                                              <form id="compra-form-<?php echo $producto['IDP']; ?>" action="" method="post">
                                                  <div class="mb-3 d-flex align-items-center">
                                                      <label for="cantidad-<?php echo $producto['IDP']; ?>" class="form-label me-2">Cantidad:</label>
                                                      <input type="number" class="form-control" id="cantidad-<?php echo $producto['IDP']; ?>" name="cantidad" min="1" max="<?php echo htmlspecialchars($producto['Cantidad']); ?>" required onchange="calcularTotal(<?php echo $producto['IDP']; ?>)">
                                                  </div>
                                                  <div class="mb-3">
                                                      <label for="metodo_pago-<?php echo $producto['IDP']; ?>" class="form-label">Método de Pago:</label>
                                                      <select class="form-control" id="metodo_pago-<?php echo $producto['IDP']; ?>" name="metodo_pago" required>
                                                          <option value="Efectivo">Efectivo</option>
                                                          <option value="Tarjeta">Tarjeta de Crédito/Débito</option>
                                                          <option value="Transferencia">Transferencia Bancaria</option>
                                                          <!-- Agrega más opciones según tus métodos de pago -->
                                                      </select>
                                                  </div>
                                                  <input type="hidden" name="id_producto" value="<?php echo $producto['IDP']; ?>">
                                                  <p>Precio: $<span id="precio-<?php echo $producto['IDP']; ?>"><?php echo htmlspecialchars($producto['Precio']); ?></span></p>
                                                  <p>Total: $<span id="total-<?php echo $producto['IDP']; ?>">0.00</span></p>
                                              </form>
                                          </div>
                                      </div>
                                  </div>
                                    <div class="modal-footer">

                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalini1" onclick="alertS()">Comprar</button>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalini1" onclick="alertS2()">Añadir al Carrito</button>
                                    </div>
                              </div>
                          </div>
                      </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    
<!-- aumenta el total del producto al cambiar la cantidad ini -->
<script>
    function alertS(){
    Swal.fire({
    icon: 'warning',
    title: 'Debes iniciar sesión para comprar',
    timer: 3000,
    timerProgressBar: true, 
    showConfirmButton: false})
    }

    function alertS2(){
    Swal.fire({
    icon: 'warning',
    title: 'Debes iniciar sesión para añadir al carrito',
    timer: 3000,
    timerProgressBar: true, 
    showConfirmButton: false})
    }
</script>

<script>
function calcularTotal(idProducto) {
    // Obtener el precio del producto
    var precio = parseFloat(document.getElementById('precio-' + idProducto).textContent)
    // Obtener la cantidad seleccionada por el usuario
    var cantidad = parseInt(document.getElementById('cantidad-' + idProducto).value)
    // Calcular el total
    var total = precio * cantidad
    // Mostrar el total calculado en el span correspondiente
    document.getElementById('total-' + idProducto).textContent = total.toFixed(2);
}
</script>

<!-- aumenta el total del producto al cambiar la cantidad fin -->

<!-- fin de Super mercado de SpaceMark -->

<div class="">
  <h3>Aprovecha estos descuentos para el hogar</h3>
</div>

<?php
// Consulta para obtener imágenes de tipo "promocion"
$query = "SELECT imagen FROM imagenes WHERE tipo = 'promocion'";
$statement = $con->prepare($query);
$statement->execute();
$imagenes = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<div>
    <div class="card-group">
        <?php foreach ($imagenes as $imagen): ?>
            <div class="card mb-5">
                <img src="uploads/<?php echo htmlspecialchars($imagen['imagen']); ?>" class="rounded" alt="Promoción">
            </div>
        <?php endforeach; ?>
    </div>
</div>

<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
  <symbol id="facebook" viewBox="0 0 16 16">
    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
  </symbol>
  <symbol id="instagram" viewBox="0 0 16 16">
      <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
  </symbol>
  <symbol id="twitter" viewBox="0 0 16 16">
    <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
  </symbol>
</svg>

<div class="container mt-5">
  <footer class="py-3 my-4">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
    <li class="nav-item"><a href="CondicionesUso.php" class="nav-link px-2 text-body-secondary"><u>Condiciones de uso</u></a></li>
    <li class="nav-item"><a href="DeclaracionPrivacidad.php" class="nav-link px-2 text-body-secondary"><u>Declaración de privacidad y de cookies</u></a></li>
      <li class="nav-item"><a href="FuncionSitio.php" class="nav-link px-2 text-body-secondary"><u>Cómo funciona el sitio</u></a></li>
    </ul>
    <ul class="nav justify-content-center list-unstyled d-flex">
      <li class="ms-3"><a class="link-body-emphasis" href="https://x.com/spacemarksag" target="_blank"><svg class="bi" width="24" height="24"><use xlink:href="#twitter"/></svg></a></li>
      <li class="ms-3"><a class="link-body-emphasis" href="https://www.instagram.com/spacemarksag/?utm_source=ig_web_button_share_sheet" target="_blank"><svg class="bi" width="24" height="24"><use xlink:href="#instagram"/></svg></a></li>
      <!-- <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#facebook"/></svg></a></li> -->
    </ul>
    <p class="text-center text-body-secondary mt-4">&copy; 2024 SpaceMark, Inc</p>
    <p class="text-center text-body-secondary mt-4"><small>2023 SpaceMark. Todos los derechos reservados. Todas las marcas registradas pertenecen a sus respectivos dueños en EE. UU. y otros países. Todos los precios incluyen IVA (donde sea aplicable).
      Política de Privacidad | Información legal | Acuerdo de Suscriptor a SpaceMark | Reembolsos | Cookies</small></p>

  </footer>
</div>
</body>
</html>