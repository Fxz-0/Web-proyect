<?php
declare(strict_types=1);

namespace Core;

use InvalidArgumentException;
use PDO;

abstract class BaseModel
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey;
    protected array $fillable = [];
    protected array $required = [];
    protected array $types = [];
    protected array $maxLengths = [];
    protected array $enums = [];
    protected array $formats = [];

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function fillable(): array   { return $this->fillable; }
    public function required(): array   { return $this->required; }
    public function types(): array      { return $this->types; }
    public function maxLengths(): array { return $this->maxLengths; }
    public function enums(): array      { return $this->enums; }
    public function formats(): array    { return $this->formats; }

    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): array|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    public function create(array $data): string
    {
        $data = $this->onlyFillable($data);
        if (!$data) {
            throw new InvalidArgumentException('No hay datos para insertar');
        }

        $columns = array_keys($data);
        $columnList = implode(', ', $columns);
        $parameterList = ':' . implode(', :', $columns);

        // OUTPUT devuelve el id en la misma sentencia (SCOPE_IDENTITY en otra consulta puede dar NULL con sqlsrv)
        $sql = "INSERT INTO {$this->table} ({$columnList})
                OUTPUT INSERTED.{$this->primaryKey}
                VALUES ({$parameterList})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        return (string) $stmt->fetchColumn();
    }

    public function update(int $id, array $data): int
    {
        $data = $this->onlyFillable($data);
        if (!$data) {
            throw new InvalidArgumentException('No hay datos para actualizar');
        }

        $assignments = [];
        foreach (array_keys($data) as $column) {
            $assignments[] = "{$column} = :{$column}";
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $assignments)
             . " WHERE {$this->primaryKey} = :_id";

        $data['_id'] = $id;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        return $stmt->rowCount();
    }

    public function delete(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount();
    }

    /** Solo deja pasar columnas permitidas (los nombres de columna nunca vienen del cliente). */
    private function onlyFillable(array $data): array
    {
        return array_intersect_key($data, array_flip($this->fillable));
    }
}
