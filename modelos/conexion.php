<?php

class Conexion
{

	static public function conectar()
	{

        $path = __DIR__ . '/../.env';
        if (!file_exists($path)) return;

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) continue;
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value, '"\' ');
        }

        try {
            $link = new PDO(
                "{$_ENV['DB_ENGINE']}:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}",
                $_ENV['DB_USER'],
                $_ENV['DB_PASS']
            );
            $link->exec("set names utf8");
            return $link;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
	}
}

?>