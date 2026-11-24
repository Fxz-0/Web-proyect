<?php
declare(strict_types=1);

namespace App\Models;

use Core\BaseModel;

class GaleriaModel extends BaseModel
{
    protected string $table = 'SIG_Galerias';
    protected string $primaryKey = 'galeria_id';

    protected array $fillable = ["usuario_id", "nombre_galeria", "descripcion", "estado"];
    protected array $required = ["usuario_id", "nombre_galeria"];
    protected array $types = ['usuario_id' => 'int', 'nombre_galeria' => 'string', 'descripcion' => 'string', 'estado' => 'bool'];

    protected array $maxLengths = ['nombre_galeria' => 100, 'descripcion' => 300];

    public function items(int $id): array
    {
        $sql = 'SELECT g.galeria_id, g.nombre_galeria, it.item_id, it.nombre_item,
                       ra.nombre_rareza, it.es_tradeable, it.es_marketable, gi.fecha_agregado
                FROM SIG_GaleriaItems gi
                INNER JOIN SIG_Galerias g ON g.galeria_id = gi.galeria_id
                INNER JOIN SIG_Items it ON it.item_id = gi.item_id
                INNER JOIN SIG_Rarezas ra ON ra.rareza_id = it.rareza_id
                WHERE g.galeria_id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    }
}
