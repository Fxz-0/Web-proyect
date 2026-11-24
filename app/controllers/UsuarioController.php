<?php

namespace App\Controllers;

use Core\CrudController;
use PDO;
use App\Models\UsuarioModel;

class UsuarioController extends CrudController
{
    public function __construct(PDO $db)
    {
        parent::__construct(new UsuarioModel($db), 'Usuario');
    }
}
