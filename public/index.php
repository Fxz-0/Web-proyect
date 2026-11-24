<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/config/bootstrap.php';

use Config\Database;
use Core\Response;
use Core\Router;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

try {
    $db = Database::connect();
    $router = new Router();

    $registerRoutes = require dirname(__DIR__) . '/routes/api.php';
    $registerRoutes($router, $db);

    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    error_log('Error de inicio: ' . $e->getMessage());
    Response::error('No fue posible iniciar la API. Revisa la configuración de conexión.', 500);
}
