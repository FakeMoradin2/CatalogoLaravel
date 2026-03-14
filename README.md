# Laravel Catalogo

Sitio web informativo con catálogo de productos y carrito de compras. Consume productos desde la API propia (proyecto **CatalogoAPI**).

## Estructura

- **LaravelCatalogo** (este proyecto): Cliente/frontend — puerto **8000**
- **CatalogoAPI** (carpeta hermana): API de productos — puerto **8001**

Ver `INSTRUCCIONES_EJECUCION.md` para ejecutar ambos proyectos.

## Requisitos

- PHP 8.2+
- Composer
- Node.js y npm

## Instalación

```bash
composer install
npm install
npm run build
```

## Ejecutar el cliente

```bash
php artisan serve
```

Abre http://127.0.0.1:8000 en el navegador. (El backend debe estar corriendo en el puerto 8001.)

## Páginas

- **Inicio** (`/`)
- **Nosotros** (`/nosotros`)
- **Catálogo** (`/productos`)
- **Detalle de producto** (`/productos/{id}`)
- **Carrito** (`/carrito`)
- **Contacto** (`/contacto`)

## Estructura

- **Layout**: `resources/views/layouts/app.blade.php` (header, menú, footer)
- **Controladores**: `PageController`, `ProductoController`, `CarritoController`
- **API**: CatalogoAPI (propia, puerto 8001)
