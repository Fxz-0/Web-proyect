<?php

namespace App\Controllers;

use Core\CrudController;
use PDO;
use App\Models\RarezaModel;

class RarezaController extends CrudController
{
    public function __construct(PDO $db)
    {
        parent::__construct(new RarezaModel($db), 'Rareza');
    }
}
