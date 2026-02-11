<?php

require_once "conexion.php";

class ModeloUsuarios {

    /*=============================================
    MOSTRAR USUARIOS (GET)
    =============================================*/
    static public function mdlMostrarUsuarios($tabla, $item, $valor) {
        try {
            if ($item != null) {
                // Obtener un usuario específico
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :valor");
                $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
            } else {
                // Obtener todos los usuarios
                $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY nombres ASC");
            }

            $stmt->execute();

            if ($item != null) {
                return $stmt->fetch(PDO::FETCH_ASSOC); 
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC); 
            }

        } catch (PDOException $e) {
            error_log("Error en mdlMostrarUsuarios: " . $e->getMessage());
            return false; // Retorna false en caso de error
        } finally {
            if ($stmt) {
                $stmt = null; // Asegura que el statement se cierre
            }
        }
    }

    /*=============================================
    REGISTRO DE USUARIO (POST)
    =============================================*/
    static public function mdlLogoutUsuario($id) {
        try {

            $stmt = Conexion::conectar()->prepare("UPDATE usuarios SET status = 0 WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            session_start();
            session_unset();
            session_destroy();
            return true; // Retorna true si el logout fue exitoso

        } catch (Exception $e) {
            error_log("Error en mdlLogoutUsuario: " . $e->getMessage());
            return $e->getMessage(); // Retorna el mensaje de error en caso de excepción
        }
    }

    /*=============================================
    REGISTRO DE USUARIO (POST)
    =============================================*/
    static public function mdlCrearUsuario ($tabla, $datos) {
        try {
            // Consulta SQL para insertar un nuevo usuario
            $stmt = Conexion::conectar()->prepare(
                "INSERT INTO 
                $tabla (nombres, apellidos, cedula, password, status) 
                VALUES (:nombres, :apellidos, :cedula, :password, :status)"
            );

            // Vincular los parámetros
            $stmt->bindParam(":nombres", $datos["nombres"], PDO::PARAM_STR);
            $stmt->bindParam(":apellidos", $datos["apellidos"], PDO::PARAM_STR);
            $stmt->bindParam(":cedula", $datos["cedula"], PDO::PARAM_INT);
            $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
            $stmt->bindParam(":status", $datos["status"], PDO::PARAM_INT);

            // Ejecutar la consulta SQL
            if ($stmt->execute()) {
                return true; // Retornar True si la inserción fue exitosa
            } else {
                error_log("Error al crear usuario: " . implode(" ", $stmt->errorInfo()));
                return false; // Retornar False si hubo un problema
            }

        } catch (PDOException $e) {
            error_log("Error en mdlCrearUsuario: " . $e->getMessage());
            return false; // Retornar False en caso de excepción
        } finally {
            if ($stmt) {
                $stmt = null; // Cerrar la conexión y liberar recursos
            }
        }
    }

    /*=============================================
    ACTUALIZAR USUARIO (PUT)
    =============================================*/
    static public function mdlEditarUsuario($tabla, $datos) {
        try {

            // Agregar "id" si no está presente
            if (!isset($datos['id'])) {
                error_log("Error en mdlEditarUsuario: 'id' no está presente en los datos.");
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
            if (empty($setClauses) && !isset($datos['fecha_modificacion'])) {
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
                error_log("Error al actualizar usuario: " . implode(" ", $stmt->errorInfo()));
                return implode(" ", $stmt->errorInfo()); // Retornar False si hubo un problema
            }

        } catch (PDOException $e) {
            error_log("Error en mdlEditarUsuario: " . $e->getMessage());
            return $e->getMessage(); // Retornar False si hubo un problema
        } finally {
            $stmt = null;
            // if ($stmt) {
            // }
        }
    }

    /*=============================================
    ELIMINAR USUARIO (DELETE)
    =============================================*/
    static public function mdlEliminarUsuario($tabla, $id) {
        try {
            $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true; // Retornar True si la eliminación fue exitosa
            } else {
                error_log("Error al eliminar usuario: " . implode(" ", $stmt->errorInfo()));
                return false; // Retornar False si hubo un problema
            }

        } catch (PDOException $e) {
            error_log("Error en mdlEliminarUsuario: " . $e->getMessage());
            return false; // Retornar False si hubo un problema
        } finally {
            if ($stmt) {
                $stmt = null;
            }
        }
    }

}