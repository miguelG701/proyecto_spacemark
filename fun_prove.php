<?php 
include_once 'conexion.php' ;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['guardar'])) {
    if (!empty($_POST['nombre'])||!empty($_POST['desc'])||!empty($_POST['precio'])||!empty($_POST['tipo'])||!empty($_POST['imgs'])){
        $nombre_p =$_POST['nombre'];
        $precio_p =$_POST['precio'];
        $tipo_p =$_POST['tipo'];
        $img_p =addslashes(file_get_contents($_FILES['imgs']['tmp_name']));
        $desc_p =$_POST['desc'];
    $sql_ins_p= "INSERT INTO producto(id_prod, nombre_prod, precio_prod, tipologia_prod, des_det_prod, img_prod)
    VALUES(NULL, '$nombre_p', '$precio_p', '$tipo_p', '$desc_p', '$img_p')";
    $pass_in = $con->prepare($sql_ins_p);
    $pass_in->execute();
    $rest_in = $pass_in->fetchAll(PDO::FETCH_ASSOC);
    header("Location: fun_prove.php");
    }else{
        echo"<script>
        alert('llene los campos para registrar el producto')
        </script>";
    }}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ingresar productos</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <h1>ingresar productos</h1>
        <input type="text" name="nombre" placeholder="nombre">
        <input type="number" name="precio" placeholder="precio"><br><br>
        <input type="text" name="tipo" placeholder="tipo de producto"><br><br>
        <input type="file" name="imgs"><br><br>
        <input type="text" name="desc" placeholder="descripcion">
        <input type="submit" name="guardar" value="agregar producto">
    </form>
</body>
</html>