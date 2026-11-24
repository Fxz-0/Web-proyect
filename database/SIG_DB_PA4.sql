/* ============================================================
   SIG - STEAM INVENTORY GALLERY
   Alumno: Palacios Mendoza Christian Jesus
   NRC: 3657
   ============================================================ */

/* 1. CREACION DE LA BASE DE DATOS */
IF DB_ID('SIG_DB') IS NULL
    CREATE DATABASE SIG_DB;
GO
USE SIG_DB;
GO

/* 2. TABLAS */

IF OBJECT_ID('SIG_Usuarios', 'U') IS NULL
BEGIN
    CREATE TABLE SIG_Usuarios (
        usuario_id INT IDENTITY(1,1) PRIMARY KEY,
        nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
        correo VARCHAR(100) NOT NULL UNIQUE,
        fecha_registro DATETIME2 NOT NULL DEFAULT SYSDATETIME(),
        estado VARCHAR(15) NOT NULL DEFAULT 'activo',
        CONSTRAINT chk_usuario_estado CHECK (estado IN ('activo', 'inactivo'))
    );
END;
GO

IF OBJECT_ID('SIG_JugadoresSteam', 'U') IS NULL
BEGIN
    CREATE TABLE SIG_JugadoresSteam (
        jugador_id INT IDENTITY(1,1) PRIMARY KEY,
        steam_id VARCHAR(30) NOT NULL UNIQUE,
        nombre_steam VARCHAR(100) NOT NULL,
        avatar_url VARCHAR(500) NULL,
        perfil_url VARCHAR(500) NULL,
        fecha_consulta DATETIME2 NOT NULL DEFAULT SYSDATETIME()
    );
END;
GO

IF OBJECT_ID('SIG_Inventarios', 'U') IS NULL
BEGIN
    CREATE TABLE SIG_Inventarios (
        inventario_id INT IDENTITY(1,1) PRIMARY KEY,
        jugador_id INT NOT NULL,
        juego VARCHAR(50) NOT NULL DEFAULT 'Dota 2',
        fecha_actualizacion DATETIME2 NOT NULL DEFAULT SYSDATETIME(),
        estado VARCHAR(15) NOT NULL DEFAULT 'publico',
        CONSTRAINT chk_inventario_estado CHECK (estado IN ('publico', 'privado'))
    );
END;
GO

IF OBJECT_ID('SIG_Rarezas', 'U') IS NULL
BEGIN
    CREATE TABLE SIG_Rarezas (
        rareza_id INT IDENTITY(1,1) PRIMARY KEY,
        nombre_rareza VARCHAR(50) NOT NULL UNIQUE,
        descripcion VARCHAR(200) NULL
    );
END;
GO

IF OBJECT_ID('SIG_Items', 'U') IS NULL
BEGIN
    CREATE TABLE SIG_Items (
        item_id INT IDENTITY(1,1) PRIMARY KEY,
        inventario_id INT NOT NULL,
        rareza_id INT NOT NULL,
        nombre_item VARCHAR(150) NOT NULL,
        imagen_url VARCHAR(500) NULL,
        es_tradeable BIT NOT NULL DEFAULT 0,
        es_marketable BIT NOT NULL DEFAULT 0,
        fecha_registro DATETIME2 NOT NULL DEFAULT SYSDATETIME()
    );
END;
GO

IF OBJECT_ID('SIG_Galerias', 'U') IS NULL
BEGIN
    CREATE TABLE SIG_Galerias (
        galeria_id INT IDENTITY(1,1) PRIMARY KEY,
        usuario_id INT NOT NULL,
        nombre_galeria VARCHAR(100) NOT NULL,
        descripcion VARCHAR(300) NULL,
        fecha_creacion DATETIME2 NOT NULL DEFAULT SYSDATETIME(),
        estado BIT NOT NULL DEFAULT 1
    );
END;
GO

IF OBJECT_ID('SIG_GaleriaItems', 'U') IS NULL
BEGIN
    CREATE TABLE SIG_GaleriaItems (
        galeria_item_id INT IDENTITY(1,1) PRIMARY KEY,
        galeria_id INT NOT NULL,
        item_id INT NOT NULL,
        fecha_agregado DATETIME2 NOT NULL DEFAULT SYSDATETIME(),
        CONSTRAINT uq_galeria_item UNIQUE (galeria_id, item_id)
    );
END;
GO

/* 3. CLAVES FORANEAS */

IF NOT EXISTS (SELECT 1 FROM sys.foreign_keys WHERE name = 'fk_inventario_jugador')
    ALTER TABLE SIG_Inventarios ADD CONSTRAINT fk_inventario_jugador
    FOREIGN KEY (jugador_id) REFERENCES SIG_JugadoresSteam(jugador_id);
GO

IF NOT EXISTS (SELECT 1 FROM sys.foreign_keys WHERE name = 'fk_item_inventario')
    ALTER TABLE SIG_Items ADD CONSTRAINT fk_item_inventario
    FOREIGN KEY (inventario_id) REFERENCES SIG_Inventarios(inventario_id);
GO

IF NOT EXISTS (SELECT 1 FROM sys.foreign_keys WHERE name = 'fk_item_rareza')
    ALTER TABLE SIG_Items ADD CONSTRAINT fk_item_rareza
    FOREIGN KEY (rareza_id) REFERENCES SIG_Rarezas(rareza_id);
GO

IF NOT EXISTS (SELECT 1 FROM sys.foreign_keys WHERE name = 'fk_galeria_usuario')
    ALTER TABLE SIG_Galerias ADD CONSTRAINT fk_galeria_usuario
    FOREIGN KEY (usuario_id) REFERENCES SIG_Usuarios(usuario_id);
GO

IF NOT EXISTS (SELECT 1 FROM sys.foreign_keys WHERE name = 'fk_galeriaitems_galeria')
    ALTER TABLE SIG_GaleriaItems ADD CONSTRAINT fk_galeriaitems_galeria
    FOREIGN KEY (galeria_id) REFERENCES SIG_Galerias(galeria_id);
GO

IF NOT EXISTS (SELECT 1 FROM sys.foreign_keys WHERE name = 'fk_galeriaitems_item')
    ALTER TABLE SIG_GaleriaItems ADD CONSTRAINT fk_galeriaitems_item
    FOREIGN KEY (item_id) REFERENCES SIG_Items(item_id);
GO

/* 4. INDICES */

IF NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = 'idx_inventario_jugador' AND object_id = OBJECT_ID('SIG_Inventarios'))
    CREATE INDEX idx_inventario_jugador ON SIG_Inventarios(jugador_id);
GO

IF NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = 'idx_item_inventario' AND object_id = OBJECT_ID('SIG_Items'))
    CREATE INDEX idx_item_inventario ON SIG_Items(inventario_id);
GO

IF NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = 'idx_item_rareza' AND object_id = OBJECT_ID('SIG_Items'))
    CREATE INDEX idx_item_rareza ON SIG_Items(rareza_id);
GO

IF NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = 'idx_item_tradeable' AND object_id = OBJECT_ID('SIG_Items'))
    CREATE INDEX idx_item_tradeable ON SIG_Items(es_tradeable);
GO

IF NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = 'idx_galeria_usuario' AND object_id = OBJECT_ID('SIG_Galerias'))
    CREATE INDEX idx_galeria_usuario ON SIG_Galerias(usuario_id);
GO

IF NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = 'idx_galeriaitems_galeria' AND object_id = OBJECT_ID('SIG_GaleriaItems'))
    CREATE INDEX idx_galeriaitems_galeria ON SIG_GaleriaItems(galeria_id);
GO

/* 5. DATOS REALES */

-- USUARIOS
IF NOT EXISTS (SELECT 1 FROM SIG_Usuarios WHERE nombre_usuario = 'zFarchez')
    INSERT INTO SIG_Usuarios (nombre_usuario, correo, estado)
    VALUES ('zFarchez', 'duckhumir0ty2000@gmail.com', 'activo');
IF NOT EXISTS (SELECT 1 FROM SIG_Usuarios WHERE nombre_usuario = 'matadorxd')
    INSERT INTO SIG_Usuarios (nombre_usuario, correo, estado)
    VALUES ('matadorxd', 'huetesoelgiancarlos@gmail.com', 'activo');
IF NOT EXISTS (SELECT 1 FROM SIG_Usuarios WHERE nombre_usuario = 'Jonah Jhon')
    INSERT INTO SIG_Usuarios (nombre_usuario, correo, estado)
    VALUES ('Jonah Jhon', 'Joseds_maravi@gmail.com', 'activo');
GO

-- JUGADORES DE STEAM
IF NOT EXISTS (SELECT 1 FROM SIG_JugadoresSteam WHERE steam_id = '76561199208608946')
    INSERT INTO SIG_JugadoresSteam (steam_id, nombre_steam, avatar_url, perfil_url)
    VALUES ('76561199208608946', 'Farchez',
    'https://avatars.fastly.steamstatic.com/9ab894b478af9e5e9bac151384d1b9d09bafe210_full.jpg',
    'https://steamcommunity.com/profiles/76561199208608946');
IF NOT EXISTS (SELECT 1 FROM SIG_JugadoresSteam WHERE steam_id = '76561198034644045')
    INSERT INTO SIG_JugadoresSteam (steam_id, nombre_steam, avatar_url, perfil_url)
    VALUES ('76561198034644045', 'Veredicto',
    'https://avatars.fastly.steamstatic.com/97157c97a9a98f26306194be62219a4743a94e96_full.jpg',
    'https://steamcommunity.com/id/IsJustPartOfOurNature');
GO

-- RAREZAS
IF NOT EXISTS (SELECT 1 FROM SIG_Rarezas WHERE nombre_rareza = 'Común')
    INSERT INTO SIG_Rarezas (nombre_rareza, descripcion) VALUES ('Común', 'Artículo de rareza común');
IF NOT EXISTS (SELECT 1 FROM SIG_Rarezas WHERE nombre_rareza = 'Poco común')
    INSERT INTO SIG_Rarezas (nombre_rareza, descripcion) VALUES ('Poco común', 'Artículo de rareza poco común');
IF NOT EXISTS (SELECT 1 FROM SIG_Rarezas WHERE nombre_rareza = 'Raro')
    INSERT INTO SIG_Rarezas (nombre_rareza, descripcion) VALUES ('Raro', 'Artículo de rareza rara');
IF NOT EXISTS (SELECT 1 FROM SIG_Rarezas WHERE nombre_rareza = 'Mítico')
    INSERT INTO SIG_Rarezas (nombre_rareza, descripcion) VALUES ('Mítico', 'Artículo de rareza mítica');
IF NOT EXISTS (SELECT 1 FROM SIG_Rarezas WHERE nombre_rareza = 'Legendario')
    INSERT INTO SIG_Rarezas (nombre_rareza, descripcion) VALUES ('Legendario', 'Artículo de rareza legendaria');
IF NOT EXISTS (SELECT 1 FROM SIG_Rarezas WHERE nombre_rareza = 'Inmortal')
    INSERT INTO SIG_Rarezas (nombre_rareza, descripcion) VALUES ('Inmortal', 'Artículo de rareza inmortal');
GO

-- INVENTARIOS (uno por jugador, Dota 2)
DECLARE @jugador1 INT = (SELECT jugador_id FROM SIG_JugadoresSteam WHERE steam_id = '76561199208608946');
DECLARE @jugador2 INT = (SELECT jugador_id FROM SIG_JugadoresSteam WHERE steam_id = '76561198034644045');

IF NOT EXISTS (SELECT 1 FROM SIG_Inventarios WHERE jugador_id = @jugador1 AND juego = 'Dota 2')
    INSERT INTO SIG_Inventarios (jugador_id, juego, estado) VALUES (@jugador1, 'Dota 2', 'publico');
IF NOT EXISTS (SELECT 1 FROM SIG_Inventarios WHERE jugador_id = @jugador2 AND juego = 'Dota 2')
    INSERT INTO SIG_Inventarios (jugador_id, juego, estado) VALUES (@jugador2, 'Dota 2', 'publico');
GO

-- ITEMS DEL INVENTARIO DE FARCHEZ
DECLARE @inv1 INT = (
    SELECT TOP 1 i.inventario_id FROM SIG_Inventarios i
    INNER JOIN SIG_JugadoresSteam j ON i.jugador_id = j.jugador_id
    WHERE j.steam_id = '76561199208608946' AND i.juego = 'Dota 2'
);

IF NOT EXISTS (SELECT 1 FROM SIG_Items WHERE inventario_id = @inv1 AND nombre_item = 'Borealis and Puppey, Guardians of Ambry')
    INSERT INTO SIG_Items (inventario_id, rareza_id, nombre_item, imagen_url, es_tradeable, es_marketable)
    VALUES (@inv1, (SELECT rareza_id FROM SIG_Rarezas WHERE nombre_rareza = 'Raro'),
    'Borealis and Puppey, Guardians of Ambry',
    'https://community.fastly.steamstatic.com/economy/image/i0CoZ81Ui0m-9KwlBY1L_18myuGuq1wfhWSaZgMttydbPaERSR0Wqmu7LAocGIyi3kajH_rNwMuvemyY6UBw58j8_1fzRA_OlZ_u-jFk-P2ifqF-b_iGAHOV09F9ue9uXBakmhExti66lJ_6Hi_IKldPVtsjFLll9U_kxNWIa7ugsFDAiIIWxSX63ClO6nk_te8BVKpwr6GGjA3HZOVos5kCdaykTxjfCRgJu7ciGt6q1ee7Rw/330x192?allow_animated=1',
    1, 1);

IF NOT EXISTS (SELECT 1 FROM SIG_Items WHERE inventario_id = @inv1 AND nombre_item = 'Determined Greevil')
    INSERT INTO SIG_Items (inventario_id, rareza_id, nombre_item, imagen_url, es_tradeable, es_marketable)
    VALUES (@inv1, (SELECT rareza_id FROM SIG_Rarezas WHERE nombre_rareza = 'Legendario'),
    'Determined Greevil',
    'https://community.fastly.steamstatic.com/economy/image/i0CoZ81Ui0m-9KwlBY1L_18myuGuq1wfhWSaZgMttydbPaERSR0Wqmu7LAocGIa5zlm5VeSbxNikMG2e6Rly5oLx_FDrfh_9i5OuqXpeufD2P6BudKbCWGHCx7Yl4LBrGS2wlE5w4jzVwt2hdHiXbgIgCowwG7C6Lh86bA/96fx96f?allow_animated=1',
    1, 1);

IF NOT EXISTS (SELECT 1 FROM SIG_Items WHERE inventario_id = @inv1 AND nombre_item = 'Heroic Plated Bracers of the Demon Stone')
    INSERT INTO SIG_Items (inventario_id, rareza_id, nombre_item, imagen_url, es_tradeable, es_marketable)
    VALUES (@inv1, (SELECT rareza_id FROM SIG_Rarezas WHERE nombre_rareza = 'Inmortal'),
    'Heroic Plated Bracers of the Demon Stone',
    'https://community.fastly.steamstatic.com/economy/image/i0CoZ81Ui0m-9KwlBY1L_18myuGuq1wfhWSaZgMttydbPaERSR0Wqmu7LAocGIyi3kajH_PV0d6pCmiH7ER84Mjw71ToTyLiipnu-B1Z_fOtbbZ-b_SWA2ie_v1iv-9scCuhlB4ioS66lo7rJi6JbVUpW5dxR-FftUbrlYGxZeyx7waNiIpGnn_22i1J6CY_4roDBaoi8rqX0V94sAv9Jg/330x192?allow_animated=1',
    0, 1);
GO

-- USUARIOS Y GALERIAS PERSONALIZADAS
DECLARE @usuario1 INT = (SELECT usuario_id FROM SIG_Usuarios WHERE nombre_usuario = 'zFarchez');
DECLARE @usuario2 INT = (SELECT usuario_id FROM SIG_Usuarios WHERE nombre_usuario = 'matadorxd');

IF NOT EXISTS (SELECT 1 FROM SIG_Galerias WHERE usuario_id = @usuario1 AND nombre_galeria = 'Hueso Store')
    INSERT INTO SIG_Galerias (usuario_id, nombre_galeria, descripcion)
    VALUES (@usuario1, 'Hueso Store', 'Canjea puntos de KICK por sets cache');
IF NOT EXISTS (SELECT 1 FROM SIG_Galerias WHERE usuario_id = @usuario2 AND nombre_galeria = 'Bazar del Techisor')
    INSERT INTO SIG_Galerias (usuario_id, nombre_galeria, descripcion)
    VALUES (@usuario2, 'Bazar del Techisor', 'Mejores ofertas de items');
GO

-- ITEMS DE CADA GALERIA
DECLARE @galeria1 INT = (
    SELECT galeria_id FROM SIG_Galerias
    WHERE usuario_id = (SELECT usuario_id FROM SIG_Usuarios WHERE nombre_usuario = 'zFarchez')
      AND nombre_galeria = 'Hueso Store'
);
DECLARE @galeria2 INT = (
    SELECT galeria_id FROM SIG_Galerias
    WHERE usuario_id = (SELECT usuario_id FROM SIG_Usuarios WHERE nombre_usuario = 'matadorxd')
      AND nombre_galeria = 'Bazar del Techisor'
);
DECLARE @item1 INT = (SELECT TOP 1 item_id FROM SIG_Items WHERE nombre_item = 'Borealis and Puppey, Guardians of Ambry');
DECLARE @item2 INT = (SELECT TOP 1 item_id FROM SIG_Items WHERE nombre_item = 'Determined Greevil');
DECLARE @item3 INT = (SELECT TOP 1 item_id FROM SIG_Items WHERE nombre_item = 'Heroic Plated Bracers of the Demon Stone');

IF NOT EXISTS (SELECT 1 FROM SIG_GaleriaItems WHERE galeria_id = @galeria1 AND item_id = @item1)
    INSERT INTO SIG_GaleriaItems (galeria_id, item_id) VALUES (@galeria1, @item1);
IF NOT EXISTS (SELECT 1 FROM SIG_GaleriaItems WHERE galeria_id = @galeria1 AND item_id = @item2)
    INSERT INTO SIG_GaleriaItems (galeria_id, item_id) VALUES (@galeria1, @item2);
IF NOT EXISTS (SELECT 1 FROM SIG_GaleriaItems WHERE galeria_id = @galeria2 AND item_id = @item3)
    INSERT INTO SIG_GaleriaItems (galeria_id, item_id) VALUES (@galeria2, @item3);
GO

/* 6. PROCEDIMIENTO ALMACENADO: BUSCAR JUGADOR POR STEAM ID */

CREATE OR ALTER PROCEDURE sp_buscar_jugador
    @steam_id VARCHAR(30)
AS
BEGIN
    SET NOCOUNT ON;

    SELECT
        jugador_id,
        steam_id,
        nombre_steam,
        avatar_url,
        perfil_url
    FROM SIG_JugadoresSteam
    WHERE steam_id = @steam_id;
END;
GO

/* 7. PRUEBA DEL PROCEDIMIENTO */

EXEC sp_buscar_jugador @steam_id = '76561199208608946';
EXEC sp_buscar_jugador @steam_id = '76561198034644045';
GO

/* 8. CONSULTAS DE VERIFICACION */

-- Jugadores con su inventario
SELECT
    j.nombre_steam,
    i.juego,
    i.estado
FROM SIG_JugadoresSteam j
INNER JOIN SIG_Inventarios i
    ON j.jugador_id = i.jugador_id;
GO

-- Items con su jugador y rareza
SELECT
    j.nombre_steam,
    it.nombre_item,
    r.nombre_rareza,
    it.es_tradeable,
    it.es_marketable
FROM SIG_Items it
INNER JOIN SIG_Inventarios i
    ON it.inventario_id = i.inventario_id
INNER JOIN SIG_JugadoresSteam j
    ON i.jugador_id = j.jugador_id
INNER JOIN SIG_Rarezas r
    ON it.rareza_id = r.rareza_id;
GO

-- Galerias personalizadas con sus items
SELECT
    u.nombre_usuario,
    g.nombre_galeria,
    it.nombre_item
FROM SIG_Galerias g
INNER JOIN SIG_Usuarios u
    ON g.usuario_id = u.usuario_id
INNER JOIN SIG_GaleriaItems gi
    ON g.galeria_id = gi.galeria_id
INNER JOIN SIG_Items it
    ON gi.item_id = it.item_id;
GO

/* 9. CONSULTA FINAL */
SELECT * FROM SIG_Usuarios;
SELECT * FROM SIG_JugadoresSteam;
SELECT * FROM SIG_Inventarios;
SELECT * FROM SIG_Rarezas;
SELECT * FROM SIG_Items;
SELECT * FROM SIG_Galerias;
SELECT * FROM SIG_GaleriaItems;
GO
