<?php

namespace App\Controllers;

use Core\CrudController;
use PDO;
use App\Models\ItemModel;

class ItemController extends CrudController
{
    public function __construct(PDO $db)
    {
        parent::__construct(new ItemModel($db), 'Item');
    }
}
