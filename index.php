<?php

/*=============================================
REGISTRO DE ERRORES
=============================================*/

ini_set('display_errors', 1);
ini_set("log_errors", 1);
ini_set("error_log",  "logs/php_error_log");
error_reporting(E_ALL);

/*=============================================
   CONTROLADORES
=============================================*/

require_once "controladores/usuarios.controlador.php";

require_once "controladores/plantilla.controlador.php";

/*=============================================
   MODELOS
=============================================*/

require_once "modelos/usuarios.modelo.php";

$plantilla = new ControladorPlantilla();
$plantilla->ctrPlantilla();
