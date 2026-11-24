<?php
declare(strict_types=1);

namespace Core;

class ResourceController
{
    public function __construct(protected BaseModel $model)
    {
    }

    public function index(): never
    {
        Response::success($this->model->all(), 'Listado obtenido correctamente');
    }

    public function show(string $rawId): never
    {
        $id = Validator::id($rawId);
        $row = $this->model->find($id);
        if (!$row) {
            Response::error("No existe un registro con id {$id}", 404);
        }
        Response::success($row, 'Registro obtenido correctamente');
    }

    public function store(array $body): never
    {
        $data = $this->pick($body);

        $errors = Validator::validate($data, $this->model);
        if ($errors) {
            Response::error('Datos inválidos', 400, $errors);
        }

        $data = Validator::normalize($data, $this->model->types());
        $data = array_filter($data, fn ($v) => $v !== null);

        $id = (int) $this->model->create($data);
        $row = $this->model->find($id) ?: [];

        Response::success(['id' => $id] + $row, 'Registro creado correctamente', 201);
    }

    public function update(string $rawId, array $body): never
    {
        $id = Validator::id($rawId);
        $data = $this->pick($body);

        if (!$data) {
            Response::error('No se enviaron campos válidos para actualizar', 400, [
                'campos_permitidos' => $this->model->fillable(),
            ]);
        }

        $errors = Validator::validate($data, $this->model, true);
        if ($errors) {
            Response::error('Datos inválidos', 400, $errors);
        }

        if (!$this->model->find($id)) {
            Response::error("No existe un registro con id {$id}", 404);
        }

        $this->model->update($id, Validator::normalize($data, $this->model->types()));
        Response::success($this->model->find($id) ?: [], 'Registro actualizado correctamente');
    }

    public function destroy(string $rawId): never
    {
        $id = Validator::id($rawId);
        if ($this->model->delete($id) === 0) {
            Response::error("No existe un registro con id {$id}", 404);
        }
        Response::success(['id' => $id], 'Registro eliminado correctamente');
    }

    protected function pick(array $body): array
    {
        return array_intersect_key($body, array_flip($this->model->fillable()));
    }
}
