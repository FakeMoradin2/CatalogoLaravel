# Laravel Catalogo

Sitio web con catálogo de productos, carrito de compras público y módulo de usuarios autenticados por token. Consume datos desde la API propia (proyecto **CatalogoAPI**).

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

## Páginas públicas

- **Inicio** (`/`)
- **Nosotros** (`/nosotros`)
- **Catálogo** (`/productos`)
- **Detalle de producto** (`/productos/{id}`)
- **Carrito** (`/carrito`)
- **Contacto** (`/contacto`)

## Páginas de usuario (requieren autenticación API)

- **Registro** (`/registro`)
- **Login** (`/login`)
- **Perfil** (`/perfil`) con actualización separada de:
  - Datos generales
  - Imagen de perfil
  - Contraseña

## Configuración de endpoints de autenticación

En `.env` del cliente:

- `AUTH_API_URL`
- `AUTH_API_REGISTER_ENDPOINT`
- `AUTH_API_LOGIN_ENDPOINT`
- `AUTH_API_LOGOUT_ENDPOINT`
- `AUTH_API_PROFILE_ENDPOINT`
- `AUTH_API_AVATAR_ENDPOINT`
- `AUTH_API_PASSWORD_ENDPOINT`

## Estructura

- **Layout**: `resources/views/layouts/app.blade.php` (header, menú, footer)
- **Controladores**: `PageController`, `ProductoController`, `CarritoController`, `AuthController`, `ProfileController`
- **API**: CatalogoAPI (propia, puerto 8001)
