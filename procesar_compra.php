<?php
session_start();

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
            echo "<script>alert('La cantidad seleccionada supera la cantidad disponible en el stock. Por favor, selecciona una cantidad menor.'); window.location.href='index.php';</script>";
        }
    } else {
        echo "Error: Faltan datos necesarios para procesar la operación.";
    }
} else {
    header("Location: index.php");
    exit;
}
?>