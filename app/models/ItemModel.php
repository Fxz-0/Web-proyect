<?php

namespace App\Models;

use Core\BaseModel;

class ItemModel extends BaseModel
{
    protected string $table = 'SIG_Items';
    protected string $primaryKey = 'item_id';

    protected array $fillable = ["inventario_id", "rareza_id", "nombre_item", "imagen_url", "es_tradeable", "es_marketable"];
    protected array $required = ["inventario_id", "rareza_id", "nombre_item"];
    protected array $types = ['inventario_id' => 'int', 'rareza_id' => 'int', 'nombre_item' => 'string', 'imagen_url' => 'string', 'es_tradeable' => 'bool', 'es_marketable' => 'bool'];
}
