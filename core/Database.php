<?php
declare(strict_types=1);

namespace Core;

use PDO;

final class Database
{
    private static ?PDO $pdo = null;

    /** Carga las variables del archivo .env (que no se sube al repositorio). */
    public static function loadEnv(string $file): void
    {
        if (!is_file($file)) {
            return;
        }
        foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) {
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\"'");
            if (getenv($key) === false) {
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
            }
        }
    }

    public static function env(string $key, ?string $default = null): ?string
    {
        $value = $_ENV[$key] ?? getenv($key);
        return ($value === false || $value === null || $value === '') ? $default : (string) $value;
    }

    public static function connection(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $dsn = sprintf(
            'sqlsrv:Server=%s,%s;Database=%s;Encrypt=%d;TrustServerCertificate=%d',
            self::env('DB_HOST', 'localhost'),
            self::env('DB_PORT', '1433'),
            self::env('DB_NAME', 'SIG_DB'),
            self::env('DB_ENCRYPT', 'no') === 'yes' ? 1 : 0,
            self::env('DB_TRUST_CERTIFICATE', 'yes') === 'yes' ? 1 : 0
        );

        self::$pdo = new PDO($dsn, self::env('DB_USER', ''), self::env('DB_PASSWORD', ''), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
        ]);

        return self::$pdo;
    }
}
