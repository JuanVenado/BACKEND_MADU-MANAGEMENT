---

## Nomenclatura de Ramas (Branches)

Todas las ramas deben seguir este formato para mantener el repositorio organizado:

tipo/descripcion-corta

### Tipos de ramas permitidos:

- feature/   -> Para nuevas funcionalidades
- fix/       -> Para corrección de bugs
- hotfix/    -> Para correcciones urgentes en producción
- refactor/  -> Para refactorización de código sin cambiar funcionalidad
- docs/      -> Para cambios en documentación
- test/      -> Para agregar o modificar pruebas
- chore/     -> Para tareas de mantenimiento (dependencias, configs, etc.)

### Ejemplos correctos:
- feature/login-usuarios
- fix/error-al-guardar-pedido
- hotfix/corregir-vulnerabilidad
- refactor/limpiar-controlador-usuarios
- docs/actualizar-readme
- test/agregar-tests-controlador-pedidos
- chore/actualizar-dependencias-laravel

### Reglas:
- Usa solo minúsculas.
- Separa las palabras con guiones medios (-), no con guiones bajos ni espacios.
- La descripción debe ser corta pero clara.
- No uses ñ ni acentos.

---

## Nomenclatura de Pull Requests (PRs)

Los títulos de las Pull Requests deben seguir este formato:

[Tipo] Descripción breve de lo que hace el cambio

### Tipos:
- [Feature]    -> Nueva funcionalidad
- [Fix]        -> Corrección de bug
- [Hotfix]     -> Corrección urgente
- [Refactor]   -> Refactorización
- [Docs]       -> Cambios en documentación
- [Test]       -> Pruebas
- [Chore]      -> Mantenimiento

### Ejemplos correctos:
- [Feature] Agregar login con JWT
- [Fix] Corregir error al guardar pedidos duplicados
- [Hotfix] Parchear vulnerabilidad en autenticación
- [Refactor] Simplificar lógica del controlador de usuarios
- [Docs] Actualizar guía de instalación
- [Test] Agregar tests unitarios al modelo Producto
- [Chore] Actualizar Laravel a versión 11

### Cuerpo de la PR (descripción):
Cada PR debe incluir en su descripción:
- Qué hace el cambio.
- Por qué es necesario.
- Cómo probarlo (pasos o comandos).
- Capturas de pantalla si aplica (para cambios visuales o de API).

### Ejemplo de cuerpo de PR:
Qué: Se agregó el endpoint para registrar nuevos usuarios.
Por qué: Requerido para el módulo de autenticación del sprint 2.
Cómo probarlo:
  1. Ejecutar POST /api/register con body { "name", "email", "password" }.
  2. Verificar respuesta 201 y token JWT.
