<?php 

include_once 'conexion.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    // Si no está autenticado, redirige al formulario de inicio de sesión
    header("Location: pgindex.php");
    exit;
}

//mensaje de error

// confirmar usuarios ini

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['guardar'])) {
        // Verificar si se han seleccionado usuarios para aceptar
        if (isset($_POST['usuarios_aceptar']) && !empty($_POST['usuarios_aceptar'])) {
            // Obtener los IDs de usuario seleccionados para aceptar
            $usuarios_aceptar = $_POST['usuarios_aceptar'];
            
            // Actualizar el estado de aceptación y el tipo de usuario para los usuarios seleccionados
            foreach ($usuarios_aceptar as $id_usuario) {
                $tipo_usuario = $_POST['tipo_usuario'][$id_usuario];
                $query_aceptar = "UPDATE usuarios SET aceptado = 'si', id_tipos = :tipo_usuario WHERE id_usuario = :id_usuario";
                $statement_aceptar = $con->prepare($query_aceptar);
                $statement_aceptar->execute([':tipo_usuario' => $tipo_usuario, ':id_usuario' => $id_usuario]);
            }
            
        }

        // Verificar si se han seleccionado usuarios para eliminar
        if (isset($_POST['usuarios_eliminar']) && !empty($_POST['usuarios_eliminar'])) {
            // Obtener los IDs de usuario seleccionados para eliminar
            $usuarios_eliminar = $_POST['usuarios_eliminar'];
  
            // Eliminar los usuarios seleccionados
            $query_eliminar = "DELETE FROM usuarios WHERE id_usuario IN (" . implode(',', $usuarios_eliminar) . ")";
            $con->query($query_eliminar);

        }
        // Redirigir a alguna página después de actualizar la base de datos
        echo "<script>window.location.href='aceptaruser.php';</script>"; 
        exit;
        
        
    }
    
}

//mensajes de pagina2


// confirmar usuarios fin

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
            margin-top: 20px;
        }
        .table-dark {
            background-color: #343a40;
            color: #ffffff;
            border-radius: 10px;
            margin-top: 20px;
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
    <!-- confirmar registro ini -->

    <form id="userForm" action="" method="post">
        <div class="">
        
        <h2 class="mb-4">Usuarios por Aceptar</h2>
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th scope="col">Usuario</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Tipo de Usuario</th>
                        <th scope="col">Verificar</th>
                        <th scope="col">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta para obtener usuarios con "no" en la columna "aceptado"
                    $query = "SELECT * FROM usuarios WHERE aceptado = 'no'";
                    $statement = $con->prepare($query);
                    $statement->execute();
                    $usuarios = $statement->fetchAll();

                    // Consulta para obtener todos los tipos de usuario
                    $query_tipos = "SELECT * FROM tipos_usuarios";
                    $statement_tipos = $con->prepare($query_tipos);
                    $statement_tipos->execute();
                    $tipos_usuarios = $statement_tipos->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($usuarios as $usuario) {
                        echo "<tr>";
                        echo "<td>" . $usuario['usuario'] . "</td>";
                        echo "<td>" . $usuario['nombre'] . "</td>";
                        echo "<td>";
                        echo "<select class='form-control' name='tipo_usuario[" . $usuario['id_usuario'] . "]'>";
                        foreach ($tipos_usuarios as $tipo) {
                            $selected = ($tipo['id_tipos'] == $usuario['id_tipos']) ? "selected" : "";
                            echo "<option value='" . $tipo['id_tipos'] . "' $selected>" . $tipo['nom_tipos'] . "</option>";
                        }
                        echo "</select>";
                        echo "</td>";
                        echo "<td>";
                        echo "<input class='form-check-input' type='checkbox' name='usuarios_aceptar[]' value='" . $usuario['id_usuario'] . "' id='aceptar-" . $usuario['id_usuario'] . "'>";
                        echo "<label class='text-white opacity-75 form-check-label' for='aceptar-" . $usuario['id_usuario'] . "'>SI</label>";
                        echo "</td>";
                        echo "<td>";
                        echo "<input class='form-check-input' type='checkbox' name='usuarios_eliminar[]' value='" . $usuario['id_usuario'] . "' id='eliminar-" . $usuario['id_usuario'] . "'>";
                        echo "<label class='text-white opacity-75 form-check-label' for='eliminar-" . $usuario['id_usuario'] . "'>NO</label>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-outline-danger btn-sm" name="guardar">Guardar</button>
        </div>
    </form>

    <!-- confirmar registro fin -->
    <!-- Botón de regresar -->
    <div class="row mt-3">
        <div class="col-12 text-center">
            <a href="index.php" class="btn btn-danger">Regresar</a>
        </div>
    </div>

</div>

<script>
    document.getElementById('userForm').addEventListener('submit', function(event) {
        let aceptarCheckboxes = document.querySelectorAll('input[name="usuarios_aceptar[]"]:checked');
        let eliminarCheckboxes = document.querySelectorAll('input[name="usuarios_eliminar[]"]:checked');

        if (aceptarCheckboxes.length === 0 && eliminarCheckboxes.length === 0) {
            alert('Por favor, selecciona al menos uno de los checkboxes.');
            event.preventDefault(); // Previene el envío del formulario
        }
    });
</script>
</body>
</html>

