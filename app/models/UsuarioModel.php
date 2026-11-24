<?php
declare(strict_types=1);

namespace App\Models;

use Core\BaseModel;

class UsuarioModel extends BaseModel
{
    protected string $table = 'SIG_Usuarios';
    protected string $primaryKey = 'usuario_id';

    protected array $fillable = ["nombre_usuario", "correo", "estado"];
    protected array $required = ["nombre_usuario", "correo"];
    protected array $types = ['nombre_usuario' => 'string', 'correo' => 'string', 'estado' => 'string'];

    protected array $maxLengths = ['nombre_usuario' => 50, 'correo' => 100, 'estado' => 15];
    protected array $enums = ['estado' => ['activo', 'inactivo']];
    protected array $formats = ['correo' => 'email'];
}
