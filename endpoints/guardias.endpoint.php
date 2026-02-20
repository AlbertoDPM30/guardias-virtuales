<?php

require_once "../controladores/guardias.controlador.php";
require_once "../modelos/guardias.modelo.php";

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
    OBTENER GUARDIA(S)
    ==========================================*/
    case 'GET':
        $filtroId;
        $item;
        switch (true) {
            case isset($_GET['id']):
                $filtroId = $_GET['id'];
                $item = "id";
                break;
            case isset($_GET['id_usuario']):
                $filtroId = $_GET['id_usuario'];
                $item = "id_usuario";
                break;
            case isset($_GET['id_sala']):
                $filtroId = $_GET['id_sala'];
                $item = "id_sala";
                break;
            default:
                $filtroId = null;
                $item = null;
        }

        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $respuesta = ControladorGuardias::ctrMostrarGuardia($item, $filtroId);
        http_response_code($respuesta['status']);
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        break;

    /*=========================================
    REGISTRAR GUARDIA
    ==========================================*/
    case 'POST':
        if (empty($entrada['id_sala']) || empty($entrada['id_usuario']) || empty($entrada['inicio_guardia'])) {
            echo json_encode(["mensaje" => "Todos los campos son obligatorios"], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit;
        }

        $respuesta = ControladorGuardias::ctrCrearGuardia($entrada);
        http_response_code($respuesta['status']);
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        break;

    /*=========================================
    EDITAR GUARDIA
    ==========================================*/
    case 'PUT':
        $respuesta = ControladorGuardias::ctrEditarGuardia($entrada);
        http_response_code($respuesta['status']);
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        break;

    /*=========================================
    ELIMINAR GUARDIA
    ==========================================*/
    case 'DELETE':
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            http_response_code(400);
            echo json_encode([
                "status" => 400,
                "success" => false,
                "message" => "Se requiere el ID para eliminar una guardia."
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            break;
        }
        $respuesta = ControladorGuardias::ctrEliminarGuardia($id);
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
                "message" => "Se requiere 'id' y 'status' para actualizar el estado de la guardia."
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            break;
        }

        $respuesta = ControladorGuardias::ctrActualizarStatusGuardia($id, $status);
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