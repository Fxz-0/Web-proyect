<?php

namespace Core;

use PDO;

abstract class BaseModel
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey;
    protected array $fillable = [];
    protected array $required = [];
    protected array $types = [];

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function fillable(): array
    {
        return $this->fillable;
    }

    public function required(): array
    {
        return $this->required;
    }

    public function types(): array
    {
        return $this->types;
    }

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
        $columns = array_keys($data);
        $columnList = implode(', ', $columns);
        $parameterList = ':' . implode(', :', $columns);

        $sql = "INSERT INTO {$this->table} ({$columnList}) VALUES ({$parameterList})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        $idQuery = $this->db->query('SELECT CAST(SCOPE_IDENTITY() AS BIGINT)');
        return (string) $idQuery->fetchColumn();
    }

    public function update(int $id, array $data): int
    {
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
}
