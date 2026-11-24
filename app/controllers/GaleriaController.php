<?php

namespace App\Controllers;

use Core\CrudController;
use PDO;
use App\Models\GaleriaModel;

class GaleriaController extends CrudController
{
    public function __construct(PDO $db)
    {
        parent::__construct(new GaleriaModel($db), 'Galería');
    }
}
