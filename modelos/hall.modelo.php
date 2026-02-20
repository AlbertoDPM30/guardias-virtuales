<?php

require_once "conexion.php";

class ModeloHall {

    /*=============================================
    MOSTRAR SALAS (GET)
    =============================================*/
    static public function mdlMostrarHalls($tabla, $item, $valor) {
        try {
            if ($item != null) {
                // Obtener una sala específica
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :valor");
                $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
            } else {
                // Obtener todas las salas
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY numero ASC");
            }

            $stmt->execute();

            if ($item != null) {
                return $stmt->fetch(PDO::FETCH_ASSOC); 
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC); 
            }

        } catch (PDOException $e) {
            error_log("Error en mdlMostrarHalls: " . $e->getMessage());
            return false; // Retorna false en caso de error
        } finally {
            if ($stmt) {
                $stmt = null; // Asegura que el statement se cierre
            }
        }
    }

    /*=============================================
    REGISTRO DE SALA (POST)
    =============================================*/
    static public function mdlCrearHall ($tabla, $datos) {
        try {
            // Consulta SQL para insertar un nuevo usuario
            $stmt = Conexion::conectar()->prepare(
                "INSERT INTO 
                $tabla (numero) 
                VALUES (:numero)"
            );

            // Vincular los parámetros
            $stmt->bindParam(":numero", $datos["numero"], PDO::PARAM_STR);

            // Ejecutar la consulta SQL
            if ($stmt->execute()) {
                return true; // Retornar True si la inserción fue exitosa
            } else {
                error_log("Error al crear sala: " . implode(" ", $stmt->errorInfo()));
                return false; // Retornar False si hubo un problema
            }

        } catch (PDOException $e) {
            error_log("Error en mdlCrearHall: " . $e->getMessage());
            return false; // Retornar False en caso de excepción
        } finally {
            if ($stmt) {
                $stmt = null; // Cerrar la conexión y liberar recursos
            }
        }
    }

    /*=============================================
    ACTUALIZAR SALA (PUT)
    =============================================*/
    static public function mdlEditarHall($tabla, $datos) {
        try {

            // Agregar "id" si no está presente
            if (!isset($datos['id'])) {
                error_log("Error en mdlEditarHall: 'id' no está presente en los datos.");
                return "'id' no está presente en los datos.";
            }

            // Iniciar la construcción de la cláusula SET
            $setClauses = [];
            $bindParams = [];
            
            // Añadir los campos que vienen en $datos (excluyendo id)
            foreach ($datos as $key => $value) {
                if ($key !== 'id') {
                    $setClauses[] = "$key = :$key";
                    $bindParams[":$key"] = $value;
                }
            }

            // Si no hay campos para actualizar además del ID y updated_at, salir.
            if (empty($setClauses) && !isset($datos['fecha_actualizacion'])) {
                return "No hay campos para actualizar la fecha.";
            }

           // Construir la consulta SQL
            $sql = "UPDATE $tabla SET " . implode(", ", $setClauses) . " WHERE id = :id";
            $stmt = Conexion::conectar()->prepare($sql);

            // Vincular los parámetros dinámicamente
            foreach ($bindParams as $param => $value) {
                $paramType = PDO::PARAM_STR;
                if (is_int($value)) {
                    $paramType = PDO::PARAM_INT;
                } elseif (is_bool($value)) {
                    $paramType = PDO::PARAM_BOOL;
                } elseif (is_null($value)) {
                    $paramType = PDO::PARAM_NULL;
                }
                $stmt->bindValue($param, $value, $paramType);
            }

            // Vincular 'id' 
            $stmt->bindValue(":id", $datos['id'], PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return true; // Retornar True si la actualización fue exitosa
            } else {
                error_log("Error al actualizar sala: " . implode(" ", $stmt->errorInfo()));
                return implode(" ", $stmt->errorInfo()); // Retornar False si hubo un problema
            }

        } catch (PDOException $e) {
            error_log("Error en mdlEditarHall: " . $e->getMessage());
            return $e->getMessage(); // Retornar False si hubo un problema
        } finally {
            $stmt = null;
        }
    }

    /*=============================================
    ELIMINAR SALA (DELETE)
    =============================================*/
    static public function mdlEliminarHall($tabla, $id) {
        try {
            $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true; // Retornar True si la eliminación fue exitosa
            } else {
                error_log("Error al eliminar sala: " . implode(" ", $stmt->errorInfo()));
                return false; // Retornar False si hubo un problema
            }

        } catch (PDOException $e) {
            error_log("Error en mdlEliminarHall: " . $e->getMessage());
            return false; // Retornar False si hubo un problema
        } finally {
            if ($stmt) {
                $stmt = null;
            }
        }
    }

}