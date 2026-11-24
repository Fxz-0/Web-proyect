<?php

use Core\Router;
use App\Controllers\UsuarioController;
use App\Controllers\JugadorSteamController;
use App\Controllers\InventarioController;
use App\Controllers\RarezaController;
use App\Controllers\ItemController;
use App\Controllers\GaleriaController;
use App\Controllers\GaleriaItemController;

return function (Router $router, PDO $db): void {

    // usuarios
    $Usuario = new UsuarioController($db);
    $router->get('/api/usuarios', [$Usuario, 'index']);
    $router->get('/api/usuarios/{id}', [$Usuario, 'show']);
    $router->post('/api/usuarios', [$Usuario, 'store']);
    $router->put('/api/usuarios/{id}', [$Usuario, 'update']);
    $router->delete('/api/usuarios/{id}', [$Usuario, 'destroy']);

    // jugadores-steam
    $JugadorSteam = new JugadorSteamController($db);
    $router->get('/api/jugadores-steam', [$JugadorSteam, 'index']);
    $router->get('/api/jugadores-steam/{id}', [$JugadorSteam, 'show']);
    $router->post('/api/jugadores-steam', [$JugadorSteam, 'store']);
    $router->put('/api/jugadores-steam/{id}', [$JugadorSteam, 'update']);
    $router->delete('/api/jugadores-steam/{id}', [$JugadorSteam, 'destroy']);

    // inventarios
    $Inventario = new InventarioController($db);
    $router->get('/api/inventarios', [$Inventario, 'index']);
    $router->get('/api/inventarios/{id}', [$Inventario, 'show']);
    $router->post('/api/inventarios', [$Inventario, 'store']);
    $router->put('/api/inventarios/{id}', [$Inventario, 'update']);
    $router->delete('/api/inventarios/{id}', [$Inventario, 'destroy']);

    // rarezas
    $Rareza = new RarezaController($db);
    $router->get('/api/rarezas', [$Rareza, 'index']);
    $router->get('/api/rarezas/{id}', [$Rareza, 'show']);
    $router->post('/api/rarezas', [$Rareza, 'store']);
    $router->put('/api/rarezas/{id}', [$Rareza, 'update']);
    $router->delete('/api/rarezas/{id}', [$Rareza, 'destroy']);

    // items
    $Item = new ItemController($db);
    $router->get('/api/items', [$Item, 'index']);
    $router->get('/api/items/{id}', [$Item, 'show']);
    $router->post('/api/items', [$Item, 'store']);
    $router->put('/api/items/{id}', [$Item, 'update']);
    $router->delete('/api/items/{id}', [$Item, 'destroy']);

    // galerias
    $Galeria = new GaleriaController($db);
    $router->get('/api/galerias', [$Galeria, 'index']);
    $router->get('/api/galerias/{id}', [$Galeria, 'show']);
    $router->post('/api/galerias', [$Galeria, 'store']);
    $router->put('/api/galerias/{id}', [$Galeria, 'update']);
    $router->delete('/api/galerias/{id}', [$Galeria, 'destroy']);

    // galeria-items
    $GaleriaItem = new GaleriaItemController($db);
    $router->get('/api/galeria-items', [$GaleriaItem, 'index']);
    $router->get('/api/galeria-items/{id}', [$GaleriaItem, 'show']);
    $router->post('/api/galeria-items', [$GaleriaItem, 'store']);
    $router->put('/api/galeria-items/{id}', [$GaleriaItem, 'update']);
    $router->delete('/api/galeria-items/{id}', [$GaleriaItem, 'destroy']);
};
