<?php
include_once 'conexion.php';

session_start();

// variables de alertas ini
$acept = false;

$UsAndConFals = false;

$completCampos = false;

$UsOrNameIn = false;

$Userrep = false;

$Admincon = false;


// variables de alertas fin



// registro ini

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
                $Userrep = true;

            } else {
                // Si el usuario no existe, realizar la inserción en la base de datos
                $aceptado = "no"; // Por defecto, se establece en "no"
                $query_insert = "INSERT INTO usuarios (usuario, nombre, correo_electronico, telefono, contraseña_usuario, id_tipos, aceptado) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $statement_insert = $con->prepare($query_insert);
                $statement_insert->execute([$usuario, $nombre, $correo, $telefono, $contrasena_cifrada, $tipo_usuario, $aceptado]);
                
                header("Location: pgindex.php");
                exit;
            }
        } else {
            // echo'<div class="alert alert-primary" role="alert">
            //         Por favor, complete todos los campos
            //     </div>';        
        }
    }
}
// registro fin




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
                    $acept = true;                 
                }
            } else {
                $UsAndConFals = true;           
            }
        } else {
            $completCampos = true;        
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
                $Admincon = true;            
            } else {
                // Si el usuario no es administrador, actualizar la contraseña en la base de datos para el usuario correspondiente
                $query_update = "UPDATE usuarios SET contraseña_usuario = ? WHERE usuario = ?";
                $statement_update = $con->prepare($query_update);
                $statement_update->execute([$contrasena_cifrada3, $usuario]);

                // Redirigir a alguna página después de guardar la contraseña
                // Esto es opcional, puedes redirigir a donde desees
                header("Location: pgindex.php");
                exit;
            }
        } else {
            $UsOrNameIn = true;        
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
</head>
<body>
  <!-- navergador primario inicio -->

  <!-- alertas de sesion ini  -->
  <?php
        if ($acept == true){
        
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
                    </svg>¡ El usuario no ha sido aceptado !
                </h4>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <p class="mb-0"> </p>
            </div>
            </div>
        <?php }
        ?>



<?php
        if ($UsAndConFals == true){
        
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
                    </svg>¡ El usuario o contraseña son incorrectos. !
                </h4>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <p class="mb-0"> </p>
            </div>
            </div>
<?php }
?>



<?php
        if ($completCampos == true){
        
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
                    </svg>¡ Por favor, complete todos los campos. !
                </h4>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <p class="mb-0"> </p>
            </div>
            </div>
<?php }
?>


<?php
        if ($UsOrNameIn == true){
        
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
                    </svg>¡ Usuario o nombre incorrectos. !
                </h4>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <p class="mb-0"> </p>
            </div>
            </div>
<?php }
?>

<?php
        if ($Userrep == true){
        
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
                    </svg>¡ El usuario ya existe. !
                </h4>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <p class="mb-0"> </p>
            </div>
            </div>
<?php }
?>

<?php
        if ($Admincon == true){
        
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
                    </svg>¡ El usuario que estás intentando actualizar es un administrador y no se puede cambiar la contraseña. !
                </h4>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <p class="mb-0"> </p>
            </div>
            </div>
<?php }
?>
  <!-- alertas de sesion fin  -->

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


            <!-- registro -->
            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalreg0">Registro</button>
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
                                <div class="form-text text-center"><u>Registro</u></div>
                                <div class="mt-3">
                                    <div class="row mb-3">
                                        <div class="col-12"> 
                                            <input type="text" class="form-control" name="nombre" placeholder="Nombre" required>
                                        </div>
                                        <div class="form-group form-text">
                                            <div id="ayuda-correo" class="form-text">
                                                Correo Personal
                                                <input type="text" class="form-control" name="correo" aria-describedby="ayuda-correo" placeholder="Correo" required>
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
                                        <label class="text-white opacity-75 form-check-label" for="terminos">
                                            Acepta los términos y condiciones
                                        </label>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-outline-danger btn-sm" name="registrar">Registrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
          </div>
        

          </div>
        </div>
      </nav>

      <!-- fin del registro  -->
<!-- navergador primaria fin -->


<!-- navergador secundario inicio -->
<nav class="shadow mb-4 bg-white navbar navbar-expand-lg bg-body-tertiary rounded-bottom" data-bs-theme="dark">
  <div class="container-fluid">
    <div class="collapse navbar-collapse mb-3" id="navbarSupportedContent">
      <div class="col-5">
        <input class="form-control me-2" type="search" placeholder="¿ Que deseas comprar ?" aria-label="Search">
      </div>  
      <button class="btn btn-outline-danger m-1" type="submit">Buscar</button>
    </div>


    <!-- <h4 class="text-white m-3">Estas Adentro </h4> -->
    
    
    <!-- inico de sesion -->
      <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalini1">Iniciar Sesion</button>
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
                        <button type="submit" class="col-12 btn btn-outline-success btn-sm" name="guardar_contraseña">Guardar Contraseña</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--recupera con fin-->




  </div>
<script src="js/bootstrap.js"></script>

</nav>

<!-- navergador secundario fin -->















<!-- Mostrar una alerta si el usuario intenta retroceder sin iniciar sesión -->

<!-- <script>
        window.onbeforeunload = function() {
            return "Por favor, inicia sesión para acceder a esta página.";
        };
</script> -->






















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
<div class="">
  <h3>Super mercado SpaceMark</h3>
</div>



<!-- inicio -->
<div class="row mb-5">
<?php
 $sql_pro = "SELECT * FROM productos";
 $pass2 = $con->prepare($sql_pro);
 $pass2->execute();
 $rest_pro = $pass2->fetchAll(PDO::FETCH_ASSOC);
foreach ($rest_pro as $prod){
  $call=$prod['id_prod'];
echo
"
  <div class='table-responsive col-3'>
      <div class='btn card' style='width: 15rem;'>
          <img class='card-img-top' data-bs-toggle='modal' data-bs-target='#modax$call' src='data:image/jpg;base64,".base64_encode($prod['img_prod'])."' style='width: 14rem;'>
          <div class='card-body'>
          <p class='h5'>".$prod['nombre_prod']."</p>
          <figcaption class='h5'></figcaption>
          <p class='h5'>$".$prod['precio_prod']."</p>
          <button type='button' class='btn btn-outline-danger btn-sm' data-bs-dismiss='modal'>Agregar al carrito</button>
          </div>               
            <div class='modal fade'
      id='modax$call'
      tabindex='$call'
      aria-hidden='true'
      aria-labelledby='label-modax$call'>
      <!-- caja de dialogo -->
      <div class='modal-dialog'>
          <div class='modal-content'>
              <!-- encabezado -->
              <div class='modal-header'>
                  <h4 class='modal-tittle'>Descripción</h4>
              </div>
              <!-- cuerpo -->
              <div class='modal-body'>
                  <h5>".$prod['des_det_prod']."</h5>
                  <img style='width: 15rem;' src='data:image/jpg;base64,".base64_encode($prod['img_prod'])."' alt=''>
              </div>

              <!-- pie de pagina -->
              <div class='modal-footer'>
                  <button class='btn-close' data-bs-dismiss='modal' aria-label='cerrar'></button>
              </div>

          </div>
      </div>
      </div>
      </div>
  </div>
  ";
  }
  ?></div>
  <!-- fin -->


<div class="row mb-5">
  <div class=" col-3">
      <div class="btn card" style="width: 15rem;">
          <img class="card-img-top" data-bs-toggle="modal" data-bs-target="#modax1" src="./IMG/1-mercado.png" style="width: 14rem;">
          <div class="card-body">
          <p class="h5">Garbanzos *500 GR</p>
          <figcaption class="h5">+ Aceite Diana *2.000 ML</figcaption>
          <p class="h5">$</p>
          <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>
          </div>                
            <div class="modal fade"
      id="modax1"
      tabindex="1"
      aria-hidden="true"
      aria-labelledby="label-modax1">
      <!-- caja de dialogo -->
      <div class="modal-dialog">
          <div class="modal-content">
              <!-- encabezado -->
              <div class="modal-header">
                  <h4 class="modal-tittle">Descripción</h4>
              </div>
              <!-- cuerpo -->
              <div class="modal-body">
                  <h5>Descubre la excelencia en cada bocado con los Garbanzos Diana, una joya culinaria que eleva tus platillos a nuevas alturas. Estos garbanzos, parte de la distinguida línea de alimentos y bebidas de origen vegetal de Diana, se destacan por su calidad premium y su aporte nutricional excepcional.</h5>
                  <img style="width: 15rem;" src="IMG/1-mercado.png" alt="">
              </div>

              <!-- pie de pagina -->
              <div class="modal-footer">
                  <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
              </div>

          </div>
      </div>
      </div>
      </div>
  </div>
  
  <div class="table-responsive col-3">
      <div class="btn card" style="width: 15rem;">                
          <img class="card-img-top rounded-start" data-bs-toggle="modal" data-bs-target="#modax2" src="./IMG/1-mercados.png" style="height: 13.4rem;">
          <div class="card-body">
            <p class="h5">Arveja con Zanahoria Zenu</p>
            <figcaption class="h5">*580 GR</figcaption>
            <p class="h5">$</p>
            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>
          </div>
      <div class="modal fade"
      id="modax2"
      tabindex="1"
      aria-hidden="true"
      aria-labelledby="label-modax2">
      <!-- caja de dialogo -->
      <div class="modal-dialog">
          <div class="modal-content">
              <!-- encabezado -->
              <div class="modal-header">
                <h4 class="modal-tittle">Descripción</h4>
            </div>
            <!-- cuerpo -->
            <div class="modal-body">
                <h5>Avena Alpina es una bebida con leche y avena, ultra alta temperatura UAT (UHT), que por las características de su empaque no necesita refrigeración.</h5>
                <img style="width: 15rem;" src="IMG/1-mercados.png" alt="">
            </div>

              <!-- pie de pagina -->
              <div class="modal-footer">
                  <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
              </div>

          </div>
      </div>
      </div>
      </div>
  </div>

  <div class="table-responsive col-3">
      <div class="btn card" style="width: 15rem;">
      <img class="card-img-top" data-bs-toggle="modal" data-bs-target="#modal3" src="./IMG/la-bon yurt.png">
      <div class="card-body">
        <p class="h5">Bon Yurt Alpina</p>
        <br>
        <figcaption class="h5">*170 GR</figcaption>
        <p class="h5">$</p>
        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>        </div>
      <div class="modal fade"
      id="modal3"
      tabindex="1"
      aria-hidden="true"
      aria-labelledby="label-modal3">
      <!-- caja de dialogo -->
      <div class="modal-dialog">
          <div class="modal-content">
              <!-- encabezado -->
              <div class="modal-header">
                <h4 class="modal-tittle">Descripción</h4>
            </div>
            <!-- cuerpo -->
            <div class="modal-body">
                <h5>No solo es su sabor, es una experiencia de consumo única: abrir la base láctea y mezclarla con el topping, BON YURT DOS PALABRAS QUE TE HACEN FELIZ.</h5>
                <img style="width: 15rem;" src="IMG/la-bon yurt.png" alt="">
            </div>

              <!-- pie de pagina -->
              <div class="modal-footer">
                  <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>

              </div>

          </div>
      </div>
      </div>
      </div>
  </div>

  <div class="table-responsive col-3">
    <div class="btn card" style="width: 15rem;">                
        <img class="card-img-top rounded-start" data-bs-toggle="modal" data-bs-target="#modal4" src="./IMG/la-KUMIS-ALPINA_F.png">
        <div class="card-body">
          <p class="h5">Kumis En Bolsa Alpina</p>
          <figcaption class="h5">*1.000 GR
          </figcaption>
          <p class="h5">$</p>
          <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>          </div>
    <div class="modal fade"
    id="modal4"
    tabindex="1"
    aria-hidden="true"
    aria-labelledby="label-modal4">
    <!-- caja de dialogo -->
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- encabezado -->
            <div class="modal-header">
              <h4 class="modal-tittle">Descripción</h4>
          </div>
          <!-- cuerpo -->
          <div class="modal-body">
              <h5>Kumis Alpina es esa tradición, un producto incondicional que ha estado presente de generación en generación en todas las familias colombianas. A todos nos encanta y estamos seguros de que nos seguirá acompañando por muchas generaciones más.</h5>
              <img style="width: 15rem;" src="IMG/la-KUMIS-ALPINA_F.png" alt="">
          </div>

            <!-- pie de pagina -->
            <div class="modal-footer">
                <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
            </div>

        </div>
    </div>
    </div>
    </div>

  </div>
  
<div class="row mt-5">
  <div class="table-responsive col-3">
      <div class="btn card" style="width: 15rem;">
          <img class="card-img-top" data-bs-toggle="modal" data-bs-target="#modal5" src="./IMG/la-QUESO-FINESSE_F.png">
          <div class="card-body">
            <p class="h5">Queso Finesses Alpina</p>
            <figcaption class="h5">*450 GR
            </figcaption>
            <p class="h5">$</p>
            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>           </div>                
            <div class="modal fade"
      id="modal5"
      tabindex="1"
      aria-hidden="true"
      aria-labelledby="label-modal5">
      <!-- caja de dialogo -->
      <div class="modal-dialog">
          <div class="modal-content">
              <!-- encabezado -->
              <div class="modal-header">
                <h4 class="modal-tittle">Descripción</h4>
            </div>
            <!-- cuerpo -->
            <div class="modal-body">
                <h5>Finesse es una marca que se enfoca en el cuidado de la figura, a lo largo de los años se ha convertido en el aliado perfecto para las mujeres. Finesse se aleja de los castigos, las dietas extremas o sacrificios.</h5>
                <img style="width: 15rem;" src="IMG/la-QUESO-FINESSE_F.png" alt="">
            </div>

              <!-- pie de pagina -->
              <div class="modal-footer">
                  <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
              </div>

          </div>
      </div>
      </div>
      </div>
  </div>
  

  <div class="table-responsive col-3">
    <div class="btn card" style="width: 15rem;">
        <img class="card-img-top" data-bs-toggle="modal" data-bs-target="#modal6" src="./IMG/la-yogurt yogo yogo.png">
        <div class="card-body">
            <p class="h5">Yogurt Yogo x3 Alpina</p>
            <figcaption class="h5">*1.000 GR
            </figcaption>
            <p class="h5">$</p>
            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>         </div>                
          <div class="modal fade"
    id="modal6"
    tabindex="1"
    aria-hidden="true"
    aria-labelledby="label-modal6">
    <!-- caja de dialogo -->
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- encabezado -->
            <div class="modal-header">
              <h4 class="modal-tittle">Descripción</h4>
          </div>
          <!-- cuerpo -->
          <div class="modal-body">
              <h5>Sumérgete en el refrescante sabor a fresa con este envase de Yogo Maxilitro de 1.1 litros. Una opción práctica para tener siempre disponible tu Yogurt favorito.</h5>
              <img style="width: 15rem;" src="IMG/la-yogurt yogo yogo.png" alt="">
          </div>

            <!-- pie de pagina -->
            <div class="modal-footer">
                <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
            </div>

        </div>
    </div>
    </div>
    </div>
</div>

<div class="table-responsive col-3">
  <div class="btn card" style="width: 15rem;">
      <img class="card-img-top" data-bs-toggle="modal" data-bs-target="#modal7" src="./IMG/la-Yogurt_griego_1000.png">
      <div class="card-body">
        <p class="h5">Yogurt Griego Dejamu Natural</p>
        <figcaption class="h5">*450 GR
        </figcaption>
        <p class="h5">$</p>
        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>       </div>                
        <div class="modal fade"
  id="modal7"
  tabindex="1"
  aria-hidden="true"
  aria-labelledby="label-modal7">
  <!-- caja de dialogo -->
  <div class="modal-dialog">
      <div class="modal-content">
          <!-- encabezado -->
          <div class="modal-header">
            <h4 class="modal-tittle">Descripción</h4>
        </div>
        <!-- cuerpo -->
        <div class="modal-body">
            <h5>El yogurt griego natural contiene mas proteínas que el yogurt natural, pues en su proceso de realización se retira el suero de leche y la lactosa, por ello está entre los alimentos saludables más altos en proteínas.</h5>
            <img style="width: 15rem;" src="IMG/la-Yogurt_griego_1000.png" alt="">
        </div>

          <!-- pie de pagina -->
          <div class="modal-footer">
              <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
          </div>

      </div>
  </div>
  </div>
  </div>
</div>

<div class="table-responsive col-3">
  <div class="btn card" style="width: 15rem;">
      <img class="card-img-top" data-bs-toggle="modal" data-bs-target="#modal8" src="./IMG/la-YOX-MULTIFRUTAS_F.png">
      <div class="card-body">
        <p class="h5">Yox Multifruta Bellota Alpina</p>
        <figcaption class="h5">*100 GR
        </figcaption>
        <p class="h5">$</p>
        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>       </div>                
        <div class="modal fade"
  id="modal8"
  tabindex="1"
  aria-hidden="true"
  aria-labelledby="label-modal8">
  <!-- caja de dialogo -->
  <div class="modal-dialog">
      <div class="modal-content">
          <!-- encabezado -->
          <div class="modal-header">
            <h4 class="modal-tittle">Descripción</h4>
        </div>
        <!-- cuerpo -->
        <div class="modal-body">
            <h5>Yox con Defensis es una un alimento lácteo con nuestra fórmula especial: Vitamina C y Zinc que contribuyen a reforzar el sistema de defensas consumiéndolo diariamente. Además contiene miles de probióticos que contribuyen al bienestar del cuerpo.</h5>
            <img style="width: 15rem;" src="IMG/la-YOX-MULTIFRUTAS_F.png" alt="">
        </div>

          <!-- pie de pagina -->
          <div class="modal-footer">
              <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
          </div>

      </div>
  </div>
  </div>
  </div>
</div>
</div>
</div>
<!-- fin de Super mercado de SpaceMark -->

<!-- inicio de productos lacteos -->
<div class="">
  <h3>Productos Lacteos</h3>
</div>

<div class="row mb-5">
<!-- inicio -->
<?php
 $sql_pro = "SELECT * FROM productos WHERE tipologia_prod like '%lacteo%'";
 $pass2 = $con->prepare($sql_pro);
 $pass2->execute();
 $rest_pro = $pass2->fetchAll(PDO::FETCH_ASSOC);
foreach ($rest_pro as $prod){
  $call=$prod['id_prod'];
echo
"
  <div class='table-responsive col-3'>
      <div class='btn card' style='width: 15rem;'>
          <img class='card-img-top' data-bs-toggle='modal' data-bs-target='#modax$call' src='data:image/jpg;base64,".base64_encode($prod['img_prod'])."' style='width: 14rem;'>
          <div class='card-body'>
          <p class='h5'>".$prod['nombre_prod']."</p>
          <figcaption class='h5'></figcaption>
          <p class='h5'>$".$prod['precio_prod']."</p>
          <button type='button' class='btn btn-outline-danger btn-sm' data-bs-dismiss='modal'>Agregar al carrito</button>
          </div>               
            <div class='modal fade'
      id='modax$call'
      tabindex='$call'
      aria-hidden='true'
      aria-labelledby='label-modax$call'>
      <!-- caja de dialogo -->
      <div class='modal-dialog'>
          <div class='modal-content'>
              <!-- encabezado -->
              <div class='modal-header'>
                  <h4 class='modal-tittle'>Descripción</h4>
              </div>
              <!-- cuerpo -->
              <div class='modal-body'>
                  <h5>".$prod['des_det_prod']."</h5>
                  <img style='width: 15rem;' src='data:image/jpg;base64,".base64_encode($prod['img_prod'])."' alt=''>
              </div>

              <!-- pie de pagina -->
              <div class='modal-footer'>
                  <button class='btn-close' data-bs-dismiss='modal' aria-label='cerrar'></button>
              </div>

          </div>
      </div>
      </div>
      </div>
  </div>
  ";
  }
  ?></div>
  <!-- fin -->


<div class="row mb-5">
  <div class="table-responsive col-3">
      <div class="btn card" style="width: 15rem;">
          <img class="card-img-top" data-bs-toggle="modal" data-bs-target="#modal1" src="./IMG/la-ALPINITO-FRESA_F.png">
          <div class="card-body">
          <p class="h5">Alpinito De Fresa Alpina</p>
          <figcaption class="h5">*45 GR</figcaption>
          <p class="h5">$</p>
          <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>
          </div>                
            <div class="modal fade"
      id="modal1"
      tabindex="1"
      aria-hidden="true"
      aria-labelledby="label-modal1">
      <!-- caja de dialogo -->
      <div class="modal-dialog">
          <div class="modal-content">
              <!-- encabezado -->
              <div class="modal-header">
                  <h4 class="modal-tittle">Descripción</h4>
              </div>
              <!-- cuerpo -->
              <div class="modal-body">
                  <h5>Alpinito es un queso fresco, blando, semimagro, con dulce de frutas, que aporta hierro, zinc, ácido fólico, vitamina D y vitamina B12.</h5>
                  <img style="width: 15rem;" src="IMG/la-ALPINITO-FRESA_F.png" alt="">
              </div>

              <!-- pie de pagina -->
              <div class="modal-footer">
                  <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
              </div>

          </div>
      </div>
      </div>
      </div>
  </div>
  
  <div class="table-responsive col-3">
      <div class="btn card" style="width: 15rem;">                
          <img class="card-img-top rounded-start" data-bs-toggle="modal" data-bs-target="#modal2" src="./IMG/la-avena-canela.png">
          <div class="card-body">
            <p class="h5">Avena Canela Alpina</p>
            <figcaption class="h5">*250 GR</figcaption>
            <p class="h5">$</p>
            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>            </div>
      <div class="modal fade"
      id="modal2"
      tabindex="1"
      aria-hidden="true"
      aria-labelledby="label-modal2">
      <!-- caja de dialogo -->
      <div class="modal-dialog">
          <div class="modal-content">
              <!-- encabezado -->
              <div class="modal-header">
                <h4 class="modal-tittle">Descripción</h4>
            </div>
            <!-- cuerpo -->
            <div class="modal-body">
                <h5>Avena Alpina es una bebida con leche y avena, ultra alta temperatura UAT (UHT), que por las características de su empaque no necesita refrigeración.</h5>
                <img style="width: 15rem;" src="IMG/la-avena-canela.png" alt="">
            </div>

              <!-- pie de pagina -->
              <div class="modal-footer">
                  <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
              </div>

          </div>
      </div>
      </div>
      </div>
  </div>

  <div class="table-responsive col-3">
      <div class="btn card" style="width: 15rem;">
      <img class="card-img-top" data-bs-toggle="modal" data-bs-target="#modal3" src="./IMG/la-bon yurt.png">
      <div class="card-body">
        <p class="h5">Bon Yurt Alpina</p>
        <br>
        <figcaption class="h5">*170 GR</figcaption>
        <p class="h5">$</p>
        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>        </div>
      <div class="modal fade"
      id="modal3"
      tabindex="1"
      aria-hidden="true"
      aria-labelledby="label-modal3">
      <!-- caja de dialogo -->
      <div class="modal-dialog">
          <div class="modal-content">
              <!-- encabezado -->
              <div class="modal-header">
                <h4 class="modal-tittle">Descripción</h4>
            </div>
            <!-- cuerpo -->
            <div class="modal-body">
                <h5>No solo es su sabor, es una experiencia de consumo única: abrir la base láctea y mezclarla con el topping, BON YURT DOS PALABRAS QUE TE HACEN FELIZ.</h5>
                <img style="width: 15rem;" src="IMG/la-bon yurt.png" alt="">
            </div>

              <!-- pie de pagina -->
              <div class="modal-footer">
                  <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>

              </div>

          </div>
      </div>
      </div>
      </div>
  </div>

  <div class="table-responsive col-3">
    <div class="btn card" style="width: 15rem;">                
        <img class="card-img-top rounded-start" data-bs-toggle="modal" data-bs-target="#modal4" src="./IMG/la-KUMIS-ALPINA_F.png">
        <div class="card-body">
          <p class="h5">Kumis En Bolsa Alpina</p>
          <figcaption class="h5">*1.000 GR
          </figcaption>
          <p class="h5">$</p>
          <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>          </div>
    <div class="modal fade"
    id="modal4"
    tabindex="1"
    aria-hidden="true"
    aria-labelledby="label-modal4">
    <!-- caja de dialogo -->
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- encabezado -->
            <div class="modal-header">
              <h4 class="modal-tittle">Descripción</h4>
          </div>
          <!-- cuerpo -->
          <div class="modal-body">
              <h5>Kumis Alpina es esa tradición, un producto incondicional que ha estado presente de generación en generación en todas las familias colombianas. A todos nos encanta y estamos seguros de que nos seguirá acompañando por muchas generaciones más.</h5>
              <img style="width: 15rem;" src="IMG/la-KUMIS-ALPINA_F.png" alt="">
          </div>

            <!-- pie de pagina -->
            <div class="modal-footer">
                <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
            </div>

        </div>
    </div>
    </div>
    </div>

  </div>
  
<div class="row mt-5">
  <div class="table-responsive col-3">
      <div class="btn card" style="width: 15rem;">
          <img class="card-img-top" data-bs-toggle="modal" data-bs-target="#modal5" src="./IMG/la-QUESO-FINESSE_F.png">
          <div class="card-body">
            <p class="h5">Queso Finesses Alpina</p>
            <figcaption class="h5">*450 GR
            </figcaption>
            <p class="h5">$</p>
            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>           </div>                
            <div class="modal fade"
      id="modal5"
      tabindex="1"
      aria-hidden="true"
      aria-labelledby="label-modal5">
      <!-- caja de dialogo -->
      <div class="modal-dialog">
          <div class="modal-content">
              <!-- encabezado -->
              <div class="modal-header">
                <h4 class="modal-tittle">Descripción</h4>
            </div>
            <!-- cuerpo -->
            <div class="modal-body">
                <h5>Finesse es una marca que se enfoca en el cuidado de la figura, a lo largo de los años se ha convertido en el aliado perfecto para las mujeres. Finesse se aleja de los castigos, las dietas extremas o sacrificios.</h5>
                <img style="width: 15rem;" src="IMG/la-QUESO-FINESSE_F.png" alt="">
            </div>

              <!-- pie de pagina -->
              <div class="modal-footer">
                  <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
              </div>

          </div>
      </div>
      </div>
      </div>
  </div>
  

  <div class="table-responsive col-3">
    <div class="btn card" style="width: 15rem;">
        <img class="card-img-top" data-bs-toggle="modal" data-bs-target="#modal6" src="./IMG/la-yogurt yogo yogo.png">
        <div class="card-body">
            <p class="h5">Yogurt Yogo x3 Alpina</p>
            <figcaption class="h5">*1.000 GR
            </figcaption>
            <p class="h5">$</p>
            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>         </div>                
          <div class="modal fade"
    id="modal6"
    tabindex="1"
    aria-hidden="true"
    aria-labelledby="label-modal6">
    <!-- caja de dialogo -->
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- encabezado -->
            <div class="modal-header">
              <h4 class="modal-tittle">Descripción</h4>
          </div>
          <!-- cuerpo -->
          <div class="modal-body">
              <h5>Sumérgete en el refrescante sabor a fresa con este envase de Yogo Maxilitro de 1.1 litros. Una opción práctica para tener siempre disponible tu Yogurt favorito.</h5>
              <img style="width: 15rem;" src="IMG/la-yogurt yogo yogo.png" alt="">
          </div>

            <!-- pie de pagina -->
            <div class="modal-footer">
                <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
            </div>

        </div>
    </div>
    </div>
    </div>
</div>

<div class="table-responsive col-3">
  <div class="btn card" style="width: 15rem;">
      <img class="card-img-top" data-bs-toggle="modal" data-bs-target="#modal7" src="./IMG/la-Yogurt_griego_1000.png">
      <div class="card-body">
        <p class="h5">Yogurt Griego Dejamu Natural</p>
        <figcaption class="h5">*450 GR
        </figcaption>
        <p class="h5">$</p>
        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>       </div>                
        <div class="modal fade"
  id="modal7"
  tabindex="1"
  aria-hidden="true"
  aria-labelledby="label-modal7">
  <!-- caja de dialogo -->
  <div class="modal-dialog">
      <div class="modal-content">
          <!-- encabezado -->
          <div class="modal-header">
            <h4 class="modal-tittle">Descripción</h4>
        </div>
        <!-- cuerpo -->
        <div class="modal-body">
            <h5>El yogurt griego natural contiene mas proteínas que el yogurt natural, pues en su proceso de realización se retira el suero de leche y la lactosa, por ello está entre los alimentos saludables más altos en proteínas.</h5>
            <img style="width: 15rem;" src="IMG/la-Yogurt_griego_1000.png" alt="">
        </div>

          <!-- pie de pagina -->
          <div class="modal-footer">
              <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
          </div>

      </div>
  </div>
  </div>
  </div>
</div>

<div class="table-responsive col-3">
  <div class="btn card" style="width: 15rem;">
      <img class="card-img-top" data-bs-toggle="modal" data-bs-target="#modal8" src="./IMG/la-YOX-MULTIFRUTAS_F.png">
      <div class="card-body">
        <p class="h5">Yox Multifruta Bellota Alpina</p>
        <figcaption class="h5">*100 GR
        </figcaption>
        <p class="h5">$</p>
        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Agregar al carrito</button>       </div>                
        <div class="modal fade"
  id="modal8"
  tabindex="1"
  aria-hidden="true"
  aria-labelledby="label-modal8">
  <!-- caja de dialogo -->
  <div class="modal-dialog">
      <div class="modal-content">
          <!-- encabezado -->
          <div class="modal-header">
            <h4 class="modal-tittle">Descripción</h4>
        </div>
        <!-- cuerpo -->
        <div class="modal-body">
            <h5>Yox con Defensis es una un alimento lácteo con nuestra fórmula especial: Vitamina C y Zinc que contribuyen a reforzar el sistema de defensas consumiéndolo diariamente. Además contiene miles de probióticos que contribuyen al bienestar del cuerpo.</h5>
            <img style="width: 15rem;" src="IMG/la-YOX-MULTIFRUTAS_F.png" alt="">
        </div>

          <!-- pie de pagina -->
          <div class="modal-footer">
              <button class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
          </div>

      </div>
  </div>
  </div>
  </div>
</div>
</div>
</div>
<!-- fin de productos lacteos -->

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
</body>
</html>