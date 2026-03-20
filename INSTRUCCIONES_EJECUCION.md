# Cómo ejecutar el proyecto completo

## Estructura del proyecto

```
proyectos/
├── LaravelCatalogo/     ← Cliente (frontend + carrito público + usuarios) - puerto 8000
└── CatalogoAPI/         ← Backend API (productos) - puerto 8001
```

## Puertos

| Proyecto | Puerto | Ubicación |
|----------|--------|-----------|
| **API Backend** (productos) | **8001** | `../CatalogoAPI/` |
| **Cliente Laravel** (catálogo + carrito + auth) | **8000** | `LaravelCatalogo/` (este proyecto) |

## Pasos para ejecutar

### 1. Crear la base de datos del backend

En MySQL Workbench:
1. Abrir el archivo `CatalogoAPI/database/schema_productos.sql` (en la carpeta hermana del cliente)
2. Ejecutar todo el script (crea `catalogo_api` y tabla `productos` con datos)

Ajustar `.env` del backend si tu MySQL tiene usuario/contraseña distintos:
- `CatalogoAPI/.env` → `DB_USERNAME`, `DB_PASSWORD`

### 2. Iniciar la API (Terminal 1)

```bash
cd ../CatalogoAPI
php artisan serve --port=8001
```

Verás: `http://127.0.0.1:8001`

### 3. Iniciar el cliente (Terminal 2)

```bash
# En la raíz del proyecto (LaravelCatalogo)
php artisan serve
```

Verás: `http://127.0.0.1:8000`

### 4. Usar la aplicación

Abrir **http://127.0.0.1:8000** en el navegador.

- Los productos se consumen desde la API en puerto 8001
- El carrito se guarda en sesión (sin base de datos en el cliente)
- El registro/login/perfil usan token de la API (guardado en sesión del cliente)

## Configuración para autenticación API (cliente)

En `LaravelCatalogo/.env` define (o deja los valores por defecto):

```env
AUTH_API_URL=http://127.0.0.1:8001/api
AUTH_API_REGISTER_ENDPOINT=/register
AUTH_API_LOGIN_ENDPOINT=/login
AUTH_API_LOGOUT_ENDPOINT=/logout
AUTH_API_PROFILE_ENDPOINT=/profile
AUTH_API_AVATAR_ENDPOINT=/profile/avatar
AUTH_API_PASSWORD_ENDPOINT=/profile/password
```

> Si tu backend usa rutas distintas, solo ajusta estos endpoints en el `.env` del cliente.
