# Guia de Flujo de Trabajo y Proteccion de Rama main

Este documento explica las reglas de proteccion establecidas en la rama principal (main) de este repositorio y como debe ser el flujo de trabajo para subir cambios.

## Objetivo

Garantizar la calidad del codigo y evitar errores en el sistema mediante la revision de codigo (Code Review) antes de que cualquier cambio se integre a la rama principal.

## Reglas Activas en main

Actualmente, la rama main tiene las siguientes reglas de proteccion habilitadas:

- No se permiten pushes directos a la rama main.
- Se requiere una Pull Request (PR) para integrar cualquier cambio.
- Se requiere al menos 1 aprobacion de otro colaborador para poder hacer merge.

## Nomenclatura de Ramas (Branches)

Todas las ramas deben seguir este formato para mantener el repositorio organizado:

`tipo/descripcion-corta`

### Tipos de ramas permitidos

- `feature/`   -> Para nuevas funcionalidades
- `fix/`       -> Para correccion de bugs
- `hotfix/`    -> Para correcciones urgentes en produccion
- `refactor/`  -> Para refactorizacion de codigo sin cambiar funcionalidad
- `docs/`      -> Para cambios en documentacion
- `test/`      -> Para agregar o modificar pruebas
- `chore/`     -> Para tareas de mantenimiento (dependencias, configs, etc.)

### Ejemplos correctos

- `feature/login-usuarios`
- `fix/error-al-guardar-pedido`
- `hotfix/corregir-vulnerabilidad`
- `refactor/limpiar-controlador-usuarios`
- `docs/actualizar-readme`
- `test/agregar-tests-controlador-pedidos`
- `chore/actualizar-dependencias-laravel`

### Reglas

- Usa solo minusculas.
- Separa las palabras con guiones medios (-), no con guiones bajos ni espacios.
- La descripcion debe ser corta pero clara.
- No uses ñ ni acentos.

## Nomenclatura de Pull Requests (PRs)

Los titulos de las Pull Requests deben seguir este formato:

`[Tipo] Descripcion breve de lo que hace el cambio`

### Tipos

- `[Feature]`    -> Nueva funcionalidad
- `[Fix]`        -> Correccion de bug
- `[Hotfix]`     -> Correccion urgente
- `[Refactor]`   -> Refactorizacion
- `[Docs]`       -> Cambios en documentacion
- `[Test]`       -> Pruebas
- `[Chore]`      -> Mantenimiento

### Ejemplos correctos

- `[Feature] Agregar login con JWT`
- `[Fix] Corregir error al guardar pedidos duplicados`
- `[Hotfix] Parchear vulnerabilidad en autenticacion`
- `[Refactor] Simplificar logica del controlador de usuarios`
- `[Docs] Actualizar guia de instalacion`
- `[Test] Agregar tests unitarios al modelo Producto`
- `[Chore] Actualizar Laravel a version 11`

### Cuerpo de la PR (descripcion)

Cada PR debe incluir en su descripcion:

- Que hace el cambio.
- Por que es necesario.
- Como probarlo (pasos o comandos).
- Capturas de pantalla si aplica (para cambios visuales o de API).

### Ejemplo de cuerpo de PR

- **Que:** Se agrego el endpoint para registrar nuevos usuarios.
- **Por que:** Requerido para el modulo de autenticacion del sprint 2.
- **Como probarlo:**
  1. Ejecutar POST /api/register con body { "name", "email", "password" }.
  2. Verificar respuesta 201 y token JWT.

## Nomenclatura de Commits

Todos los mensajes de commit deben seguir el estandar de Conventional Commits para mantener un historial claro, legible y automatizable.

### Formato general

`tipo(alcance): descripcion corta en minusculas`

### Tipos permitidos

- `feat`: Agrega una nueva funcionalidad al sistema.
- `fix`: Corrige un bug o error en el codigo.
- `docs`: Cambios exclusivos en la documentacion (README, comentarios, guias).
- `style`: Cambios de formato que no afectan el significado del codigo (espacios, comas, indentacion).
- `refactor`: Cambios en el codigo que no corrigen un bug ni agregan funcionalidad.
- `test`: Agregar pruebas faltantes o corregir pruebas existentes.
- `chore`: Cambios en herramientas auxiliares (dependencias, CI/CD, configs).
- `perf`: Cambios que mejoran el rendimiento.

### Alcance (Scope)

Es opcional pero recomendado. Indica a que modulo afecta el commit. Ejemplos: `(auth)`, `(usuarios)`, `(api)`, `(db)`.

### Reglas estrictas

1. Usa solo minusculas en todo el mensaje.
2. Usa modo imperativo: "agregar" en lugar de "agregado" o "agrega".
3. No uses punto final al terminar la descripcion corta.
4. La primera linea no debe exceder los 72 caracteres.
5. No uses ñ ni acentos.

### Ejemplos correctos

- `feat(auth): agregar validacion de token jwt en login`
- `fix(usuarios): corregir error al guardar correo duplicado`
- `docs(readme): actualizar instrucciones de instalacion local`
- `refactor(controladores): simplificar logica de metodo store`
- `test(modelos): agregar prueba unitaria para relacion hasMany`
- `chore(deps): actualizar laravel a version 11.0`

### Ejemplos incorrectos (NO HACER)

- "arregle el bug del login" (falta tipo, usa pasado)
- "FEAT: Agregando nueva funcionalidad." (mayusculas, gerundio y punto final)
- "cambios" (vago, sin tipo ni descripcion)
- "fix: arreglar error de sintaxis en la linea 45 del controlador de usuarios que causaba que no se guardara la informacion en la base de datos" (demasiado largo)

### Commits con multiples lineas

Si el cambio es complejo, deja una linea en blanco despues de la descripcion corta y agrega detalles:

    feat(api): agregar endpoint para generar reportes en pdf

    - Se agrego la libreria dompdf.
    - Se creo el controlador ReportController.
    - Se agrego la ruta POST /api/reportes/ventas.

    Closes #123

## Flujo de Trabajo Paso a Paso

### 1. Crear una rama para tu funcionalidad

Nunca trabajes directamente sobre main. Crea una rama nueva con un nombre descriptivo:

    git checkout -b feature/nombre-de-tu-funcionalidad
    # o para arreglar un bug:
    git checkout -b fix/descripcion-del-bug

### 2. Hacer tus cambios y commits

Realiza tus cambios, haz commit y sube tu rama al repositorio:

    git add .
    git commit -m "feat(modulo): descripcion del cambio"
    git push origin feature/nombre-de-tu-funcionalidad

### 3. Abrir una Pull Request (PR)

1. Ve a la pagina del repositorio en GitHub.
2. Veras un aviso sugiriendo comparar y hacer pull request de tu nueva rama hacia main. Haz clic en "Compare & pull request".
3. Escribe un titulo siguiendo la nomenclatura de PRs y una descripcion clara de lo que hace tu codigo.
4. Haz clic en "Create pull request".

### 4. Solicitar Revision (Review)

1. Dentro de la PR, en el panel derecho, busca la seccion "Reviewers".
2. Haz clic en el icono de engranaje y selecciona a tu companero para que revise tu codigo.
3. Espera a que tu companero revise el codigo y le de "Approve" (Aprobar).

### 5. Hacer Merge

Una vez que la PR tenga la aprobacion requerida (el check de "1 review required" estara en verde), el boton "Merge pull request" se habilitara.

1. Haz clic en "Merge pull request".
2. Confirma el merge.
3. Borra la rama ya fusionada para mantener el repo limpio.

## Notas Importantes

- **No puedes aprobar tu propia PR:** GitHub bloquea que el autor de la PR sea quien la apruebe. Siempre se necesita la revision de al menos un companero.
- **Si te piden cambios:** Si el revisor le da "Request changes", deberas hacer los ajustes en tu rama, hacer push de los nuevos commits, y el revisor debera volver a aprobar.
- **Conflictos de merge:** Si GitHub te avisa de conflictos en la PR, deberas resolverlos en tu rama local antes de poder hacer el merge.
