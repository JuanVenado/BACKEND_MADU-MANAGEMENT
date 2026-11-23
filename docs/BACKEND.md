# Backend de MADU

MADU expone una API REST para el frontend en React. Esta guía describe el contrato disponible en `main` a partir del commit `c5e7657` y el avance de los módulos en otras ramas.

## Arquitectura y entorno local

React envía peticiones HTTP con JSON a Laravel. Sanctum autentica las rutas protegidas. Los Form Requests validan los datos, los controladores coordinan las operaciones, los API Resources dan forma a las respuestas y Eloquent persiste los datos en MySQL. El servicio de movimientos controla las transacciones de inventario.

Usa una versión de PHP compatible con [composer.json](../composer.json) y [composer.lock](../composer.lock), Composer y una base MySQL vacía para desarrollo. El manifiesto declara Laravel `^13.17` y Sanctum `^4.0`.

1. Ejecuta `composer install`.
2. Copia `.env.example` a `.env`. Configura `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD` para la base local.
3. Ejecuta `php artisan key:generate` y `php artisan migrate`.
4. Ejecuta `php artisan serve`. La URL base predeterminada es `http://127.0.0.1:8000/api`.
5. Para probar el login, crea un usuario de desarrollo activo en `usuarios` mediante el modelo `App\Models\Usuario`. El modelo aplica hash al guardar la contraseña. El seeder predeterminado usa `App\Models\User`, mientras que el login de MADU consulta `App\Models\Usuario`.

## Contrato HTTP

Envía `Accept: application/json` y, cuando haya un cuerpo JSON, `Content-Type: application/json`. Extrae el token de `data.token` tras el login y envíalo como `Authorization: Bearer <token>` en las rutas protegidas. Sanctum emite un token personal. El logout revoca el token usado en esa petición.

Las respuestas incluyen `success`, `data` y `message`. Para los errores de validación, `data.errors` agrupa los mensajes por campo.

| Código | Uso |
| --- | --- |
| `200` | Login, logout, consulta, edición o eliminación completada |
| `201` | Producto o movimiento creado |
| `400` | Datos inválidos, credenciales incorrectas, cuenta inactiva en el login o regla de inventario incumplida |
| `401` | Falta un token válido en una ruta protegida |
| `404` | Recurso inexistente |
| `405` | Método HTTP no habilitado para la ruta |
| `500` | Error interno con mensaje genérico |

## Rutas integradas

Los cuerpos y los parámetros se detallan debajo de la tabla. La fuente de las rutas es [routes/api.php](../routes/api.php).

| Método y ruta | Acceso | Operación |
| --- | --- | --- |
| `POST /api/login` | Público | Autentica con `nombre` y `password`. Devuelve `data.usuario` y `data.token`. |
| `POST /api/logout` | Token | Revoca el token actual. Devuelve `data: null`. |
| `GET /api/productos` | Token | Lista productos sin paginación. |
| `POST /api/productos` | Token | Crea un producto. |
| `GET /api/productos/{id}` | Token | Consulta un producto. |
| `PUT /api/productos/{id}`, `PATCH /api/productos/{id}` | Token | Edita un producto. Ambos métodos requieren los campos obligatorios. |
| `DELETE /api/productos/{id}` | Token | Ejecuta la eliminación física. |
| `GET /api/movimientos` | Token | Lista movimientos sin paginación, con los más recientes primero. |
| `POST /api/movimientos` | Token | Registra una entrada, salida o ajuste. |
| `GET /api/movimientos/{id}` | Token | Consulta un movimiento. |

### Producto

En alta y edición, envía `nombre`, `coding` único y los IDs existentes de `unidad` y `categoria`. Los campos opcionales son `descripcion`, `activo`, `existencial` y `stock_min` (entero mayor o igual a cero).

```json
{
  "nombre": "Tornillo hexagonal",
  "coding": "TORN-003",
  "unidad": 1,
  "categoria": 1,
  "stock_min": 10
}
```

El recurso devuelve `id`, `nombre`, `descripcion`, `coding`, `categoria`, `unidad`, `activo`, `stock_min` y `created_at`. Cuando carga las relaciones, `categoria` contiene el nombre y `unidad` la abreviatura. Para enviar un formulario usa los IDs de catálogo.

### Movimiento

Envía el ID de `producto` y el ID de la asociación `producto_presentacion` de ese producto. `cantidad` acepta hasta dos decimales y debe ser positiva en entradas y salidas. En ajustes puede ser positiva o negativa, debe ser distinta de cero y requiere `motivo`. `referencia` y `folio` son opcionales. Si omites el folio, se genera uno.

```json
{
  "producto": 1,
  "producto_presentacion": 1,
  "tipo": "entrada",
  "cantidad": 3,
  "referencia": "Compra de ejemplo"
}
```

El servicio obtiene el último saldo del producto, calcula el nuevo saldo y guarda el movimiento dentro de una transacción. Una salida que dejaría saldo negativo devuelve `400` sin guardar el movimiento. El recurso devuelve `cantidad_base`, `saldo_anterior` y `saldo_posterior`, además del folio, tipo, cantidad, producto, usuario y fecha. La cantidad base usa equivalencia 1:1 hasta que se incorpore el factor de conversión.

## Datos y relaciones

| Tablas | Uso |
| --- | --- |
| `categorias`, `unidades`, `productos` | Cada producto referencia una categoría y una unidad. |
| `proveedores`, `presentaciones` | Catálogos asociados a productos. |
| `producto_proveedor`, `producto_presentacion` | Relaciones muchos a muchos. Los movimientos usan el ID de `producto_presentacion`. |
| `usuarios`, `movimientos`, `auditorias` | Autenticación de MADU, historial de inventario y almacenamiento de auditoría. |
| `personal_access_tokens` | Tokens de Sanctum. |

Consulta las [migraciones](../database/migrations), el [DER](DER.jpeg) y el [diccionario de datos](MADU%20-%20Diccionario%20de%20Datos.pdf).

## Avance fuera de main

| Módulo | Estado al corte |
| --- | --- |
| Categorías, proveedores, presentaciones y unidades (MADU-12 a MADU-15) | En [PR #8](https://github.com/JuanVenado/BACKEND_MADU-MANAGEMENT/pull/8). Incluye listado, alta, detalle, edición y baja lógica bajo `/api/categorias`, `/api/proveedores`, `/api/presentaciones` y `/api/unidades`. Proveedores valida unicidad y formato del RFC de 12 o 13 caracteres. |
| Administración de usuarios y consulta de auditorías (MADU-16 y MADU-17) | Implementación reportada en un worktree separado. Pendiente de verificar su publicación e integrar los permisos `administrar-usuarios` y `consultar-auditorias` con los roles de MADU-10. |
| Colección Postman (MADU-20) | En [PR #9](https://github.com/JuanVenado/BACKEND_MADU-MANAGEMENT/pull/9). Incluye prueba rápida, productos y movimientos. |

El contrato de `main` todavía tiene pendientes la baja lógica de productos, la conversión de presentaciones, la autorización por roles y la expiración de tokens por inactividad.

## Pruebas

- **PHPUnit.** Ejecuta `php artisan test --filter=Scrum8ApiTest`. [phpunit.xml](../phpunit.xml) usa SQLite en memoria para las pruebas.
- **Postman o Newman.** Desde una copia que incluya la colección del PR #9, levanta Laravel con una base de prueba. Importa `docs/MADU.postman_collection.json` y configura `base_url` sin el sufijo `/api`, `usuario_nombre` y `usuario_password`. Para recorrer los flujos completos configura también IDs de prueba de categoría, unidad, producto y asociación producto-presentación.
- **Prueba rápida por CLI.** Ejecuta `npx newman run docs/MADU.postman_collection.json --folder "Prueba rápida" -e <entorno-local.json>`, usando un entorno local de Postman con las variables de prueba. El flujo comprueba token en login, acceso con token, rechazo `401` sin token y logout.
