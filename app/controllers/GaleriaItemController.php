<?php

namespace App\Controllers;

use Core\CrudController;
use PDO;
use App\Models\GaleriaItemModel;

class GaleriaItemController extends CrudController
{
    public function __construct(PDO $db)
    {
        parent::__construct(new GaleriaItemModel($db), 'Artículo de galería');
    }
}
