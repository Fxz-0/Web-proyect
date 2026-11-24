<?php
declare(strict_types=1);

namespace App\Models;

use Core\BaseModel;

class JugadorSteamModel extends BaseModel
{
    protected string $table = 'SIG_JugadoresSteam';
    protected string $primaryKey = 'jugador_id';

    protected array $fillable = ["steam_id", "nombre_steam", "avatar_url", "perfil_url"];
    protected array $required = ["steam_id", "nombre_steam"];
    protected array $types = ['steam_id' => 'string', 'nombre_steam' => 'string', 'avatar_url' => 'string', 'perfil_url' => 'string'];

    protected array $maxLengths = ['steam_id' => 30, 'nombre_steam' => 100, 'avatar_url' => 500, 'perfil_url' => 500];
    protected array $formats = ['avatar_url' => 'url', 'perfil_url' => 'url'];

    /** Usa el procedimiento almacenado sp_buscar_jugador. */
    public function buscarPorSteamId(string $steamId): array|false
    {
        $stmt = $this->db->prepare('EXEC sp_buscar_jugador @steam_id = :steam_id');
        $stmt->execute(['steam_id' => $steamId]);
        return $stmt->fetch();
    }
}
