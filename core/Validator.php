<?php
declare(strict_types=1);

namespace Core;

final class Validator
{
    /** Valida un id recibido por URL. */
    public static function id(string $raw): int
    {
        if (!ctype_digit($raw) || (int) $raw < 1) {
            Response::error('El id debe ser un número entero positivo', 400, ['id' => $raw]);
        }
        return (int) $raw;
    }

    /**
     * Valida obligatorios, tipos, longitudes, valores permitidos y formatos.
     * $partial = true (PUT): solo valida los campos enviados.
     * Devuelve un arreglo campo => mensaje (vacío si todo está bien).
     */
    public static function validate(array $data, BaseModel $model, bool $partial = false): array
    {
        $errors = [];
        $required = $model->required();
        $types = $model->types();

        if (!$partial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data) || $data[$field] === null
                    || (is_string($data[$field]) && trim($data[$field]) === '')) {
                    $errors[$field] = 'El campo es obligatorio';
                }
            }
        }

        foreach ($data as $field => $value) {
            if (isset($errors[$field])) {
                continue;
            }
            if ($value === null) {
                if ($partial && in_array($field, $required, true)) {
                    $errors[$field] = 'El campo no puede ser nulo';
                }
                continue;
            }
            if (is_string($value) && trim($value) === '' && in_array($field, $required, true)) {
                $errors[$field] = 'El campo no puede estar vacío';
                continue;
            }

            $type = $types[$field] ?? 'string';
            $typeError = self::checkType($value, $type);
            if ($typeError !== null) {
                $errors[$field] = $typeError;
                continue;
            }

            if ($type === 'string') {
                $max = $model->maxLengths()[$field] ?? null;
                if ($max !== null && mb_strlen($value) > $max) {
                    $errors[$field] = "Máximo {$max} caracteres";
                    continue;
                }
                $enum = $model->enums()[$field] ?? null;
                if ($enum !== null && !in_array($value, $enum, true)) {
                    $errors[$field] = 'Valor no permitido. Valores válidos: ' . implode(', ', $enum);
                    continue;
                }
                $format = $model->formats()[$field] ?? null;
                if ($format === 'email' && filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
                    $errors[$field] = 'Debe ser un correo electrónico válido';
                } elseif ($format === 'url' && filter_var($value, FILTER_VALIDATE_URL) === false) {
                    $errors[$field] = 'Debe ser una URL válida';
                }
            }
        }

        return $errors;
    }

    /** Convierte los valores ya validados al tipo que espera la base de datos. */
    public static function normalize(array $data, array $types): array
    {
        foreach ($data as $field => $value) {
            if ($value === null) {
                continue;
            }
            $type = $types[$field] ?? 'string';
            $data[$field] = match ($type) {
                'int' => (int) $value,
                'bool' => ($value === true || $value === 1 || $value === '1') ? 1 : 0,
                'float', 'decimal' => (float) $value,
                default => trim((string) $value),
            };
        }
        return $data;
    }

    private static function checkType(mixed $value, string $type): ?string
    {
        return match ($type) {
            'int' => (is_bool($value) || filter_var($value, FILTER_VALIDATE_INT) === false)
                ? 'Debe ser un número entero' : null,
            'bool' => (is_bool($value) || in_array($value, [0, 1, '0', '1'], true))
                ? null : 'Debe ser booleano (true o false)',
            'float', 'decimal' => (is_bool($value) || !is_numeric($value))
                ? 'Debe ser un número' : null,
            default => is_string($value) ? null : 'Debe ser texto',
        };
    }
}
