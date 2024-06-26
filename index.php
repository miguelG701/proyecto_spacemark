<?php

include_once 'conexion.php';

session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}



// variables de alertas ini

$UsersCorrect = false;

$NotSelecteUsers = false;


// variables de alertas fin

// confirmar usuarios ini

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['guardar'])) {
      // Verificar si se han seleccionado usuarios para aceptar
      if (isset($_POST['usuarios_aceptar']) && !empty($_POST['usuarios_aceptar'])) {
          // Obtener los IDs de usuario seleccionados
          $usuarios_aceptar = $_POST['usuarios_aceptar'];

          // Actualizar el estado de aceptación para los usuarios seleccionados
          $query = "UPDATE usuarios SET aceptado = 'si' WHERE id_usuario IN (";
          $query .= implode(',', $usuarios_aceptar) . ")";
          
          // Ejecutar la consulta de actualización
          if ($con->query($query) === TRUE) {
            $UsersCorrect = true;
            // Redirigir a alguna página después de actualizar la base de datos
              header("Location: index.php");
              exit;
          }} else {
            $NotSelecteUsers = true;      
          }
  }
}

// confirmar usuarios fin


// // Verifica si se ha enviado el formulario para cerrar sesión
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cerrar_sesion'])) {
//   // Destruye la sesión
//   session_destroy();

//   // Redirige al usuario a pgindex.php
//   header("Location: pgindex.php");
//   exit;
// }


// if ($_SERVER["REQUEST_METHOD"] == "GET") {
//   // Verifica si 'usuario' está definido en $_SESSION
//   if(isset($_SESSION['usuario'])) {
//       // Obtenemos el nombre de usuario de la sesión
//       $usuario = $_SESSION['usuario'];

//       // Insertamos un registro en la tabla logs_ingreso
//       $query = "INSERT INTO logs_ingreso (usuario, fecha_hora_ingreso) VALUES (?, NOW())";
//       $statement = $con->prepare($query);
//       $statement->execute([$usuario]);
//   } else {
//       // Si 'usuario' no está definido en $_SESSION, muestra un mensaje de error o redirige a una página de inicio de sesión
//       echo "Error: Sesión de usuario no iniciada.";
//       // O redirige a una página de inicio de sesión
//       // header("Location: formulario_login.php");
//       exit;
//   }
// }

// Realizar la consulta a la base de datos para obtener los usuarios

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
</head>
<body>

<!-- alertas ini -->
<?php
        if ($UsersCorrect == true){
        
        ?>
         <div class="alerta_posit">
         <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
              <!-- <symbol id="check-circle-fill" viewBox="0 0 16 16">
                <path fill="green" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
              </symbol> -->
            	</svg>
            <div class="alert alert-success  ajuste_color_alerta  fade show alert-dismissible" role="alert">
              <h4 class="alert-heading">  	
                    <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Success:"  style="width: 20px; height: 20px;" ><use xlink:href="#check-circle-fill"/>
                    </svg>¡ Usuarios actualizados correctamente. !
                </h4>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <p class="mb-0"> </p>
            </div>
            </div>
<?php }
?>

<?php
        if ($NotSelecteUsers == true){
        
        ?>
         <div class="alerta_posit">
         <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
              <!-- <symbol id="check-circle-fill" viewBox="0 0 16 16">
                <path fill="green" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
              </symbol> -->
            	</svg>
            <div class="alert alert-success  ajuste_color_alerta  fade show alert-dismissible" role="alert">
              <h4 class="alert-heading">  	
                    <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Success:"  style="width: 20px; height: 20px;" ><use xlink:href="#check-circle-fill"/>
                    </svg>¡ No se han seleccionado usuarios para aceptar. !
                </h4>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <p class="mb-0"> </p>
            </div>
            </div>
<?php }
?>
<!-- alertas fin -->



  <!-- navergador primario inicio -->

    <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">SpaceMark</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            </ul>
            <form class="d-flex m-2" role="search">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="#">Descubrir</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="#">Lista de compras</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="#">Categorias</a>
                </li>
              </ul>
            </form>

          <a href="salir.php">
            <button type="submit" class="btn btn-outline-danger btn-sm">Cerrar Sesión</button>
          </a>

            <!-- <form action="pgindex.php" method="post">
    <button type="submit" class="btn btn-outline-danger btn-sm" name="cerrar_sesion">Cerrar Sesión</button>
</form> -->
            
      </nav>
<!-- navergador primaria fin -->


<!-- navergador secundario inicio -->
<nav class="shadow mb-4 bg-white navbar navbar-expand-lg bg-body-tertiary rounded-bottom" data-bs-theme="dark">
  <div class="container-fluid">

<!-- buscador ini -->
<div class="collapse navbar-collapse mb-3" id="navbarSupportedContent">
  <div class="col-5">
    <input id="search-input" class="form-control me-2" type="search" placeholder="¿ Qué deseas buscar ?" aria-label="Search">
  </div>  
    <button id="search-button" class="btn btn-outline-danger m-1" type="submit">Buscar</button>
</div>

<script>
document.getElementById('search-button').addEventListener('click', function(event) {
  event.preventDefault();
  var searchTerm = document.getElementById('search-input').value.toLowerCase();
  var products = document.querySelectorAll('.col-md-3');
  
  products.forEach(function(product) {
    var productName = product.querySelector('.product-name').textContent.toLowerCase();
    if (productName.includes(searchTerm)) {
      product.style.display = 'block';
    } else {
      product.style.display = 'none';
    }
  });
});
</script>
<!-- buscador fin -->

            
    
  <div class="m-3 text-center">

    <h4><?php echo "<h4 class='text-white'>Bienvenido, " . $_SESSION['usuario_nombre'] . "</h4>";?> </h4>

    <h5 class=""> <?php $tipo_usuario = $_SESSION['usuario_tipo']; 
    if ($tipo_usuario == 1) {
        echo "<p class='btn btn-outline-success btn-sm' data-bs-toggle='modal' data-bs-target='#modaluse2'>Administrador</p>";
    } elseif ($tipo_usuario == 2) {
        echo "<p class='btn btn-outline-danger btn-sm' data-bs-toggle='modal' data-bs-target='#modaluse3'>Cliente</p>";
    } elseif ($tipo_usuario == 3) {
        echo "<p class='btn btn-outline-primary btn-sm' data-bs-toggle='modal' data-bs-target='#modaluse4'>Proveedor</p>";
    } elseif ($tipo_usuario == 4) {
      echo "<p class='btn btn-outline-warning btn-sm' data-bs-toggle='modal' data-bs-target='#modaluse5'>Empleado</p>";
    } elseif ($tipo_usuario == 5) {
      echo "<p class='btn btn-outline-info btn-sm' data-bs-toggle='modal' data-bs-target='#modaluse6'>Gerente</p>";
     }else {
        echo "<p class='text-white'>Desconocido</p>";
    }?></h5>

  </div>

  <!-- Administrador ini-->
  <div class="modal fade"
         id="modaluse2"
         tabindex="-1"
         aria-hidden="true"
         aria-labelledby="label-modaluse2">
        <!-- Caja de diálogo -->
        <div class="modal-dialog">
            <div class="modal-content">                    
                <!-- Cuerpo -->
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="modal-body">
                            <div class="form-text text-center mb-3"><u>Opciones de Administrador</u></div>
                            <div class="list-group">
                                <a href="veruser.php" class="list-group-item list-group-item-action">Ver Usuarios</a>
                                <a href="aceptaruser.php" class="list-group-item list-group-item-action">Registros por Aceptar</a>
                                <a href="mandarproducto.php" class="list-group-item list-group-item-action">Enviar Producto</a>
                                <a href="confirmarproducto.php" class="list-group-item list-group-item-action">Confirmar Producto</a>
                                <a href="gestionproveedores.php" class="list-group-item list-group-item-action">Gestión de Proveedores</a>
                                <a href="gestiongerentes.php" class="list-group-item list-group-item-action">Gestión de Gerentes</a>
                                <a href="gestionempleados.php" class="list-group-item list-group-item-action">Gestión de Empleados</a>
                                <a href="listaproducto.php" class="list-group-item list-group-item-action">Lista de Productos</a>
                                <a href="carritouser.php" class="list-group-item list-group-item-action">Carrito de compras</a>
                                <a href="compradosuser.php" class="list-group-item list-group-item-action">Productos Comprados</a>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




<!-- confirmar registro ini -->

<div class="modal fade" id="modalregacce" tabindex="4" aria-hidden="true" aria-labelledby="label-modalregacce">
    <!-- caja de dialogo -->
    <div class="modal-dialog">
        <div class="modal-content"> 
            <div class="form-text text-center"><u>Nuevos Usuarios</u></div>
            <!-- cuerpo -->
            <div class="modal-body">
                <form action="" method="post">
                    <div class="modal-body">
                        <table class="table table-dark">
                            <thead>
                                <tr>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Tipo de Usuario</th>
                                    <th scope="col">Verificar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Consulta para obtener usuarios con "no" en la columna "aceptado"
                                $query = "SELECT * FROM usuarios WHERE aceptado = 'no'";
                                $statement = $con->prepare($query);
                                $statement->execute();
                                $usuarios = $statement->fetchAll();

                                foreach ($usuarios as $usuario) {
                                    echo "<tr>";
                                    echo "<td>" . $usuario['usuario'] . "</td>";
                                    echo "<td>" . $usuario['nombre'] . "</td>";
                                    $query_tipo = "SELECT nom_tipos FROM tipos_usuarios WHERE id_tipos = ?";
                                    $statement_tipo = $con->prepare($query_tipo);
                                    $statement_tipo->execute([$usuario['id_tipos']]);
                                    $tipo_usuario = $statement_tipo->fetchColumn();
                                    echo "<td>" . $tipo_usuario . "</td>";
                                    echo "<td>";
                                    echo "<input class='form-check-input' type='checkbox' name='usuarios_aceptar[]' value='" . $usuario['id_usuario'] . "' id='user-" . $usuario['id_usuario'] . "'>";
                                    echo "<label class='text-white opacity-75 form-check-label' for='user-" . $usuario['id_usuario'] . "'>SI</label>";
                                    
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- confirmar registro fin -->


<!-- Administrador fin /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->


<!-- Cliente ini /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade"
         id="modaluse3"
         tabindex="-1"
         aria-hidden="true"
         aria-labelledby="label-modaluse3">
        <!-- Caja de diálogo -->
        <div class="modal-dialog">
            <div class="modal-content">                    
                <!-- Cuerpo -->
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="modal-body">
                            <div class="form-text text-center mb-3"><u>Opciones de Cliente</u></div>
                            <div class="list-group">
                                <a href="opcionesuser.php" class="list-group-item list-group-item-action">Opiniones de perfil</a>
                                <a href="carritouser.php" class="list-group-item list-group-item-action">Carrito de compras</a>
                                <a href="compradosuser.php" class="list-group-item list-group-item-action">Productos Comprados</a>
                                <a href="solihelpsuser.php" class="list-group-item list-group-item-action">Solicitud de ayuda</a>
                                <a href="soliprogreso.php" class="list-group-item list-group-item-action">Progreso de la solicitud</a>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Cliente fin /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->





<!-- Proveedor ini //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<div class="modal fade"
         id="modaluse4"
         tabindex="-1"
         aria-hidden="true"
         aria-labelledby="label-modaluse4">
        <!-- Caja de diálogo -->
        <div class="modal-dialog">
            <div class="modal-content">                    
                <!-- Cuerpo -->
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="modal-body">
                            <div class="form-text text-center mb-3"><u>Opciones de Proveedor</u></div>
                            <div class="list-group">
                                <a href="soliproducto.php" class="list-group-item list-group-item-action">Ver encargos</a>
                                <a href="mandarproducto.php" class="list-group-item list-group-item-action">Enviar Producto</a>
                                <a href="verproductosmandados.php" class="list-group-item list-group-item-action">Productos Mandados</a>
                                <a href="productosmes.php" class="list-group-item list-group-item-action">Productos del mes</a>
                                <a href="carritouser.php" class="list-group-item list-group-item-action">Carrito de compras</a>
                                <a href="compradosuser.php" class="list-group-item list-group-item-action">Productos Comprados</a>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Proveedor fin /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->



<!-- Empleado ini /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade"
         id="modaluse5"
         tabindex="-1"
         aria-hidden="true"
         aria-labelledby="label-modaluse5">
        <!-- Caja de diálogo -->
        <div class="modal-dialog">
            <div class="modal-content">                    
                <!-- Cuerpo -->
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="modal-body">
                            <div class="form-text text-center mb-3"><u>Opciones de Empleado</u></div>
                            <div class="list-group">
                                <a href="gestionproductos.php" class="list-group-item list-group-item-action">Gestionar productos</a>
                                <a href="gestionarventas.php" class="list-group-item list-group-item-action">Gestionar ventas</a>
                                <a href="solitcliente.php" class="list-group-item list-group-item-action">Ver solicitudes de clientes</a>
                                <a href="carritouser.php" class="list-group-item list-group-item-action">Carrito de compras</a>
                                <a href="compradosuser.php" class="list-group-item list-group-item-action">Productos Comprados</a>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Empleado fin /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->





<!-- Gerente ini /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
<div class="modal fade"
         id="modaluse6"
         tabindex="-1"
         aria-hidden="true"
         aria-labelledby="label-modaluse6">
        <!-- Caja de diálogo -->
        <div class="modal-dialog">
            <div class="modal-content">                    
                <!-- Cabecera del modal -->
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Cuerpo -->
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="modal-body">
                            <div class="form-text text-center mb-3"><u>Opciones de Gerente</u></div>
                            <div class="list-group">
                                <a href="gestioninventario.php" class="list-group-item list-group-item-action">Gestion de Productos</a>
                                <a href="gestionpromo.php" class="list-group-item list-group-item-action">Gestion de Promociones</a>
                                <a href="confirmarproducto.php" class="list-group-item list-group-item-action">Confirmar Producto</a>
                                <a href="solicitudproductos.php" class="list-group-item list-group-item-action">Solicitar Producto</a>
                                <a href="verproductossolicitados.php" class="list-group-item list-group-item-action">Ver progreso solicitud</a>
                                <a href="gestionproveedores.php" class="list-group-item list-group-item-action">Gestion de Proveedores</a>
                                <a href="gestionempleados.php" class="list-group-item list-group-item-action">Gestion de Empleados</a>
                                <a href="listaproducto.php" class="list-group-item list-group-item-action">Lista de productos</a>
                                <a href="carritouser.php" class="list-group-item list-group-item-action">Carrito de compras</a>
                                <a href="compradosuser.php" class="list-group-item list-group-item-action">Productos Comprados</a>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Gerente fin /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->

  </div>
<script src="js/bootstrap.js"></script>
</nav>
<!-- navergador secundario fin -->


 <!-- Mostrar una alerta si el usuario intenta retroceder sin cerrar sesión -->

<!-- <script>
        window.onbeforeunload = function() {
            return "No has cerrado sesión. ¿Estás seguro de que quieres abandonar esta página?";
        };
</script> -->

<style>
        .table-container {
            overflow: hidden;
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
        }
    </style>

<h5 class="container"> <?php $tipo_usuario = $_SESSION['usuario_tipo']; 
    if ($tipo_usuario == 1) {?>

<div class="container mt-5">
        <h2 class="mb-4">Lista de Usuarios por Aceptar</h2>
        <div class="table-container">
            <table class="table table-striped table-bordered table-hover table-light m-0">
                <thead class="thead-light">
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
                        echo "<td>" . $usuario['usuario'] . "</td>";
                        echo "<td>" . $usuario['nombre'] . "</td>";
                        // Consultar el tipo de usuario
                        $query_tipo = "SELECT nom_tipos FROM tipos_usuarios WHERE id_tipos = ?";
                        $statement_tipo = $con->prepare($query_tipo);
                        $statement_tipo->execute([$usuario['id_tipos']]);
                        $tipo_usuario = $statement_tipo->fetchColumn();
                        echo "<td>" . $tipo_usuario . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>


  </h5>
  <?php
  }?>


<!-- carousel inicio-->
<div class="container">
        <div class="row mb-5">
            <div class="col">
            
                <div class="carousel slide carousel-fade" id="mi-carousel" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="img-fluid rounded" src="IMG/descuento1.png">
                        </div>

                        <div class="carousel-item">
                            <img class="img-fluid rounded" src="IMG/descuento2.png">
                        </div>
                           
                        <div class="carousel-item">
                            <img class="img-fluid rounded" src="IMG/descuento3.png">
                        </div>

                        <div class="carousel-item">
                            <img class="img-fluid rounded" src="IMG/descuento4.png">
                        </div>

                        <div class="carousel-item">
                            <img class="img-fluid rounded" src="IMG/descuento5.png">
                        </div>

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
                        <button type="button" class="active" data-bs-target="#mi-carousel" data-bs-slide-to="0" aria-label="Slide 1">
                        </button>

                        <button type="button" class="active" data-bs-target="#mi-carousel" data-bs-slide-to="1" aria-label="Slide 2">
                        </button>

                        <button type="button" class="active" data-bs-target="#mi-carousel" data-bs-slide-to="2" aria-label="Slide 3">
                        </button>

                        <button type="button" class="active" data-bs-target="#mi-carousel" data-bs-slide-to="3" aria-label="Slide 4">
                        </button>

                        <button type="button" class="active" data-bs-target="#mi-carousel" data-bs-slide-to="4" aria-label="Slide 5">
                        </button>
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
</style>
<div class="container mt-4">
    <?php foreach ($productosPorCategoria as $categoria => $productos): ?>
        <h2><?php echo htmlspecialchars($categoria); ?></h2>
        <div class="row">
            <?php foreach ($productos as $producto): ?>
                <div class="col-md-3 mb-4" id="product-<?php echo $producto['IDP']; ?>">
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
                                        <h5 class="modal-title" id="label-modal-<?php echo $producto['IDP']; ?>">Compra del producto <?php echo htmlspecialchars($producto['Nombre']); ?></h5>
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
                                                <form id="compra-form-<?php echo $producto['IDP']; ?>" action="procesar_compra.php" method="post">
                                                    <div class="mb-3 d-flex align-items-center">
                                                        <label for="cantidad-<?php echo $producto['IDP']; ?>" class="form-label me-2">Cantidad:</label>
                                                        <input type="number" class="form-control" id="cantidad-<?php echo $producto['IDP']; ?>" name="cantidad" min="1" max="<?php echo htmlspecialchars($producto['Cantidad']); ?>" value="1" required onchange="calcularTotal(<?php echo $producto['IDP']; ?>)">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="metodo_pago-<?php echo $producto['IDP']; ?>" class="form-label">Método de Pago:</label>
                                                        <select class="form-control" id="metodo_pago-<?php echo $producto['IDP']; ?>" name="metodo_pago" required>
                                                            <option value="Efectivo">Efectivo</option>
                                                            <option value="Tarjeta">Tarjeta de Crédito/Débito</option>
                                                            <option value="Transferencia">Transferencia Bancaria</option>
                                                        </select>
                                                    </div>
                                                    <input type="hidden" name="id_producto" value="<?php echo $producto['IDP']; ?>">
                                                    <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($_SESSION['usuario_id']); ?>">
                                                    <p>Precio: $<span id="precio-<?php echo $producto['IDP']; ?>"><?php echo htmlspecialchars($producto['Precio']); ?></span></p>
                                                    <p>Total: $<span id="total-<?php echo $producto['IDP']; ?>">0.00</span></p>
                                                    <input type="hidden" name="accion" value="comprar">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="document.getElementById('compra-form-<?php echo $producto['IDP']; ?>').submit();">Comprar</button>
                                        <button type="button" class="btn btn-secondary" onclick="agregarAlCarrito(<?php echo $producto['IDP']; ?>);">Añadir al Carrito</button>
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

<script>
    function agregarAlCarrito(idProducto) {
    var form = document.getElementById('compra-form-' + idProducto);
    form.accion.value = 'carrito';
    form.submit();
}
</script>

<!-- aumenta el total del producto al cambiar la cantidad fin -->

<!-- fin de Super mercado de SpaceMark -->

<div class="">
  <h3>Aprovecha estos descuentos para el hogar</h3>
</div>

<div class="card-group">
  <div class="card mb-5">
    <img src="./IMG/anun1.png" class="rounded" alt="...">
  </div>

  <div class="card mb-5">
    <img src="./IMG/aunu2.png" class="rounded" alt="...">
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
      <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary"><u>Condiciones de uso</u></a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary"><u>Declaración de privacidad y de cookies</u></a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary"><u>Consentimiento de cookie</u></a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary"><u>Cómo funciona el sitio</u></a></li>
    </ul>
    <ul class="nav justify-content-center list-unstyled d-flex">
      <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#twitter"/></svg></a></li>
      <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#instagram"/></svg></a></li>
      <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#facebook"/></svg></a></li>
    </ul>
    <p class="text-center text-body-secondary mt-4">&copy; 2024 SpaceMark, Inc</p>
    <p class="text-center text-body-secondary mt-4"><small>2023 SpaceMark. Todos los derechos reservados. Todas las marcas registradas pertenecen a sus respectivos dueños en EE. UU. y otros países. Todos los precios incluyen IVA (donde sea aplicable).
      Política de Privacidad | Información legal | Acuerdo de Suscriptor a SpaceMark | Reembolsos | Cookies</small></p>

  </footer>
</div>

<script src="JS/bootstrap.bundle.min.js"></script>

</body>
</html>