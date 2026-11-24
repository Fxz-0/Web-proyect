<?php

namespace App\Models;

use Core\BaseModel;

class InventarioModel extends BaseModel
{
    protected string $table = 'SIG_Inventarios';
    protected string $primaryKey = 'inventario_id';

    protected array $fillable = ["jugador_id", "juego", "estado"];
    protected array $required = ["jugador_id", "juego"];
    protected array $types = ['jugador_id' => 'int', 'juego' => 'string', 'estado' => 'string'];
}
