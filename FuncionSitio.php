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
        Cómo Funciona el Sitio (Manual del Usuario)</h1>
        <p>Este es un breve manual para ayudar a los usuarios a entender cómo utilizar Spacemark</p>

        <h2>Cómo Funciona el Sitio</h2>
        <p>Bienvenido a Spacemark. Aquí te ofrecemos una guía rápida sobre cómo utilizar nuestro sitio:</p>

        <h2>Registro</h2>
        <p>Para acceder a todas las funciones, debes registrarte y crear una cuenta.</p>
        <div class="mb-5 d-flex justify-content-center">
        <iframe class="rounded" width="1095" height="615" src="https://www.youtube.com/embed/B_ndoOCkAls?si=wGMaPaoHP6tAOhfI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

        <!-- <video class="rounded" controls width="75%">
            <source src="IMG/registro-capture.mp4" type="video/mp4">
            Tu navegador no soporta la etiqueta de video.
        </video> -->
        </div>
        
        <h2>Inicio de Sesión</h2>
        <p>Una vez registrado, puedes iniciar sesión con tu nombre de usuario y contraseña.</p>
        <div class="mb-5 d-flex justify-content-center">
        <iframe class="rounded" width="1095" height="615" src="https://www.youtube.com/embed/_HsYUOr0I0U?si=kHch6T_woc6pIM-K" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        <!-- <video class="rounded" controls width="75%">
            <source src="IMG/inicio-capture.mp4" type="video/mp4">
            Tu navegador no soporta la etiqueta de video.
        </video> -->
        </div>

        <h2>Buscar Información</h2>
        <p>Utiliza la barra de búsqueda en la parte superior para encontrar productos, usuarios y más. Puedes filtrar tus búsquedas por diferentes criterios.</p>
        <div class="mb-5 d-flex justify-content-center">
        <iframe class="rounded" width="1095" height="615" src="https://www.youtube.com/embed/N8365ZTpuLA?si=qvcp54p4bGSXiJYK" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        <!-- <video class="rounded" controls width="75%">
            <source src="IMG/navegar-capture.mp4" type="video/mp4">
            Tu navegador no soporta la etiqueta de video.
        </video> -->
        </div>

        <h2>Actualizar Información</h2>
        <p>Para actualizar tu información personal, accede a tu perfil y realiza los cambios necesarios. Asegúrate de guardar los cambios.</p>
        <div class="mb-5 d-flex justify-content-center">
        <iframe class="rounded" width="1095" height="615" src="https://www.youtube.com/embed/snE-D1dk3es?si=oDoAGWAMvZs7Rup1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        <!-- <video class="rounded" controls width="75%">
            <source src="IMG/opciones-capture.mp4" type="video/mp4">
            Tu navegador no soporta la etiqueta de video.
        </video> -->
        </div>

        <h2>Realizar Compras</h2>
        <p>Navega por nuestro catálogo de productos y añade los artículos que desees a tu carrito. Procede al checkout para completar la compra.</p>
        <div class="mb-5 d-flex justify-content-center">
        <iframe class="rounded" width="1095" height="615" src="https://www.youtube.com/embed/Eee-_LHkDk0?si=eHsSZAwr4vWh_RiA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        <!-- <video class="rounded" controls width="75%">
            <source src="IMG/compra-capture.mp4" type="video/mp4">
            Tu navegador no soporta la etiqueta de video.
        </video> -->
        </div>

        <h2>Soporte y Contacto</h2>
        <p>Si necesitas ayuda, puedes contactarnos a través del formulario de contacto o por correo electrónico en [spacemarksag@gmail.com].</p>
        <div class="mb-5 d-flex justify-content-center">
        <iframe class="rounded" width="1095" height="615" src="https://www.youtube.com/embed/k6XKSQcYJTk?si=CdnTz4NkW5gbHlKV" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        <!-- <video class="rounded" controls width="75%">
            <source src="IMG/ayuda-capture.mp4" type="video/mp4">
            Tu navegador no soporta la etiqueta de video.
        </video> -->
        </div>

    </div>

    <div class="containerDark mb-4">
        <div class="col-12 m-1 text-center">
            <a href="index.php" class="btn btn-danger">Regresar</a>
        </div>
    </div>
</body>
</html>