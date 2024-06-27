<?php
session_start();

$mensaje1=false;
$mensaje2=false;
$mensaje3=false;

?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>
 </head>
 <body>
 </body>
 </html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_producto'], $_POST['id_usuario'], $_POST['cantidad'], $_POST['metodo_pago'], $_POST['accion'])) {
        include_once "conexion.php";
        
        $id_producto = intval($_POST['id_producto']);
        $id_usuario = intval($_POST['id_usuario']);
        $cantidad = intval($_POST['cantidad']);
        $metodo_pago = htmlspecialchars($_POST['metodo_pago']);
        $accion = $_POST['accion'];

        // Obtener datos del producto
        $query_producto = "SELECT Nombre, Precio, Cantidad FROM productos WHERE IDP = ?";
        $statement_producto = $con->prepare($query_producto);
        $statement_producto->execute([$id_producto]);
        $producto = $statement_producto->fetch(PDO::FETCH_ASSOC);

        // Verificar si hay una promoción activa para el producto
        $query_promocion = "SELECT Descuento FROM promociones WHERE ID_Producto = ? AND Estado = 'Activa' AND Fecha_Inicio <= CURRENT_DATE AND Fecha_Final >= CURRENT_DATE";
        $statement_promocion = $con->prepare($query_promocion);
        $statement_promocion->execute([$id_producto]);
        $promocion = $statement_promocion->fetch(PDO::FETCH_ASSOC);

        // Calcular el total con o sin descuento
        if ($cantidad > 0 && $cantidad <= $producto['Cantidad']) {
            $precio_producto = $producto['Precio'];
            if ($promocion) {
                $descuento = $promocion['Descuento'];
                $precio_producto = $precio_producto * (1 - $descuento);
            }
            $total = $cantidad * $precio_producto;
            $estado = ($accion == 'carrito') ? 'En Carrito' : 'Pendiente';

            try {
                $con->beginTransaction();

                $query_insert = "INSERT INTO historial_ventas (id_usuario, Metodo_pago, Cantidad, Fecha, Estado, IDP, Total) 
                                 VALUES (?, ?, ?, current_timestamp(), ?, ?, ?)";
                $statement_insert = $con->prepare($query_insert);
                $statement_insert->execute([$id_usuario, $metodo_pago, $cantidad, $estado, $id_producto, $total]);

                if ($accion != 'carrito') {
                    $nueva_cantidad = $producto['Cantidad'] - $cantidad;
                    $query_update = "UPDATE productos SET Cantidad = ? WHERE IDP = ?";
                    $statement_update = $con->prepare($query_update);
                    $statement_update->execute([$nueva_cantidad, $id_producto]);
                }

                $con->commit();

                $mensaje = ($accion == 'carrito') ? 'Producto añadido al carrito.' : 'Compra realizada con éxito.';
                echo "<script>alert('$mensaje'); window.location.href='index.php';</script>";
            } catch (PDOException $e) {
                $con->rollback();
                echo "Error al procesar la operación: " . $e->getMessage();
            }
        } else {
            // $mensaje1 = true;window.location.href='index.php';
            echo "<script>
            swal({ title:'Error',
            text:'La cantidad seleccionada supera la cantidad disponible en el stock... o Es menor a 1 Por favor, selecciona una cantidad valida.',
            icon: 'error',
            button: 'Ok'}).then(function(){window.location.href='index.php';});   
            
            </script>";
        }
    } else {
        // $mensaje2 = true;
        echo "Error: Faltan datos necesarios para procesar la operación.";
    }
} else {
    // $mensaje3 = true;
    echo "<script src='sweetalert2.all.min.js' > swal('Compra exitosa.'); window.location.href='index.php';</script>";
    exit;
}
?>
<?php
// if ($mensaje1 == true) {
//     echo '<div class="alerta_posit">';
//     echo '<svg xmlns="http://www.w3.org/2000/svg" class="d-none">';
//     // echo '<symbol id="check-circle-fill" viewBox="0 0 16 16">';
//     // echo '<path fill="green" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>';
//     // echo '</symbol>';
//     echo '</svg>';
//     echo '<div class="alert alert-success ajuste_color_alerta fade show alert-dismissible" role="alert">';
//     echo '<h4 class="alert-heading">';
//     echo '<svg class="bi flex-shrink-0 me-2" role="img" aria-label="Success:" style="width: 20px; height: 20px;">';
//     echo '<use xlink:href="#check-circle-fill"/>';
//     echo '</svg>';
//     echo '¡La cantidad seleccionada supera la cantidad disponible en el stock...<br> o Es menor a 1 Por favor, selecciona una cantidad valida. !';
//     echo '</h4>';
//     echo '<button name="cerrar" onclick="redirigir()" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
//     echo '<p class="mb-0"> </p>';
//     echo '</div>';
//     echo '</div>';
// }
// if ($mensaje2 == true) {
//     echo '<div class="alerta_posit">';
//     echo '<svg xmlns="http://www.w3.org/2000/svg" class="d-none">';
//     // echo '<symbol id="check-circle-fill" viewBox="0 0 16 16">';
//     // echo '<path fill="green" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>';
//     // echo '</symbol>';
//     echo '</svg>';
//     echo '<div class="alert alert-success ajuste_color_alerta fade show alert-dismissible" role="alert">';
//     echo '<h4 class="alert-heading">';
//     echo '<svg class="bi flex-shrink-0 me-2" role="img" aria-label="Success:" style="width: 20px; height: 20px;">';
//     echo '<use xlink:href="#check-circle-fill"/>';
//     echo '</svg>';
//     echo 'Error: Faltan datos necesarios para procesar la operación.';
//     echo '</h4>';
//     echo '<button name="cerrar" onclick="redirigir()" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
//     echo '<p class="mb-0"> </p>';
//     echo '</div>';
//     echo '</div>';
// }
// if ($mensaje3 == true) {
//     echo '<div class="alerta_posit">';
//     echo '<svg xmlns="http://www.w3.org/2000/svg" class="d-none">';
//     // echo '<symbol id="check-circle-fill" viewBox="0 0 16 16">';
//     // echo '<path fill="green" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>';
//     // echo '</symbol>';
//     echo '</svg>';
//     echo '<div class="alert alert-success ajuste_color_alerta fade show alert-dismissible" role="alert">';
//     echo '<h4 class="alert-heading">';
//     echo '<svg class="bi flex-shrink-0 me-2" role="img" aria-label="Success:" style="width: 20px; height: 20px;">';
//     echo '<use xlink:href="#check-circle-fill"/>';
//     echo '</svg>';
//     echo 'Compra exitosa.';
//     echo '</h4>';
//     echo '<button name="cerrar" onclick="redirigir()" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
//     echo '<p class="mb-0"> </p>';
//     echo '</div>';
//     echo '</div>';
// }?>