<<<<<<< HEAD
<?php


// local
$database="spacemark";
$user='root';
$password='';
$server="localhost";

$conex=new mysqli($server,$user,$password,$database);
//echo"conectado";

try{
$con = new PDO('mysql:host=localhost;dbname='.$database, $user, $password);
  //echo"conectado";

} catch(PDOException $e){
    echo "ERROR".$e->getMessage();
}

// server
// $database="u459296459_supermercado";
// $user='u459296459_root1';
// $password='Adso2024**';
// $server="localhost";

// $conex=new mysqli($server,$user,$password,$database);
// //echo"conectado";

// try{
// $con = new PDO('mysql:host=localhost;dbname='.$database, $user, $password);
//   //echo"conectado";

// } catch(PDOException $e){
//     echo "ERROR".$e->getMessage();
// }


=======
<?php


// local
// $database="spacemark";
// $user='root';
// $password='';
// $server="localhost";

// $conex=new mysqli($server,$user,$password,$database);
// //echo"conectado";

// try{
// $con = new PDO('mysql:host=localhost;dbname='.$database, $user, $password);
//   //echo"conectado";

// } catch(PDOException $e){
//     echo "ERROR".$e->getMessage();
// }

// server
$database="u459296459_supermercado";
$user='u459296459_root1';
$password='Adso2024**';
$server="localhost";

$conex=new mysqli($server,$user,$password,$database);
//echo"conectado";

try{
$con = new PDO('mysql:host=localhost;dbname='.$database, $user, $password);
  //echo"conectado";

} catch(PDOException $e){
    echo "ERROR".$e->getMessage();
}


>>>>>>> 7ba2cf3d847245476291b52426604944cd704857
?>