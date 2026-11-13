# Guia de Flujo de Trabajo y Proteccion de Rama main

Este documento explica las reglas de proteccion establecidas en la rama principal (main) de este repositorio y como debe ser el flujo de trabajo para subir cambios.

## Objetivo
Garantizar la calidad del codigo y evitar errores en el sistema mediante la revision de codigo (Code Review) antes de que cualquier cambio se integre a la rama principal.

## Reglas Activas en main
Actualmente, la rama main tiene las siguientes reglas de proteccion habilitadas:
- No se permiten pushes directos a la rama main.
- Se requiere una Pull Request (PR) para integrar cualquier cambio.
- Se requiere al menos 1 aprobacion de otro colaborador para poder hacer merge.

***

## Nomenclatura de Ramas (Branches)

Todas las ramas deben seguir este formato para mantener el repositorio organizado:

`tipo/descripcion-corta`

### Tipos de ramas permitidos:

- `feature/`   -> Para nuevas funcionalidades
- `fix/`       -> Para correccion de bugs
- `hotfix/`    -> Para correcciones urgentes en produccion
- `refactor/`  -> Para refactorizacion de codigo sin cambiar funcionalidad
- `docs/`      -> Para cambios en documentacion
- `test/`      -> Para agregar o modificar pruebas
- `chore/`     -> Para tareas de mantenimiento (dependencias, configs, etc.)

### Ejemplos correctos:
- `feature/login-usuarios`
- `fix/error-al-guardar-pedido`
- `hotfix/corregir-vulnerabilidad`
- `refactor/limpiar-controlador-usuarios`
- `docs/actualizar-readme`
- `test/agregar-tests-controlador-pedidos`
- `chore/actualizar-dependencias-laravel`

### Reglas:
- Usa solo minusculas.
- Separa las palabras con guiones medios (-), no con guiones bajos ni espacios.
- La descripcion debe ser corta pero clara.
- No uses ñ ni acentos.

***

## Nomenclatura de Pull Requests (PRs)

Los titulos de las Pull Requests deben seguir este formato:

`[Tipo] Descripcion breve de lo que hace el cambio`

### Tipos:
- `[Feature]`    -> Nueva funcionalidad
- `[Fix]`        -> Correccion de bug
- `[Hotfix]`     -> Correccion urgente
- `[Refactor]`   -> Refactorizacion
- `[Docs]`       -> Cambios en documentacion
- `[Test]`       -> Pruebas
- `[Chore]`      -> Mantenimiento

### Ejemplos correctos:
- `[Feature] Agregar login con JWT`
- `[Fix] Corregir error al guardar pedidos duplicados`
- `[Hotfix] Parchear vulnerabilidad en autenticacion`
- `[Refactor] Simplificar logica del controlador de usuarios`
- `[Docs] Actualizar guia de instalacion`
- `[Test] Agregar tests unitarios al modelo Producto`
- `[Chore] Actualizar Laravel a version 11`

### Cuerpo de la PR (descripcion):
Cada PR debe incluir en su descripcion:
- Que hace el cambio.
- Por que es necesario.
- Como probarlo (pasos o comandos).
- Capturas de pantalla si aplica (para cambios visuales o de API).

### Ejemplo de cuerpo de PR:
- **Que:** Se agrego el endpoint para registrar nuevos usuarios.
- **Por que:** Requerido para el modulo de autenticacion del sprint 2.
- **Como probarlo:**
  1. Ejecutar POST /api/register con body { "name", "email", "password" }.
  2. Verificar respuesta 201 y token JWT.

***

## Flujo de Trabajo Paso a Paso

### 1. Crear una rama para tu funcionalidad
Nunca trabajes directamente sobre main. Crea una rama nueva con un nombre descriptivo:

```bash
git checkout -b feature/nombre-de-tu-funcionalidad
# o para arreglar un bug:
git checkout -b fix/descripcion-del-bug
