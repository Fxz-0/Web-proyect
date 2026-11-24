# SIG API - Steam Inventory Gallery

API REST desarrollada en PHP con arquitectura MVC y conexión a SQL Server.

## Requisitos

- PHP 8.1 o superior.
- Composer.
- SQL Server.
- Extensiones PHP `pdo` y `pdo_sqlsrv`.
- Postman o Thunder Client.

## 1. Preparar la base de datos

1. Abre SQL Server Management Studio.
2. Ejecuta `database/SIG_DB_PA4.sql`.
3. Verifica que se hayan creado las siete tablas de SIG.

El script crea las tablas que falten y agrega datos de prueba sin borrar la base de datos existente.

## 2. Configurar la conexión

1. Copia `.env.example` y renombra la copia a `.env`.
2. Completa los datos de tu SQL Server.
3. No subas `.env` a GitHub. Está incluido en `.gitignore`.

Ejemplo:

```env
DB_HOST=localhost
DB_PORT=1433
DB_NAME=SIG_DB
DB_USER=sa
DB_PASSWORD=tu_clave
DB_ENCRYPT=no
DB_TRUST_CERTIFICATE=yes
```

La conexión utiliza PDO_SQLSRV. Debes tener instalado el controlador de Microsoft para PHP y habilitada la extensión `pdo_sqlsrv`.

## 3. Instalar e iniciar

Desde la carpeta principal del proyecto:

```bash
composer install
composer dump-autoload
php -S localhost:8000 -t public
```

Abre `http://localhost:8000/api/usuarios` para comprobar el servicio.

## 4. Módulos y endpoints

Todos los módulos cuentan con GET, GET por ID, POST, PUT y DELETE:

| Módulo | Ruta |
|---|---|
| Usuarios | `/api/usuarios` |
| Jugadores Steam | `/api/jugadores-steam` |
| Inventarios | `/api/inventarios` |
| Rarezas | `/api/rarezas` |
| Items | `/api/items` |
| Galerías | `/api/galerias` |
| Items de galerías | `/api/galeria-items` |

Ejemplo para listar: `GET http://localhost:8000/api/items`

## 5. Ejemplos JSON para Postman

### Crear usuario
POST `/api/usuarios`

```json
{
  "nombre_usuario": "usuario_prueba",
  "correo": "prueba@gmail.com",
  "estado": "activo"
}
```

### Crear jugador Steam
POST `/api/jugadores-steam`

```json
{
  "steam_id": "76561198000000999",
  "nombre_steam": "Jugador de prueba",
  "avatar_url": "https://example.com/avatar.png",
  "perfil_url": "https://steamcommunity.com/profiles/76561198000000999"
}
```

### Crear inventario
POST `/api/inventarios`

```json
{
  "jugador_id": 1,
  "juego": "Dota 2",
  "estado": "publico"
}
```

### Crear rareza
POST `/api/rarezas`

```json
{
  "nombre_rareza": "Arcano",
  "descripcion": "Artículo de rareza arcana"
}
```

### Crear item
POST `/api/items`

```json
{
  "inventario_id": 1,
  "rareza_id": 1,
  "nombre_item": "Item de prueba",
  "imagen_url": "https://example.com/item.png",
  "es_tradeable": 1,
  "es_marketable": 1
}
```

### Crear galería
POST `/api/galerias`

```json
{
  "usuario_id": 1,
  "nombre_galeria": "Mi nueva galeria",
  "descripcion": "Galería de prueba",
  "estado": 1
}
```

### Agregar item a galería
POST `/api/galeria-items`

```json
{
  "galeria_id": 1,
  "item_id": 1
}
```

Para actualizar, envía un PUT con el ID y los campos que deseas modificar. Para eliminar, utiliza DELETE con el ID.

## 6. Códigos HTTP

- 200: consulta, actualización o eliminación correcta.
- 201: registro creado.
- 400: datos incorrectos o campos obligatorios faltantes.
- 404: registro o endpoint no encontrado.
- 409: registro duplicado o relacionado con otros datos.
- 500: error interno o problema de conexión.

## 7. Nota

La API administra los datos que SIG almacena en SQL Server. La consulta real de inventarios mediante Steam Web API se integraría posteriormente desde la aplicación.
