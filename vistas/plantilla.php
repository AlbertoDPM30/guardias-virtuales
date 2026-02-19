<?php

session_start();

?>

<!DOCTYPE html>
<html lang="es_VE">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asesoría Consolitex</title>

    <!-- <link rel="icon" href="vistas/img/plantilla/icono-consolitex.png"> -->

    <!-- ESTILOS -->
    <link rel="stylesheet" href="vistas/css/estilos.css">
    <link rel="stylesheet" href="libraries/bootstrap/css/bootstrap.min.css">

    <!-- SCRIPTS -->
    <script src="libraries/jquery/jquery-4-0-0.min.js"></script>
    <script src="libraries/bootstrap/js/bootstrap.min.js"></script>
    <script src="libraries/peerjs/peerjs.min.js.map"></script>

</head>
<body class="hold-transition sidebar-mini layout-fixed">

    <div class="wrapper">

    <?php

        // Capturamos la ruta actual o definimos 'inicio' por defecto
        $ruta = isset($_GET["ruta"]) ? $_GET["ruta"] : "inicio";

        // Verificamos el estado de la sesión
        $isLoggedIn = (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok");

        /*=============================================
        LÓGICA DE ENRUTAMIENTO
        =============================================*/

        if (!$isLoggedIn) {
            
            // --- USUARIO NO LOGUEADO ---
            if ($ruta == "inicio") {
                include "modulos/inicio.php";
            } else {
                
                include "modulos/login.php";
            }

        } else {

            // --- USUARIO LOGUEADO ---
            include "modulos/header.php";

            // Redirección al Dashboard (hall) si intenta ir a rutas públicas
            if ($ruta == "login" || $ruta == "inicio") {
                
                header("Location: hall");
                include "modulos/hall.php";

            } 
            // Acceso a rutas protegidas
            else if (
                $ruta == "hall"     ||
                $ruta == "usuarios" ||
                $ruta == "dashboard"
            ) {
                
                include "modulos/" . $ruta . ".php";

            } 
            // Ruta no encontrada
            else {
                
                include "modulos/404.php";
                
            }

            include "modulos/footer.php";
        }
    ?>

    </div>

    <!-- SCRIPTS JS -->
    <script src="vistas/js/logout.js"></script>

</body>
</html>