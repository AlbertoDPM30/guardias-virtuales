<?php

class ControladorUsuarios
{

	/*=============================================
	INGRESO DE USUARIO
	=============================================*/

	static public function ctrIngresoUsuario($entrada)
	{

        if (preg_match('/^[0-9]+$/', $entrada["cedula"])) {

            $encriptar = crypt($entrada["password"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

            $tabla = "usuarios";

            $item = "cedula";
            $valor = $entrada["cedula"];

            $respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla, $item, $valor);

            if (is_array($respuesta) && $respuesta["cedula"] == $entrada["cedula"] && $respuesta["password"] == $encriptar) {

                session_start();

                $_SESSION["iniciarSesion"] = "ok";
                $_SESSION["id"] = $respuesta["id"];
                $_SESSION["nombres"] = $respuesta["nombres"];
                $_SESSION["apellidos"] = $respuesta["apellidos"];
                $_SESSION["cedula"] = $respuesta["cedula"];

                /*=============================================
                REGISTRAR FECHA PARA SABER EL ÚLTIMO LOGIN
                =============================================*/

                date_default_timezone_set('America/Caracas');

                $fechaActual = date('Y-m-d H:i:s');

                $datos = array(
                    "id" => $respuesta["id"],
                    "status" => 1,
                    "fecha_ultimo_ingreso" => $fechaActual
                );

                $ultimoLogin = ModeloUsuarios::mdlEditarUsuario($tabla, $datos);

                if ($ultimoLogin === true) {
                    
                    return array(
                        "success" => true,
                        "status" => 200,
                        "mensaje" => "Ingreso exitoso",
                        "data" => array(
                            "id" => $_SESSION["id"],
                            "nombres" => $_SESSION["nombres"],
                            "apellidos" => $_SESSION["apellidos"],
                            "cedula" => $_SESSION["cedula"],
                            "status" => $_SESSION["iniciarSesion"]
                        )
                    );
                }
                
            } else {

                $_SESSION["iniciarSesion"] = null;
                $_SESSION["id"] = null;
                $_SESSION["nombres"] = null;
                $_SESSION["apellidos"] = null;
                $_SESSION["cedula"] = null;
                
                session_abort();
                
                return array(
                    "success" => false,
                    "status" => 401,
                    "mensaje" => "Usuario o contraseña incorrectos"
                );
            }
        }
	}

	/*=============================================
	CERRAR SESIÓN DE USUARIO
	=============================================*/

    static public function ctrLogoutUsuario() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] != "ok") {
        //     return array(
        //         "success" => false,
        //         "status" => 400,
        //         "mensaje" => "Debe iniciar sesión para cerrar sesión"
        //     );
        // }
        
        $idUsuario = $_SESSION["id"];
        
        $respuesta = ModeloUsuarios::mdlLogoutUsuario($idUsuario);

        session_unset();
        session_destroy();

        if ($respuesta === true) {
            
            $_SESSION["iniciarSesion"] = null;
            $_SESSION["id"] = null;
            $_SESSION["nombres"] = null;
            $_SESSION["apellidos"] = null;
            $_SESSION["cedula"] = null;
            
            return array(
                "success" => true,
                "status" => 200,
                "mensaje" => "Sesión cerrada con éxito"
            );
        } else {
            return array(
                "success" => false,
                "status" => 500,
                "mensaje" => "Error al cerrar sesión",
                "error" => $respuesta
            );
        }
    }

	/*=============================================
	REGISTRO DE USUARIO
	=============================================*/

	static public function ctrCrearUsuario($entrada)
	{

        $tabla = "usuarios";
        
        $encriptar = crypt($entrada["password"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

        $datos = array(
            "nombres" => ucfirst(trim($entrada["nombres"])),
            "apellidos" => ucfirst(trim($entrada["apellidos"])),
            "cedula" => trim($entrada["cedula"]),
            "password" => $encriptar
        );

        $respuesta = ModeloUsuarios::mdlCrearUsuario($tabla, $datos);

        if ($respuesta === true) {

            return array(
                "success" => true,
                "status" => 201,
                "mensaje" => "Usuario creado con éxito"
            );
        } else {

            return array(
                "success" => false,
                "status" => 500,
                "mensaje" => "Error al crear el usuario"
            );
        }
	}

	/*=============================================
	MOSTRAR USUARIO
	=============================================*/

	static public function ctrMostrarUsuarios($item, $valor)
	{

		$tabla = "usuarios";

		$respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla, $item, $valor);

		return array(
            "success" => true,
            "status" => 200,
            "data" => $respuesta
        );
	}

	/*=============================================
	EDITAR USUARIO
	=============================================*/

	static public function ctrEditarUsuario($entrada)
	{

        $tabla = "usuarios";

        if (isset($entrada["password"]) && !empty($entrada["password"])) {

            $entrada["password"] = crypt($entrada["password"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
        } 

        $entrada["fecha_modificacion"] = date('Y-m-d H:i:s');

        $respuesta = ModeloUsuarios::mdlEditarUsuario($tabla, $entrada);

        if ($respuesta === true) {

            return array(
                "success" => true,
                "status" => 200,
                "mensaje" => "Usuario editado con éxito"
            );
        } else {

            return array(
                "success" => false,
                "status" => 500,
                "mensaje" => "Error al editar el usuario",
                "error" => $respuesta
            );
        }
	}

	/*=============================================
	ELIMINAR USUARIO
	=============================================*/

	static public function ctrEliminarUsuario($id){

        $tabla ="usuarios";
        $datos = $id;

        $respuesta = ModeloUsuarios::mdlEliminarUsuario($tabla, $datos);

        if($respuesta == true){

            return array(
                "success" => true,
                "status" => 200,
                "mensaje" => "Usuario eliminado con éxito"
            );
        }else{
            return array(
                "success" => false,
                "status" => 500,
                "mensaje" => "Error al eliminar el usuario"
            );
        }
    }
}
