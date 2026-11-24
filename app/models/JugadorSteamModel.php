<?php

namespace App\Models;

use Core\BaseModel;

class JugadorSteamModel extends BaseModel
{
    protected string $table = 'SIG_JugadoresSteam';
    protected string $primaryKey = 'jugador_id';

    protected array $fillable = ["steam_id", "nombre_steam", "avatar_url", "perfil_url"];
    protected array $required = ["steam_id", "nombre_steam"];
    protected array $types = ['steam_id' => 'string', 'nombre_steam' => 'string', 'avatar_url' => 'string', 'perfil_url' => 'string'];
}
