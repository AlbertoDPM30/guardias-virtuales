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

            date_default_timezone_set('America/Caracas');
            $fechaInicio = date("Y-m-d H:i:s"); // Obtener la fecha y hora actual

            return array(
                "success" => true,
                "status" => 201,
                "mensaje" => "Guardia creada con éxito",
                "data" => $respuesta, // Retornamos el ID de la guardia creada
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
        date_default_timezone_set('America/Caracas');
        $tz = new DateTimeZone('America/Caracas');

        if (isset($entrada["final_guardia"]) && !empty($entrada["final_guardia"])) {
            
            $guardiaActual = ModeloGuardias::mdlMostrarGuardias($tabla, "id", $entrada["id"]);
            
            //  Objetos DateTime asegurando la zona horaria de Caracas
            $fechaInicio = new DateTime($guardiaActual["inicio_guardia"], $tz);
            $fechaFin = new DateTime($entrada["final_guardia"], $tz); 
            
            //  Calcular la diferencia
            $intervalo = $fechaInicio->diff($fechaFin);
            
            //  Calcular horas totales (por si pasa de 24h)
            $horasTotales = ($intervalo->days * 24) + $intervalo->h;
            
            //  Formatear el resultado final
            $tiempoTranscurrido = sprintf('%02d:%02d:%02d', $horasTotales, $intervalo->i, $intervalo->s);
            
            $entrada["duracion"] = $tiempoTranscurrido;
        }

        $respuesta = ModeloGuardias::mdlEditarGuardia($tabla, $entrada);

        if ($respuesta === true) {

            return array(
                "success" => true,
                "status" => 200,
                "mensaje" => "Guardia actualizada con éxito",
                "tiempo_total" => $entrada["duracion"] ?? null
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
