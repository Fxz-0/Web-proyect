<?php
declare(strict_types=1);

use App\Models\{GaleriaItemModel, GaleriaModel, InventarioModel, ItemModel, JugadorSteamModel, RarezaModel, UsuarioModel};
use Core\{Database, Response, ResourceController, Validator};

spl_autoload_register(function (string $class): void {
    $map = ['Core\\' => __DIR__ . '/../core/', 'App\\' => __DIR__ . '/../app/'];
    foreach ($map as $prefix => $dir) {
        if (str_starts_with($class, $prefix)) {
            $file = $dir . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
            if (is_file($file)) {
                require $file;
            }
        }
    }
});

ini_set('display_errors', '0'); // nunca mostrar errores internos al cliente
error_reporting(E_ALL);
Database::loadEnv(__DIR__ . '/../.env');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$segments = array_values(array_filter(array_map('trim', explode('/', $path)), fn ($s) => $s !== ''));

$resources = [
    'usuarios'       => UsuarioModel::class,
    'jugadores'      => JugadorSteamModel::class,
    'inventarios'    => InventarioModel::class,
    'rarezas'        => RarezaModel::class,
    'items'          => ItemModel::class,
    'galerias'       => GaleriaModel::class,
    'galeria-items'  => GaleriaItemModel::class,
];

try {
    // Cuerpo JSON (solo POST y PUT)
    $body = [];
    if (in_array($method, ['POST', 'PUT'], true)) {
        $raw = (string) file_get_contents('php://input');
        if (trim($raw) !== '') {
            $body = json_decode($raw, true);
            if (!is_array($body)) {
                Response::error('El cuerpo de la petición debe ser un JSON válido (objeto)', 400);
            }
        }
    }

    if (!$segments) {
        Response::success(['api' => 'SIG_PA4'], 'API SIG_PA4 funcionando');
    }
    if ($segments[0] !== 'api') {
        Response::error('Ruta no encontrada', 404);
    }

    $rest = array_slice($segments, 1);
    $n = count($rest);

    // GET /api/health
    if ($rest === ['health']) {
        $db = Database::connection()->query('SELECT DB_NAME() AS db')->fetch();
        Response::success(['db' => $db['db']], 'Conexión a la base de datos correcta');
    }

    // GET /api/jugadores/buscar/{steamId}  (procedimiento almacenado)
    if ($n === 3 && $rest[0] === 'jugadores' && $rest[1] === 'buscar') {
        if ($method !== 'GET') {
            Response::error('Método no permitido', 405);
        }
        if (!ctype_digit($rest[2]) || strlen($rest[2]) > 30) {
            Response::error('El steam_id debe contener solo dígitos (máximo 30)', 400, ['steam_id' => $rest[2]]);
        }
        $jugador = (new JugadorSteamModel(Database::connection()))->buscarPorSteamId($rest[2]);
        if (!$jugador) {
            Response::error('No existe un jugador con ese steam_id', 404);
        }
        Response::success($jugador, 'Jugador encontrado');
    }

    // GET /api/galerias/{id}/items  y  GET /api/inventarios/{id}/items  (JOIN)
    if ($n === 3 && $rest[2] === 'items' && in_array($rest[0], ['galerias', 'inventarios'], true)) {
        if ($method !== 'GET') {
            Response::error('Método no permitido', 405);
        }
        $id = Validator::id($rest[1]);
        $model = new $resources[$rest[0]](Database::connection());
        if (!$model->find($id)) {
            Response::error("No existe un registro con id {$id}", 404);
        }
        Response::success($model->items($id), 'Items obtenidos correctamente');
    }

    // CRUD: /api/{recurso}  y  /api/{recurso}/{id}
    if ($n >= 1 && $n <= 2 && isset($resources[$rest[0]])) {
        $controller = new ResourceController(new $resources[$rest[0]](Database::connection()));
        $id = $rest[1] ?? null;

        if ($id === null) {
            if ($method === 'GET')  { $controller->index(); }
            if ($method === 'POST') { $controller->store($body); }
        } else {
            if ($method === 'GET')    { $controller->show($id); }
            if ($method === 'PUT')    { $controller->update($id, $body); }
            if ($method === 'DELETE') { $controller->destroy($id); }
        }
        Response::error('Método no permitido', 405);
    }

    Response::error('Ruta no encontrada', 404);
} catch (PDOException $e) {
    Response::fromPdo($e);
} catch (Throwable $e) {
    error_log('[ERROR] ' . $e->getMessage()); // el detalle solo queda en el log del servidor
    Response::error('Error interno del servidor', 500);
}
