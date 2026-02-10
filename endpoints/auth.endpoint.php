<?php

// Configurar cabeceras para respuestas JSON
header('Content-Type: application/json; charset=utf-8');

// Obtener método HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

// Procesar datos de entrada (JSON)
$entrada = json_decode(file_get_contents('php://input'), true);

if ($metodo === 'POST' && isset($_POST['ingUsuario']) && isset($_POST['ingPassword'])) {
    $respuesta = ControladorUsuarios::ctrIngresoUsuario();
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
