<?php

$database="spacemark";
$user='root';
$password='';
$server="localhost";

$conex=new mysqli($server,$user,$password,$database);
//echo"conectado";

try{
$con = new PDO('mysql:host=localhost;dbname='.$database, $user, $password);
  // echo"conectado";

} catch(PDOException $e){
    echo "ERROR".$e->getMessage();
}


?>