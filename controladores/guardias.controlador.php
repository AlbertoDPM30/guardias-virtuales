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
            "id_usuario" => $entrada["id_usuario"]
        );

        $respuesta = ModeloGuardias::mdlCrearGuardia($tabla, $datos);

        if ($respuesta !== false) {

            return array(
                "success" => true,
                "status" => 201,
                "mensaje" => "Guardia creada con éxito",
                "data" => $respuesta, // Retornamos el ID de la guardia creada
                "inicio" => $datos["fecha_inicio"] // Enviar la fecha de inicio para el temporizador
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

        // Verificar si se solicita calcular la duración
        if (isset($entrada["duracion"]) && $entrada["duracion"] == true) {
            
            // Obtener la fecha de inicio del registro (desde la BD usando el ID)
            $guardiaActual = ModeloGuardias::mdlMostrarGuardias($tabla, "id", $entrada["id"]);
            $fechaInicio = new DateTime($guardiaActual["fecha_inicio"]);
            $fechaFin = new DateTime(); // Ahora mismo
            
            // Calcular la diferencia
            $intervalo = $fechaInicio->diff($fechaFin);
            
            // Formatear para MySQL tipo TIME (HH:MM:SS)
            // %H permite más de 24 horas si fuera necesario
            $tiempoTranscurrido = $intervalo->format('%H:%I:%S');
            
            // Inyectamos el resultado en el array que se enviará al Modelo
            $entrada["duracion"] = $tiempoTranscurrido;
        }

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
