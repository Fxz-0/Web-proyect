<?php
declare(strict_types=1);

namespace Core;

use PDOException;

final class Response
{
    public static function json(array $payload, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION);
        exit;
    }

    public static function success(mixed $data, string $message = 'OK', int $status = 200): never
    {
        self::json(['success' => true, 'message' => $message, 'data' => $data], $status);
    }

    public static function error(string $message, int $status, array $errors = []): never
    {
        $payload = ['success' => false, 'message' => $message];
        if ($errors) {
            $payload['errors'] = $errors;
        }
        self::json($payload, $status);
    }

    /** Traduce errores de SQL Server a códigos HTTP sin exponer detalles internos. */
    public static function fromPdo(PDOException $e): never
    {
        error_log('[PDO] ' . $e->getMessage()); // queda solo en el log del servidor
        $native = (int) ($e->errorInfo[1] ?? 0);

        if (in_array($native, [2627, 2601], true)) {
            self::error('Ya existe un registro con ese valor único', 409);
        }
        if ($native === 547) {
            self::error('Operación no permitida: viola una relación (clave foránea) o una restricción de la tabla', 409);
        }
        if (in_array($native, [515, 245, 8114, 2628], true)) {
            self::error('Datos inválidos para la base de datos', 400);
        }
        self::error('Error interno del servidor', 500);
    }
}
