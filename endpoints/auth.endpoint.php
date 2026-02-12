<?php

require_once "../controladores/usuarios.controlador.php";
require_once "../modelos/usuarios.modelo.php";

// Configurar cabeceras para respuestas JSON
header('Content-Type: application/json; charset=utf-8');

// Obtener método HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

// Procesar datos de entrada (JSON)
$entrada = json_decode(file_get_contents('php://input'), true);

/*=========================================
MANEJO DE MÉTODOS HTTP PARA AUTENTICACIÓN
==========================================*/
if ($metodo === 'POST' && isset($entrada['cedula']) && isset($entrada['password'])) {
    $respuesta = ControladorUsuarios::ctrIngresoUsuario($entrada);
    http_response_code($respuesta['status']);
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
    
} elseif ($metodo === 'POST' && isset($entrada['logout']) && $entrada['logout'] === true) {
    $respuesta = ControladorUsuarios::ctrLogoutUsuario();
    http_response_code($respuesta['status']);
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;

} else {

    http_response_code(405);
    echo json_encode([
        "status" => 405,
        "success" => false,
        "message" => "Método no permitido para la ruta de usuarios."
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}
