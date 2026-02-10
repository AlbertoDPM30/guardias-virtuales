<?php

class ControladorUsuarios
{

	/*=============================================
	INGRESO DE USUARIO
	=============================================*/

	static public function ctrIngresoUsuario()
	{

        if (preg_match('/^[0-9]+$/', $_POST["ingUsuario"])) {

            $encriptar = crypt($_POST["ingPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

            $tabla = "usuarios";

            $item = "cedula";
            $valor = $_POST["ingUsuario"];

            $respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla, $item, $valor);

            if (is_array($respuesta) && $respuesta["cedula"] == $_POST["ingUsuario"] && $respuesta["password"] == $encriptar) {

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

                $ultimoLogin = ModeloUsuarios::mdlActualizarUsuario($tabla, $datos);

                if ($ultimoLogin === true) {
                    
                    return array(
                        "success" => true,
                        "status" => 200,
                        "mensaje" => "Ingreso exitoso"
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
	REGISTRO DE USUARIO
	=============================================*/

	static public function ctrCrearUsuario()
	{

        $tabla = "usuarios";
        
        $encriptar = crypt($_POST["nuevoPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

        $datos = array(
            "nombres" => strtoupper(trim($_POST["nuevoNombres"])),
            "apellidos" => strtoupper(trim($_POST["nuevoApellidos"])),
            "cedula" => trim($_POST["nuevoCedula"]),
            "password" => $encriptar
        );

        $respuesta = ModeloUsuarios::mdlIngresarUsuario($tabla, $datos);

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

		return $respuesta;
	}

	/*=============================================
	EDITAR USUARIO
	=============================================*/

	static public function ctrEditarUsuario()
	{

        $tabla = "usuarios";

        if ($_POST["editarPassword"] != "") {

            $encriptar = crypt($_POST["editarPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
        } else {

            
            $encriptar = $_POST["passwordActual"];
        }

        $fechaActual = date('Y-m-d H:i:s');


        $datos = array(
            "id" => $_POST["idUsuario"],
            "nombres" => $_POST["editarNombres"],
            "usuario" => strtolower($_POST["editarUsuario"]),
            "email" => strtolower($_POST["editarEmail"]),
            "password" => $encriptar,
            "fecha_actualizacion" => $fechaActual
        );

        $respuesta = ModeloUsuarios::mdlEditarUsuario($tabla, $datos);

        if ($respuesta == "ok") {

            return array(
                "success" => true,
                "status" => 200,
                "mensaje" => "Usuario editado con éxito"
            );
        } else {

            return array(
                "success" => false,
                "status" => 500,
                "mensaje" => "Error al editar el usuario"
            );
        }
	}

	/*=============================================
	ELIMINAR USUARIO
	=============================================*/

	static public function ctrEliminarUsuario(){

        $tabla ="usuarios";
        $datos = $_POST["idEliminarUsuario"];

        $respuesta = ModeloUsuarios::mdlEliminarUsuario($tabla, $datos);

        if($respuesta == "ok"){

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
