<?php

class ControladorGuardias
{

    /*=============================================
    REGISTRO DE GUARDIA
    =============================================*/

    static public function ctrCrearGuardia($entrada)
    {

        $tabla = "guardias";
        
        $datos = array(
            "id_sala" => $entrada["id_sala"],
            "id_usuario" => $_SESSION["id"], // Usar el ID del usuario autenticado
            "inicio_guardia" => $entrada["inicio_guardia"]
        );

        $respuesta = ModeloGuardias::mdlCrearGuardia($tabla, $datos);

        if ($respuesta === true) {

            return array(
                "success" => true,
                "status" => 201,
                "mensaje" => "Guardia creada con éxito"
            );
        } else {

            return array(
                "success" => false,
                "status" => 500,
                "mensaje" => "Error al crear la guardia"
            );
        }
    }

	/*=============================================
	MOSTRAR GUARDIA
	=============================================*/

	static public function ctrMostrarGuardia($item, $valor)
	{

		$tabla = "guardias";

		$respuesta = ModeloGuardias::mdlMostrarGuardias($tabla, $item, $valor);

		return array(
            "success" => true,
            "status" => 200,
            "data" => $respuesta
        );
	}

	/*=============================================
	EDITAR GUARDIA
	=============================================*/

	static public function ctrEditarGuardia($entrada)
	{

        $tabla = "guardias";

        $respuesta = ModeloGuardias::mdlEditarGuardia($tabla, $entrada);

        if ($respuesta === true) {

            return array(
                "success" => true,
                "status" => 200,
                "mensaje" => "Guardia actualizada con éxito"
            );
        } else {

            return array(
                "success" => false,
                "status" => 500,
                "mensaje" => "Error al actualizar la guardia",
                "error" => $respuesta
            );
        }
	}
}
