# Endpoints de SIG API

Base URL: `http://localhost:8000`

Cada módulo implementa:

| Método | Ruta | Función |
|---|---|---|
| GET | `/api/{modulo}` | Listar registros |
| GET | `/api/{modulo}/{id}` | Consultar por ID |
| POST | `/api/{modulo}` | Crear registro |
| PUT | `/api/{modulo}/{id}` | Actualizar registro |
| DELETE | `/api/{modulo}/{id}` | Eliminar registro |

## Módulos

- `/api/usuarios`
- `/api/jugadores-steam`
- `/api/inventarios`
- `/api/rarezas`
- `/api/items`
- `/api/galerias`
- `/api/galeria-items`

Los campos admitidos y ejemplos de JSON se encuentran en el README principal.
