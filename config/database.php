<?php

namespace Config;

use PDO;
use PDOException;

class Database
{
    public static function connect(): PDO
    {
        $host = getenv('DB_HOST') ?: 'localhost';
        $port = getenv('DB_PORT') ?: '1433';
        $database = getenv('DB_NAME') ?: 'SIG_DB';
        $user = getenv('DB_USER') ?: '';
        $password = getenv('DB_PASSWORD') ?: '';

        $encrypt = (getenv('DB_ENCRYPT') ?: 'no') === 'yes' ? '1' : '0';
        $trustCertificate = (getenv('DB_TRUST_CERTIFICATE') ?: 'yes') === 'yes' ? '1' : '0';

        $dsn = "sqlsrv:Server={$host},{$port};Database={$database};Encrypt={$encrypt};TrustServerCertificate={$trustCertificate}";

        try {
            return new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            error_log('Error de conexión a SQL Server: ' . $e->getMessage());
            throw new PDOException('No se pudo conectar con la base de datos.');
        }
    }
}
