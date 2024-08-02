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
     Declaración de Privacidad</h1>
        <p>En Spacemark, tu privacidad es importante para nosotros. Esta declaración de privacidad explica cómo recopilamos, usamos y protegemos tu información personal.</p>

        <h2>Información Recopilada</h2>
        <p>Recopilamos información personal que nos proporcionas directamente (por ejemplo, nombre, correo electrónico, historial de compras).</p>

        <h2>Uso de la Información</h2>
        <p>Utilizamos la información para mejorar nuestros servicios, comunicarte actualizaciones y promociones, y cumplir con nuestras obligaciones legales.</p>

        <h2>Compartición de la Información</h2>
        <p>No compartimos tu información personal con terceros sin tu consentimiento, excepto cuando sea necesario para cumplir con la ley o proteger nuestros derechos.</p>

        <h2>Seguridad</h2>
        <p>Implementamos medidas de seguridad para proteger tu información contra acceso no autorizado, alteración, divulgación o destrucción.</p>

        <h2>Tus Derechos</h2>
        <p>Tienes derecho a acceder, corregir o eliminar tu información personal. Para hacerlo, contáctanos en [tu correo electrónico].</p>

        <h2>Cambios en la Declaración</h2>
        <p>Podemos actualizar esta declaración de privacidad ocasionalmente. Te notificaremos sobre cambios importantes.</p>

        </div>

    <div class="container">
        <h1>Política de Cookies</h1>
        <p>En Spacemark, utilizamos cookies para mejorar tu experiencia en nuestro sitio web. Aquí te explicamos qué son las cookies y cómo las usamos.</p>

        <h2>¿Qué son las Cookies?</h2>
        <p>Las cookies son pequeños archivos de datos que se almacenan en tu dispositivo cuando visitas un sitio web.</p>

        <h2>Uso de Cookies</h2>
        <p>Utilizamos cookies para recordar tus preferencias, analizar el tráfico del sitio y mejorar la funcionalidad del sitio.</p>

        <h2>Gestionar Cookies</h2>
        <p>Puedes configurar tu navegador para rechazar cookies o eliminar cookies ya existentes. Ten en cuenta que esto puede afectar tu experiencia en nuestro sitio.</p>

        <h2>Cookies de Terceros</h2>
        <p>Podemos utilizar cookies de terceros para servicios como análisis web y publicidad. Estas cookies están sujetas a las políticas de privacidad de los terceros.</p>

        <h2>Cambios en la Política</h2>
        <p>Actualizaremos esta política de cookies cuando sea necesario. Te notificaremos sobre cualquier cambio importante.</p>

        <h2>Contacto</h2>
        <p>Si tienes preguntas sobre nuestra política de cookies, contáctanos en [spacemarksag@gmail.com].</p>
    </div>

    <div class="containerDark mb-4">
        <div class="col-12 m-1 text-center">
            <a href="index.php" class="btn btn-danger">Regresar</a>
        </div>
    </div>
</body>
</html>