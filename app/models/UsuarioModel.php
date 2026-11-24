<?php

namespace App\Models;

use Core\BaseModel;

class UsuarioModel extends BaseModel
{
    protected string $table = 'SIG_Usuarios';
    protected string $primaryKey = 'usuario_id';

    protected array $fillable = ["nombre_usuario", "correo", "estado"];
    protected array $required = ["nombre_usuario", "correo"];
    protected array $types = ['nombre_usuario' => 'string', 'correo' => 'string', 'estado' => 'string'];
}
