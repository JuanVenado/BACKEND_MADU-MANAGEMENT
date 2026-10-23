# MADU - Sistema de Gestión de Inventario

Aplicación web desarrollada para centralizar y optimizar el control de inventario de un negocio de distribución por mayoreo y menudeo. El proyecto permite gestionar productos, categorías, unidades, presentaciones, proveedores y movimientos (entradas, salidas y ajustes) con trazabilidad completa y auditoría de cambios.

---

## Equipo de Trabajo / Autores

- **Institución:** Universidad Tecnológica de Torreón (UTT)
- **Materia:** Formulación de Proyectos
- **Integrantes:**
  - Carlos Daniel García Rodríguez
  - Diego Reyes Nevarez
  - Edgar Alexis Ochoa Duarte
  - Ángel Gabriel Valenzuela Guerrero
- **Asesor(a):** M.C.C. Jesus Del Toro Robledo
- **Fecha de entrega:** Diciembre 2026

---

## Resumen del Proyecto

**MADU** resuelve la problemática de la dispersión de registros y la falta de control en las existencias físicas frente a los registros digitales. Su arquitectura por capas separa claramente las responsabilidades entre la interfaz de usuario, la lógica de negocio centralizada en una API y la persistencia de datos relacionales, asegurando transacciones y existencias actualizadas en tiempo real.

---

## Arquitectura y Tecnologías

El sistema sigue una arquitectura cliente-servidor de tres capas:

| Capa                        | Responsabilidad                                                                     | Tecnologías / Herramientas                    |
| :-------------------------- | :---------------------------------------------------------------------------------- | :-------------------------------------------- |
| **Presentación (Frontend)** | Interacción, navegación, formularios, tablas, filtros y mensajes responsivos.       | `React`, CSS / Componentes                    |
| **Aplicación (Backend)**    | Reglas de negocio, validaciones, autorización, políticas, transacciones y API REST. | `Laravel` (PHP)                               |
| **Datos (Base de datos)**   | Persistencia, relaciones, integridad referencial, índices y transacciones.          | `MySQL`, `MySQL Workbench`                    |
| **Infraestructura**         | Publicación segura, dominio, certificados SSL y despliegue en la nube.              | `DigitalOcean`, `Plesk`, `Namecheap`, `HTTPS` |
| **Gestión y Control**       | Control de versiones, ramas, metodologías ágiles y pruebas de API.                  | `Git`, `GitHub`, `Jira`, `Postman`, `Figma`   |

---

## Alcance Funcional del MVP (Producto Mínimo Viable)

El sistema prioriza las funcionalidades esenciales bajo criterios de aceptación estrictos:

1. **Autenticación y Seguridad:** Acceso exclusivo para el usuario administrador mediante correo y contraseña con cifrado (Hash) y expiración de sesiones inactivas.
2. **Catálogos y Productos:** Alta, consulta, edición y desactivación de productos, categorías, unidades de medida y proveedores (evitando duplicados).
3. **Manejo de Presentaciones:** Equivalencias y conversiones controladas entre unidad base y presentaciones autorizadas (piezas, paquetes, cajas, etc.).
4. **Control de Movimientos:**
   - **Entradas:** Incrementan el saldo disponible y registran la referencia comercial.
   - **Salidas:** Disminuyen el saldo y se rechazan automáticamente si superan la existencia disponible (evitando saldos negativos).
   - **Ajustes:** Positivos o negativos, estrictamente condicionados a un motivo justificado y permisos de administrador.
5. **Existencias y Alertas:** Consulta de saldos en tiempo real e identificación visual de productos iguales o inferiores al nivel mínimo configurado.
6. **Trazabilidad y Reportes:** Historial detallado por periodo que incluye folio, tipo de movimiento, cantidad, saldo anterior y posterior, fecha y usuario responsable.

---

## Metodología de Desarrollo (Scrum)

El proyecto se desarrolla bajo el marco de trabajo **Scrum** dividido en **6 sprints** de dos semanas (del 21 de septiembre al 8 de diciembre):

- **Sprint 1:** Definición validada, wireframes, arquitectura inicial y configuración de ambientes.
- **Sprint 2:** Módulo de autenticación y gestión de datos maestros de productos.
- **Sprint 3:** Unidades, equivalencias y registro de entradas de mercancía.
- **Sprint 4:** Registro de salidas y ajustes con protección de inventario.
- **Sprint 5:** Gestión de proveedores, filtros de búsqueda, mínimos y alertas de stock bajo.
- **Sprint 6:** Reportes operativos, pruebas integrales de seguridad/responsividad, despliegue en producción (`HTTPS`) y respaldos.

---

## Requisitos No Funcionales Clave

- **Seguridad:** Cifrado de contraseñas mediante hash y comunicaciones cifradas vía HTTPS.
- **Rendimiento:** Las consultas principales deben responder en menos de 10 segundos con el volumen inicial esperado.
- **Responsividad:** Funcional en versiones vigentes de Chrome, Edge y Firefox, con soporte adaptable desde dispositivos de $360\text{ px}$ de ancho.
- **Integridad Transaccional:** Los movimientos y la actualización de saldos se ejecutan estrictamente dentro de una transacción de base de datos para evitar registros parciales.

---

## Estructura de Navegación del Sistema

- `/login` - Inicio de sesión seguro.
- `/dashboard` - Panel principal con indicadores (productos activos, bajo mínimo, entradas/salidas del día), accesos rápidos y alertas.
- `/productos y catalogos` - Listado, filtros, búsqueda y formulario de alta/edición de productos.
- `/movimientos/new` - Registro interactivo de entradas, salidas y ajustes con cálculo de existencia resultante.
- `/reportes y auditoria` - Historial de movimientos y reportes operativos por periodo.
- `/proveedores` - Registro de proveedores|

---

## Licencia y Documentación

Este proyecto forma parte de la documentación académica y técnica del programa de la Universidad Tecnológica de Torreón. Todos los derechos reservados sobre la identidad visual y código fuente de MADU.

## Documentacion

Toda documentacion previa del proyecto se encuentra ubicada en la ruta /docs

## FIGMA

Link de figma:

https://www.figma.com/design/QooH8XDGz8NV638pHfENwx/MADU_Project?node-id=0-1&p=f&t=720wSZl1C4youkem-0
