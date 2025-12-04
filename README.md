# Sistema de Gestión de Producción - Laboratorios Portugal

Este sistema centraliza el flujo de producción de Laboratorios Portugal, reemplazando el uso de múltiples archivos Excel. Permite gestionar órdenes, aprobaciones de arte y registros de producción en una plataforma web unificada.

## 🚀 Acceso al Sistema

- **URL:** `/admin`
- **Usuario Administrador:** `admin@labpor.com`
- **Contraseña:** `password`

---

## 📖 Guía Paso a Paso

### 1. Panel de Control (Vista PCP)
Al iniciar sesión, accederás al listado principal de **Órdenes**. Esta es la "Grilla Maestra" que reemplaza al Excel de Planeamiento.
- **Filtrado:** Puedes buscar por código, cliente o filtrar por estado (Pendiente, En Progreso, etc.).
- **Detalle:** Haz clic en cualquier orden para ver su información completa.

### 2. Gestión de una Orden
Dentro de una Orden, encontrarás dos pestañas clave en la parte inferior:
- **Solicitudes de Arte:** Para gestionar el diseño y pre-prensa.
- **Etapas de Producción:** Para controlar el avance en planta (Corte, Impresión, Troquel, Pegado).

### 3. Flujo de Aprobación de Arte
El sistema cuenta con una regla de negocio crítica: **No se puede imprimir sin arte aprobado.**

1.  Ve a la pestaña **Solicitudes de Arte** de una orden.
2.  Asigna un diseñador y cambia el estado.
3.  **Importante:** Para desbloquear la producción, el estado debe ser **"Aprobado"**.

### 4. Control de Producción (El "Bloqueo")
Intenta iniciar la etapa de **IMPRESIÓN** antes de aprobar el arte:
- El sistema mostrará un error: *"No se puede iniciar IMPRESION porque el Arte no está APROBADO."*

Una vez que el arte esté **Aprobado**:
1.  Ve a la pestaña **Etapas de Producción**.
2.  Cambia el estado de IMPRESIÓN a **"En Progreso"**.
3.  El sistema ahora permitirá el cambio.

### 5. Registro de Producción (Operarios)
Los operarios pueden registrar su avance diario:
1.  Ve a la sección **Producción** en el menú lateral.
2.  Haz clic en **"Crear Registro de Producción"**.
3.  Selecciona la **Etapa** (ej. Impresión de la Orden 182291) y el **Operador**.
4.  Ingresa la **Cantidad Buena** y la **Merma**.
5.  Guarda el registro.

---

## 🛠️ Stack Tecnológico
- **Backend:** Laravel 12
- **Base de Datos:** MariaDB
- **Panel Administrativo:** FilamentPHP v3
- **Frontend:** Livewire / Blade / Tailwind CSS
