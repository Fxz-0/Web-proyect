<?php

namespace Core;

use PDOException;
use Throwable;

abstract class CrudController
{
    protected BaseModel $model;
    protected string $entityName;

    public function __construct(BaseModel $model, string $entityName)
    {
        $this->model = $model;
        $this->entityName = $entityName;
    }

    public function index(): void
    {
        try {
            Response::json([
                'ok' => true,
                'datos' => $this->model->all()
            ]);
        } catch (Throwable $e) {
            $this->serverError($e);
        }
    }

    public function show(string $id): void
    {
        $idNumber = $this->validId($id);
        if ($idNumber === null) {
            Response::error('El identificador debe ser un número entero positivo.', 400);
            return;
        }

        try {
            $record = $this->model->find($idNumber);

            if (!$record) {
                Response::error($this->entityName . ' no encontrado.', 404);
                return;
            }

            Response::json(['ok' => true, 'datos' => $record]);
        } catch (Throwable $e) {
            $this->serverError($e);
        }
    }

    public function store(): void
    {
        $data = $this->readJson();
        if ($data === null) {
            return;
        }

        $validated = $this->validateData($data, true);
        if ($validated === null) {
            return;
        }

        try {
            $id = $this->model->create($validated);
            Response::json([
                'ok' => true,
                'mensaje' => $this->entityName . ' registrado correctamente.',
                'id' => $id
            ], 201);
        } catch (PDOException $e) {
            $this->databaseError($e);
        } catch (Throwable $e) {
            $this->serverError($e);
        }
    }

    public function update(string $id): void
    {
        $idNumber = $this->validId($id);
        if ($idNumber === null) {
            Response::error('El identificador debe ser un número entero positivo.', 400);
            return;
        }

        $data = $this->readJson();
        if ($data === null) {
            return;
        }

        $validated = $this->validateData($data, false);
        if ($validated === null) {
            return;
        }

        try {
            if (!$this->model->find($idNumber)) {
                Response::error($this->entityName . ' no encontrado.', 404);
                return;
            }

            $this->model->update($idNumber, $validated);
            Response::json([
                'ok' => true,
                'mensaje' => $this->entityName . ' actualizado correctamente.'
            ]);
        } catch (PDOException $e) {
            $this->databaseError($e);
        } catch (Throwable $e) {
            $this->serverError($e);
        }
    }

    public function destroy(string $id): void
    {
        $idNumber = $this->validId($id);
        if ($idNumber === null) {
            Response::error('El identificador debe ser un número entero positivo.', 400);
            return;
        }

        try {
            if (!$this->model->find($idNumber)) {
                Response::error($this->entityName . ' no encontrado.', 404);
                return;
            }

            $this->model->delete($idNumber);
            Response::json([
                'ok' => true,
                'mensaje' => $this->entityName . ' eliminado correctamente.'
            ]);
        } catch (PDOException $e) {
            $this->databaseError($e);
        } catch (Throwable $e) {
            $this->serverError($e);
        }
    }

    private function readJson(): ?array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!is_array($data) || $data === []) {
            Response::error('Envía un objeto JSON con los datos requeridos.', 400);
            return null;
        }

        return $data;
    }

    private function validateData(array $data, bool $isCreate): ?array
    {
        $fillable = $this->model->fillable();
        $required = $isCreate ? $this->model->required() : [];
        $types = $this->model->types();

        foreach ($data as $field => $value) {
            if (!in_array($field, $fillable, true)) {
                Response::error("El campo '{$field}' no está permitido.", 400);
                return null;
            }

            if (in_array($field, $required, true) && ($value === null || $value === '')) {
                Response::error("El campo '{$field}' es obligatorio.", 400);
                return null;
            }

            if ($value === null) {
                continue;
            }

            $type = $types[$field] ?? 'string';

            if ($type === 'int' && filter_var($value, FILTER_VALIDATE_INT) === false) {
                Response::error("El campo '{$field}' debe ser un número entero.", 400);
                return null;
            }

            if ($type === 'bool' && !in_array($value, [true, false, 0, 1, '0', '1'], true)) {
                Response::error("El campo '{$field}' debe ser verdadero o falso (0/1).", 400);
                return null;
            }

            if ($type === 'string' && !is_string($value)) {
                Response::error("El campo '{$field}' debe ser texto.", 400);
                return null;
            }

            if ($type === 'int') {
                $data[$field] = (int) $value;
            }

            if ($type === 'bool') {
                $data[$field] = (bool) $value;
            }
        }

        foreach ($required as $field) {
            if (!array_key_exists($field, $data) || $data[$field] === null || $data[$field] === '') {
                Response::error("El campo '{$field}' es obligatorio.", 400);
                return null;
            }
        }

        return $data;
    }

    private function validId(string $id): ?int
    {
        if (!ctype_digit($id) || (int) $id < 1) {
            return null;
        }

        return (int) $id;
    }

    private function databaseError(PDOException $e): void
    {
        error_log('Error SQL: ' . $e->getMessage());
        $sqlState = (string) $e->getCode();

        if ($sqlState === '23000' || $sqlState === '23505') {
            Response::error('No se pudo completar la operación porque el registro está duplicado o relacionado con otros datos.', 409);
            return;
        }

        Response::error('Ocurrió un problema al procesar los datos en la base de datos.', 500);
    }

    private function serverError(Throwable $e): void
    {
        error_log('Error API: ' . $e->getMessage());
        Response::error('Ocurrió un error interno en el servidor.', 500);
    }
}
