<?php
declare(strict_types=1);

namespace App\Models;

use Core\BaseModel;

class InventarioModel extends BaseModel
{
    protected string $table = 'SIG_Inventarios';
    protected string $primaryKey = 'inventario_id';

    protected array $fillable = ["jugador_id", "juego", "estado"];
    protected array $required = ["jugador_id"];
    protected array $types = ['jugador_id' => 'int', 'juego' => 'string', 'estado' => 'string'];

    protected array $maxLengths = ['juego' => 50, 'estado' => 15];
    protected array $enums = ['estado' => ['publico', 'privado']];

    public function items(int $id): array
    {
        $sql = 'SELECT it.item_id, it.nombre_item, ra.nombre_rareza, it.es_tradeable, it.es_marketable
                FROM SIG_Items it
                INNER JOIN SIG_Rarezas ra ON ra.rareza_id = it.rareza_id
                WHERE it.inventario_id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    }
}
