<?php

class ControladorHall
{

	/*=============================================
	REGISTRO DE SALA
	=============================================*/

	static public function ctrCrearHall($entrada)
	{

        $tabla = "salas";
        
        $datos = array("numero" => $entrada["numero"]);

        $respuesta = ModeloHall::mdlCrearHall($tabla, $datos);

        if ($respuesta === true) {

            return array(
                "success" => true,
                "status" => 201,
                "mensaje" => "Sala creada con éxito"
            );
        } else {

            return array(
                "success" => false,
                "status" => 500,
                "mensaje" => "Error al crear la sala"
            );
        }
	}

	/*=============================================
	MOSTRAR SALA
	=============================================*/

	static public function ctrMostrarHall($item, $valor)
	{

		$tabla = "salas";

		$respuesta = ModeloHall::MdlMostrarHalls($tabla, $item, $valor);

		return array(
            "success" => true,
            "status" => 200,
            "data" => $respuesta
        );
	}

	/*=============================================
	EDITAR SALA
	=============================================*/

	static public function ctrEditarHall($entrada)
	{

        $tabla = "salas";

        $entrada["fecha_actualizacion"] = date('Y-m-d H:i:s');

        $respuesta = ModeloHall::mdlEditarHall($tabla, $entrada);

        if ($respuesta === true) {

            return array(
                "success" => true,
                "status" => 200,
                "mensaje" => "Sala editada con éxito"
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
	ELIMINAR SALA
	=============================================*/

	static public function ctrEliminarHall($id){

        $tabla ="salas";
        $datos = $id;

        $respuesta = ModeloHall::mdlEliminarHall($tabla, $datos);

        if($respuesta == true){

            return array(
                "success" => true,
                "status" => 200,
                "mensaje" => "Sala eliminada con éxito"
            );
        }else{
            return array(
                "success" => false,
                "status" => 500,
                "mensaje" => "Error al eliminar la sala"
            );
        }
    }
}
