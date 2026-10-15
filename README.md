# Catálogo y cotizaciones online

Aplicación Laravel para publicar un catálogo de productos tecnológicos y recibir solicitudes de cotización. Los visitantes pueden buscar y filtrar productos, seleccionar cantidades y enviar sus datos de contacto. El equipo inicia sesión para administrar productos y dar seguimiento a las cotizaciones.

## Inicio rápido

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Registra un usuario desde `/register` para ingresar al panel. No se crean usuarios ni contraseñas de demostración al cargar los datos iniciales.

## Funcionalidades

- Catálogo público en `/`, con búsqueda y filtro por categoría.
- Solicitud de cotización con varias líneas de productos y cantidades.
- Código de seguimiento, contacto del cliente, total y estado para cada solicitud.
- Precios, nombres y SKU guardados como instantánea en cada cotización para conservar el historial aunque cambie el catálogo.
- Panel autenticado para crear, editar y desactivar productos, y para consultar solicitudes y actualizar sus estados.
- Administración de categorías desde el panel. Las categorías existentes de los productos se conservan al aplicar la migración.
- Galería de tres ilustraciones tecnológicas locales de muestra en cada producto del catálogo; son ilustrativas y no fotografías exactas del producto.

La moneda predeterminada es USD. Configura `CATALOGO_MONEDA` en `.env` con un código ISO 4217, por ejemplo `USD`, `EUR` o `MXN`.

Ejecuta las pruebas con `php artisan test`.

Las fotografías de producto se guardan en el disco público de Laravel. En una instalación nueva, ejecuta `php artisan storage:link` para que estén disponibles en el catálogo y en las cotizaciones.
