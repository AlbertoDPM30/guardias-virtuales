<?php

require_once "../controladores/hall.controlador.php";
require_once "../modelos/hall.modelo.php";

// Configurar cabeceras para respuestas JSON
header('Content-Type: application/json; charset=utf-8');

// Obtener método HTTP
$metodo = $_SERVER['REQUEST_METHOD'];

// Procesar datos de entrada (JSON)
$entrada = json_decode(file_get_contents('php://input'), true);

/*=========================================
MANEJO DE MÉTODOS HTTP PARA USUARIOS
==========================================*/
switch ($metodo) {

    /*=========================================
    OBTENER SALA(S)
    ==========================================*/
    case 'GET':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $respuesta = ControladorHall::ctrMostrarSalas($id ? "id" : null, $id);
        http_response_code($respuesta['status']);
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        break;

    /*=========================================
    REGISTRAR SALA
    ==========================================*/
    case 'POST':
        if (empty($entrada['numero'])) {
            echo json_encode(["mensaje" => "Todos los campos son obligatorios"], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit;
        }

        $respuesta = ControladorHall::ctrCrearSala($entrada);
        http_response_code($respuesta['status']);
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        break;

    /*=========================================
    EDITAR SALA
    ==========================================*/
    case 'PUT':
        $respuesta = ControladorHall::ctrEditarSala($entrada);
        http_response_code($respuesta['status']);
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        break;

    /*=========================================
    ELIMINAR SALA
    ==========================================*/
    case 'DELETE':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            http_response_code(400);
            echo json_encode([
                "status" => 400,
                "success" => false,
                "message" => "Se requiere el ID para eliminar un usuario."
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            break;
        }
        $respuesta = ControladorUsuarios::ctrEliminarUsuario($id);
        http_response_code($respuesta['status']);
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        break;
    
    /*=========================================
    ACTUALIZAR SALA
    ==========================================*/
    case 'PATCH':
        $id = $entrada['id'] ?? null;
        $status = $entrada['status'] ?? null;

        if ($id === null || $status === null) {
            http_response_code(400);
            echo json_encode([
                "status" => 400,
                "success" => false,
                "message" => "Se requiere 'id' y 'status' para actualizar el estado del usuario."
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            break;
        }

        $respuesta = ControladorUsuarios::ctrActualizarStatusUsuario($id, $status);
        http_response_code($respuesta['status']);
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        break;

    /*=========================================
    MÉTODO NO PERMITIDO
    ==========================================*/
    default:
        http_response_code(405);
        echo json_encode([
            "status" => 405,
            "success" => false,
            "message" => "Método no permitido para la ruta de usuarios."
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        break;
}