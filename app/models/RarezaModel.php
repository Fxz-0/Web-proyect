<?php
declare(strict_types=1);

namespace App\Models;

use Core\BaseModel;

class RarezaModel extends BaseModel
{
    protected string $table = 'SIG_Rarezas';
    protected string $primaryKey = 'rareza_id';

    protected array $fillable = ["nombre_rareza", "descripcion"];
    protected array $required = ["nombre_rareza"];
    protected array $types = ['nombre_rareza' => 'string', 'descripcion' => 'string'];

    protected array $maxLengths = ['nombre_rareza' => 50, 'descripcion' => 200];
}
