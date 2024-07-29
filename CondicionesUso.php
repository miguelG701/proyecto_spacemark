<?php
include_once 'conexion.php';
session_start();
include_once("sweetarch.php");


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
        .containerDark {
            background-color: #212529;
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
        <h1>        <img class="m-1" src="IMG/Spacemark ico_transparent.ico" alt="SpaceMark Logo" height="50">
        Condiciones de Uso</h1>
        <p>Bienvenido a Spacemark. Al acceder y utilizar nuestro sitio web, aceptas cumplir con los siguientes términos y condiciones:</p>

        <h2>Uso del Sitio Web</h2>
        <p>Solo puedes usar nuestro sitio para fines legales y de acuerdo con estas condiciones.</p>
        <p>No debes usar el sitio de manera que pueda dañar, deshabilitar, sobrecargar o perjudicar el sitio, o interferir con el uso de terceros.</p>

        <h2>Propiedad Intelectual</h2>
        <p>Todo el contenido del sitio web, incluyendo pero no limitado a textos, gráficos, logos y software, es propiedad de Spacemark o de sus licenciantes y está protegido por las leyes de propiedad intelectual.</p>

        <h2>Modificaciones del Servicio</h2>
        <p>Spacemark se reserva el derecho de modificar o interrumpir el servicio en cualquier momento y sin previo aviso.</p>

        <h2>Responsabilidad</h2>
        <p>Spacemark no será responsable de ningún daño que pueda surgir de la utilización del sitio web o de cualquier información contenida en el mismo.</p>

        <h2>Enlaces Externos</h2>
        <p>El sitio puede contener enlaces a sitios web de terceros. No nos hacemos responsables del contenido o las políticas de privacidad de esos sitios.</p>

        <h2>Ley Aplicable</h2>
        <p>Estos términos se regirán e interpretarán de acuerdo con las leyes de Colombia.</p>

        <h2>Contacto</h2>
        <p>Para cualquier pregunta sobre estas condiciones, por favor contáctanos en [spacemarksag@gmail.com].</p>
        

    
    </div>
<div class="containerDark mb-4">
    <div class="col-12 m-1 text-center">
            <a href="index.php" class="btn btn-danger">Regresar</a>
    </div>
</div>
    
</body>
</html>
