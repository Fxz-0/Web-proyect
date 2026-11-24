<?php

namespace App\Controllers;

use Core\CrudController;
use PDO;
use App\Models\InventarioModel;

class InventarioController extends CrudController
{
    public function __construct(PDO $db)
    {
        parent::__construct(new InventarioModel($db), 'Inventario');
    }
}
