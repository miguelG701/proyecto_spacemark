<?php 
include_once 'conexion.php'; 
session_start();
// Verifica si se ha enviado el formulario para cerrar sesión
if (!isset($_SESSION['usuario_nombre'])) {
    // Destruye la sesión
    session_destroy();
    // Redirige al usuario a pgindex.php
    header("Location: pgindex.php");}
if(isset($_POST['btn_regresar'])){
header("Location: index.php");
}

$query = "SELECT * FROM historial_ventas ORDER BY ID ASC LIMIT 40"; // Excluye a los administradores (id_tipos = 1)
$statement = $con->prepare($query);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

  if(isset($_POST['btn_buscar'])){
  $buscar_text= $_POST['busqueda'];
  $select_buscar= $con->prepare('SELECT * FROM historial_ventas WHERE id_usuario LIKE :campo OR IDP LIKE :campo ;');
  $select_buscar->execute(array(':campo'=>"%". $buscar_text ."%"));
  $result=$select_buscar->fetchAll(PDO::FETCH_ASSOC);
  }
?>

<?php
$Message1 = false;
$Message2 = false;
$Message3 = false;

        if ($Message1 == true){
        
        ?>
         <div class="alerta_posit">
         <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
              <!-- <symbol id="check-circle-fill" viewBox="0 0 16 16">
                <path fill="green" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
              </symbol> -->
            	</svg>
            <div class="alert alert-success  ajuste_color_alerta  fade show alert-dismissible" role="alert">
              <h4 class="alert-heading">  	
                    <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Danger:"  style="width: 20px; height: 20px;" ><use xlink:href="#check-circle-fill"/>
                    </svg>¡ ha ocurrido un error al guardar. !
                </h4>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <p class="mb-0"> </p>
            </div>
            </div>
<?php }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compras</title>
    <link rel="stylesheet" href="CSS/bootstrap.css">
    <style>
      body{
        background-color: #565656;
      }
      h4{
        color: #000;
      }
      label{
        color: #000;
      }
    </style>
</head>
<?php
if(isset($_POST['btn_buscar'])){
  $buscar_text= $_POST['busqueda'];
  $select_buscar= $con->prepare('SELECT * FROM historial_ventas WHERE id_usuario LIKE :campo OR IDP LIKE :campo ;');
  $select_buscar->execute(array(':campo'=>"%". $buscar_text ."%"));
  $result=$select_buscar->fetchAll(PDO::FETCH_ASSOC);
  }
?>
<body>
    <form action="" class="container" method="POST">
        <div class="row justify-content-center">
            <h1 class="text-center mt-5">Historial de Compras</h1>
            <div class="col-1 mt-5">
            <button class="btn btn-primary" type="submit" name="btn_regresar">Regresar</button>
            </div>
            <div class="col-5 mt-5">
            <input type="text" name="busqueda" placeholder="busqueda..." class="form-control" value="<?php if(isset($busqueda_text)) echo $busqueda_text; ?>">
            </div>
            <div class="col-5 mt-5">
            <button class="btn btn-primary" type="submit" name="btn_buscar">buscar</button>
            </div>
            
            <table class="table table-dark mt-3">
                <tr>
                    <th>Id</th><th>Usuario</th><th>Metodo de Pago</th><th>cantidad</th><th>Fecha</th><th>Estado</th>
                    <th>ID de Producto</th><th>Total de compra</th><th>Acciones</th>
                </tr>
                <?php
                
                foreach ($result as $resultado) {
                $ID = (int) $resultado['ID'];
                $Estado = $_POST['Estado'];
                $Cantidad = $_POST['Cantidad'];

                

                ?>
                <tr>
                    <td><?php echo $resultado['ID']; ?></td>
                    <td><?php echo $resultado['id_usuario']; ?></td>
                    <td><?php echo $resultado['Metodo_pago']; ?></td>
                    <td><?php echo $resultado['Cantidad']; ?></td>
                    <td><?php echo $resultado['Fecha']; ?></td>
                    <td><?php echo $resultado['Estado']; ?></td>
                    <td><?php echo $resultado['IDP']; ?></td>
                    <td><?php echo $resultado['Total']; ?></td>
                    <td><button type='button' class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#modax<?php echo $resultado['ID']; ?>'>Editar</button>
                        <div class='modal fade' id='modax<?php echo $resultado['ID']; ?>' tabindex='1' aria-hidden='true' aria-labelledby='label-modax<?php echo $resultado['ID']; ?>'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h4 class='modal-title'>Editar</h4>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body'>
                                        <form action="" method="post">
                                            <input type="hidden" name="ID" disabled value="<?php echo $resultado['ID'] ?>">
                                            <div class="form-group">
                                                <label for="Estado">Estado</label>
                                                <input type="text" id="Estado" name="Estado" value="<?php echo $resultado['Estado'] ?>" class="input__text">
                                            </div>
                                            <div class="form-group">
                                                <label for="Cantidad">Cantidad</label>
                                                <input type="text" id="Cantidad" name="Cantidad" value="<?php echo $resultado['Cantidad'] ?>" class="input__text">
                                            </div>                  
                                        </form>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
                                        <input type="submit" name="btn_guardar" value="Guardar" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <?php }
                    if(isset($_POST['btn_guardar'])){ 
                      $consulta_update = $con->prepare('UPDATE historial_ventas SET Estado=:Estado, Cantidad=:Cantidad WHERE ID=:ID');
                      $consulta_update->execute(['ID' => $ID, 'Estado' => $Estado, 'Cantidad' => $Cantidad]);
                      $result2 = $consulta_update->fetchAll(PDO::FETCH_ASSOC);
                      }
                    ?>
                </tr>
            </table>
        </div>
    </form>

</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>