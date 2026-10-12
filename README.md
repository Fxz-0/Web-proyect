# Steam Inventory Gallery (SIG)

Una plataforma web moderna que permite a vendedores de items de Steam 
crear galerías personalizadas de su inventario, y a compradores explorar 
y descubrir items de forma visual y organizada.

## Descripción del Proyecto

**SIG** nace de la idea de proporcionar una tienda virtual profesional 
para personas que venden items de Steam a través de redes sociales 
(Facebook, Instagram). Permite crear dashboards personalizados donde 
vendedores muestren sus inventarios de forma atractiva, y compradores 
exploren items con facilidad.

## Características Principales

**Búsqueda de usuarios Steam**
   - Por SteamID o nombre de usuario
   - Validación de perfil público

**Galería visual de inventarios**
   - Visualización en grid responsivo
   - Tarjetas de items con imagen, nombre y detalles

**Filtrado inteligente**
   - Por rareza del item
   - Por disponibilidad (tradeable/comerciable)

**Soporte inicial: Dota 2**
   - Expandible a otros juegos con mercado Steam

**Interfaz intuitiva**
   - Página de inicio con instrucciones
   - Diseño moderno y responsivo

## Público Objetivo

- **Jugadores de Steam** interesados en explorar inventarios
- **Coleccionistas de items** virtuales
- **Traders** que desean vender o comprar items
- **Streamers** que promocionan su inventario

## Características Futuras (Opcionales)

**Sección de Usuario**
   - Login con Steam OpenID
   - Crear galería personalizada (seleccionar items específicos)
   - No mostrar todo el inventario, solo lo que el usuario quiera

**Análisis de Mercado**
   - Historial de búsquedas recientes
   - Comparativa de precios con mercado Steam
   - Tendencias de items

**Escalabilidad**
   - Soporte para otros juegos (CS:GO, Team Fortress 2, etc.)
   - Integración con múltiples mercados

## Tecnologías

| Tecnología | Propósito |
|-----------|-----------|
| **Node.js** | Entorno de ejecución |
| **npm/pnpm** | Gestor de dependencias |
| **HTML5** | Estructura semántica |
| **Tailwind CSS** | Estilos y diseño responsivo |
| **JavaScript Vanilla** | Interactividad del cliente |
| **Steam Web API** | Datos de inventarios y usuarios |
| **GitHub** | Control de versiones |

## Requisitos Previos

- Node.js v16 o superior
- npm o pnpm instalado
- Clave API de Steam (gratuita)
- Navegador moderno (Chrome, Firefox, Safari, Edge)

## Instalación y Configuración

### 1. Descargar el Proyecto

```bash
# Descomprime el proyect.zip
unzip steam-inventory-gallery.zip
cd steam-inventory-gallery
