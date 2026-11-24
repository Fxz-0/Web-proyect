<?php

namespace App\Controllers;

use Core\CrudController;
use PDO;
use App\Models\JugadorSteamModel;

class JugadorSteamController extends CrudController
{
    public function __construct(PDO $db)
    {
        parent::__construct(new JugadorSteamModel($db), 'Jugador Steam');
    }
}
