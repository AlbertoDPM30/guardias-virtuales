<?php

require_once "conexion.php";

class ModeloGuardias {

    /*=============================================
    MOSTRAR GUARDIAS (GET)
    =============================================*/
    static public function mdlMostrarGuardias($tabla, $item, $valor) {
        try {
            if ($item != null) {
                // Obtener una guardia específica
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :valor");
                $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
            } else {
                // Obtener todas las guardias
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id ASC");
            }

            $stmt->execute();

            if ($item != null && $item === "id") {
                return $stmt->fetch(PDO::FETCH_ASSOC); 
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC); 
            }

        } catch (PDOException $e) {
            error_log("Error en mdlMostrarGuardias: " . $e->getMessage());
            return false; // Retorna false en caso de error
        } finally {
            if ($stmt) {
                $stmt = null; // Asegura que el statement se cierre
            }
        }
    }

    /*=============================================
    REGISTRO DE GUARDIA (POST)
    =============================================*/
    static public function mdlCrearGuardia ($tabla, $datos) {
        try {
            // Consulta SQL para insertar un nuevo guardia
            $stmt = Conexion::conectar()->prepare(
                "INSERT INTO 
                $tabla (id_sala, id_usuario, inicio_guardia) 
                VALUES (:id_sala, :id_usuario, :inicio_guardia)"
            );

            // Vincular los parámetros
            $stmt->bindParam(":id_sala", $datos["id_sala"], PDO::PARAM_INT);
            $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
            $stmt->bindParam(":inicio_guardia", $datos["inicio_guardia"], PDO::PARAM_STR);

            // Ejecutar la consulta SQL
            if ($stmt->execute()) {
                return true; // Retornar True si la inserción fue exitosa
            } else {
                error_log("Error al crear guardia: " . implode(" ", $stmt->errorInfo()));
                return false; // Retornar False si hubo un problema
            }

        } catch (PDOException $e) {
            error_log("Error en mdlCrearGuardia: " . $e->getMessage());
            return false; // Retornar False en caso de excepción
        } finally {
            if ($stmt) {
                $stmt = null; // Cerrar la conexión y liberar recursos
            }
        }
    }

    /*=============================================
    ACTUALIZAR GUARDIA (PUT)
    =============================================*/
    static public function mdlEditarGuardia($tabla, $datos) {
        try {

            // Agregar "id" si no está presente
            if (!isset($datos['id'])) {
                error_log("Error en mdlEditarGuardia: 'id' no está presente en los datos.");
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
            error_log("Error en mdlEditarGuardia: " . $e->getMessage());
            return $e->getMessage(); // Retornar False si hubo un problema
        } finally {
            $stmt = null;
        }
    }

    /*=============================================
    ELIMINAR GUARDIA (DELETE)
    =============================================*/
    static public function mdlEliminarGuardia($tabla, $id) {
        try {
            $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true; // Retornar True si la eliminación fue exitosa
            } else {
                error_log("Error al eliminar guardia: " . implode(" ", $stmt->errorInfo()));
                return false; // Retornar False si hubo un problema
            }

        } catch (PDOException $e) {
            error_log("Error en mdlEliminarGuardia: " . $e->getMessage());
            return false; // Retornar False si hubo un problema
        } finally {
            if ($stmt) {
                $stmt = null;
            }
        }
    }

}