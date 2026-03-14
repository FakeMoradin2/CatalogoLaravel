# Laravel Catalogo

Sitio web informativo con catálogo de productos que consume datos desde la API [DummyJSON](https://dummyjson.com/products) (sin autenticación).

## Requisitos

- PHP 8.2+
- Composer
- Node.js y npm

## Instalación

```bash
# Dependencias PHP (ya instaladas con create-project)
composer install

# Dependencias Node (Tailwind, Vite)
npm install
npm run build
```

## Ejecutar el servidor

```bash
php artisan serve
```

Abre http://127.0.0.1:8000 en el navegador.

## Páginas

- **Inicio** (`/`)
- **Nosotros** (`/nosotros`)
- **Catálogo** (`/productos`) — lista productos desde la API
- **Detalle de producto** (`/productos/{id}`)
- **Contacto** (`/contacto`)

## Estructura

- **Layout**: `resources/views/layouts/app.blade.php` (header, menú, footer)
- **Controladores**: `PageController`, `ProductoController`
- **API**: DummyJSON (no requiere token)
