<?php

namespace App\Models;

use Core\BaseModel;

class GaleriaModel extends BaseModel
{
    protected string $table = 'SIG_Galerias';
    protected string $primaryKey = 'galeria_id';

    protected array $fillable = ["usuario_id", "nombre_galeria", "descripcion", "estado"];
    protected array $required = ["usuario_id", "nombre_galeria"];
    protected array $types = ['usuario_id' => 'int', 'nombre_galeria' => 'string', 'descripcion' => 'string', 'estado' => 'bool'];
}
