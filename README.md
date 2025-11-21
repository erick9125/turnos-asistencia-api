# Sistema de Gestión de Turnos y Asistencia

Backend desarrollado en Laravel 10+ para la gestión de turnos de trabajo y control de asistencia de trabajadores. El sistema permite registrar marcas de entrada y salida desde diferentes fuentes (relojes biométricos, aplicaciones remotas, sistemas externos) y asociarlas automáticamente con los turnos programados.

## Descripción

Este sistema proporciona una API REST completa para:

- Gestión de turnos de trabajo con validación de solapamientos
- Registro de marcas de asistencia desde múltiples fuentes
- Asociación automática de marcas con turnos mediante jobs programados
- Detección automática de ausencias
- Generación de reportes de asistencia, atrasos y horas extras
- Control de acceso basado en roles (manager/worker)
- Integración con sistemas externos mediante API Keys

## Requisitos

- PHP >= 8.2
- Composer >= 2.0
- MySQL >= 8.0 o PostgreSQL >= 13
- Node.js >= 18 (para assets frontend, opcional)
- Extensiones PHP requeridas:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML

## Instalación

### 1. Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd turnos-asistencia-api
```

### 2. Configurar entorno

```bash
cp .env.example .env
```

Editar el archivo `.env` y configurar:

- Conexión a base de datos
- Variables de aplicación
- API Keys para integraciones externas

### 3. Instalar dependencias

```bash
composer install
```

### 4. Generar clave de aplicación

```bash
php artisan key:generate
```

### 5. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

Esto creará:
- **Usuarios de ejemplo:**
  - `admin@test.com` / `admin123` (rol: admin) - Acceso completo al sistema
  - `manager@test.com` / `manager123` (rol: manager) - Gestión de turnos, dispositivos y reportes
  - `worker@test.com` / `worker123` (rol: worker) - Asociado a un trabajador, puede ver turnos y crear marcas
- Estructura de clientes (holdings, companies, branches, areas)
- Dispositivos de ejemplo
- Trabajadores de ejemplo
- Turnos y marcas de prueba (opcional)

### 6. Iniciar servidor de desarrollo

```bash
php artisan serve
```

La API estará disponible en `http://localhost:8000`

### 7. Configurar cola de trabajos (opcional, para desarrollo)

```bash
php artisan queue:work
```

## Autenticación y Roles

El sistema utiliza Laravel Sanctum para autenticación mediante tokens. Los usuarios tienen tres roles:

### Roles del Sistema

- **admin**: Acceso completo, puede gestionar usuarios, turnos, dispositivos, reportes y exportaciones
- **manager**: Puede gestionar turnos, dispositivos, reportes y exportaciones
- **worker**: Puede ver sus turnos semanales y crear marcas remotas desde la aplicación

### Cómo usar Sanctum

1. **Obtener token de autenticación:**

```bash
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "admin@test.com",
  "password": "admin123"
}
```

Respuesta:
```json
{
  "success": true,
  "message": "Login exitoso",
  "data": {
    "user": {
      "id": 1,
      "name": "Administrador del Sistema",
      "email": "admin@test.com",
      "role": "admin",
      "status": "active"
    },
    "token": "1|abcdef123456...",
    "worker_id": null
  }
}
```

2. **Usar el token en peticiones:**

```bash
GET /api/v1/shifts
Authorization: Bearer 1|abcdef123456...
```

**Nota**: Si el usuario es `worker`, la respuesta del login incluirá `worker_id` si está asociado a un trabajador.

### Credenciales de Prueba

Después de ejecutar `php artisan migrate --seed`, estarán disponibles:

| Email | Password | Rol | Permisos |
|-------|----------|-----|----------|
| admin@test.com | admin123 | admin | Acceso completo |
| manager@test.com | manager123 | manager | Gestión de turnos, dispositivos, reportes |
| worker@test.com | worker123 | worker | Ver turnos, crear marcas remotas |

## Arquitectura del Proyecto

El proyecto sigue una arquitectura limpia (DDD-lite) con separación clara de responsabilidades:

### Capas Principales

#### Controllers (`app/Http/Controllers/Api/V1/`)
Controladores delgados que solo validan requests, llaman a servicios y retornan respuestas. No contienen lógica de negocio.

#### Services (`app/Application/`)
Contienen toda la lógica de negocio:
- `ShiftService`: Gestión de turnos con validación de solapamientos
- `MarkIngestionService`: Ingesta de marcas desde diferentes fuentes
- `MarkAssociationService`: Asociación automática de marcas con turnos
- `WorkerService`: Gestión de trabajadores
- `DeviceService`: Gestión de dispositivos
- `AbsenceService`: Procesamiento de ausencias
- `ReportService`: Generación de reportes

#### Repositories (`app/Infrastructure/Persistence/Repositories/`)
Encapsulan todas las consultas a la base de datos usando Eloquent Query Builder:
- `ShiftRepository`: Consultas relacionadas con turnos
- `MarkRepository`: Consultas relacionadas con marcas
- `WorkerRepository`: Consultas relacionadas con trabajadores
- `DeviceRepository`: Consultas relacionadas con dispositivos

#### Models (`app/Models/`)
Modelos Eloquent con relaciones, scopes y métodos de negocio.

#### Jobs (`app/Jobs/Attendance/`)
Trabajos asíncronos para procesamiento en segundo plano:
- `AssociateMarksJob`: Asocia marcas con turnos (cada 5 minutos)
- `DailyAbsenceJob`: Procesa ausencias diarias (23:59)

## Endpoints Principales

### Autenticación
- `POST /api/v1/auth/login` - Login de usuario (retorna token Sanctum)

### Usuarios (requiere rol: admin)
- `GET /api/v1/users` - Listar usuarios
- `POST /api/v1/users` - Crear usuario
- `GET /api/v1/users/{id}` - Obtener usuario (admin o el mismo usuario)
- `PATCH /api/v1/users/{id}` - Actualizar usuario (rol o estado)
- `DELETE /api/v1/users/{id}` - Eliminar usuario (no si está asociado a worker activo)

### Turnos (requiere rol: manager o admin)
- `GET /api/v1/shifts` - Listar turnos
- `POST /api/v1/shifts` - Crear turno
- `GET /api/v1/shifts/{id}` - Obtener turno
- `PUT /api/v1/shifts/{id}` - Actualizar turno
- `DELETE /api/v1/shifts/{id}` - Eliminar turno

### Marcas
- `POST /api/v1/marks/remote` - Crear marca remota (requiere rol: worker)
- `POST /api/v1/marks/batch/clock` - Crear marcas batch desde reloj (requiere autenticación)
- `POST /api/v1/marks/external` - Crear marca externa (requiere API Key)

### Trabajadores
- `GET /api/v1/workers` - Listar trabajadores
- `GET /api/v1/workers/{id}` - Obtener trabajador
- `GET /api/v1/workers/{id}/shifts/week` - Turnos semanales de un trabajador (requiere rol: worker)

### Dispositivos (requiere rol: manager o admin)
- `GET /api/v1/devices` - Listar dispositivos
- `POST /api/v1/devices` - Crear dispositivo
- `GET /api/v1/devices/{id}` - Obtener dispositivo
- `PATCH /api/v1/devices/{id}/disable` - Desactivar dispositivo

### Reportes (requiere rol: manager o admin)
- `GET /api/v1/reports/attendance` - Reporte de asistencia
- `GET /api/v1/reports/delays` - Reporte de atrasos
- `GET /api/v1/reports/overtime` - Reporte de horas extras

## Jobs y Scheduler

El sistema utiliza jobs programados para tareas automáticas:

### AssociateMarksJob
- **Frecuencia**: Cada 5 minutos
- **Propósito**: Asocia marcas con turnos del día y actualiza estados
- **Proceso**:
  1. Obtiene turnos del día en estado `planned` o `in_progress`
  2. Busca marcas en el rango de horas del turno
  3. Determina primera entrada y última salida
  4. Actualiza estado del turno: `completed`, `in_progress` o `inconsistent`

### DailyAbsenceJob
- **Frecuencia**: Diariamente a las 23:59
- **Propósito**: Detecta turnos sin marcas y crea registros de ausencia
- **Proceso**:
  1. Busca turnos del día en estado `planned` sin marcas
  2. Crea registro en tabla `absences`
  3. Actualiza estado del turno a `absent`

### Configuración en Producción

Para que los jobs se ejecuten automáticamente en producción, agregar al crontab:

```bash
* * * * * cd /ruta/al/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

O configurar un supervisor para el scheduler de Laravel.

## Roles y Permisos

El sistema implementa un sistema de roles con tres niveles:

- **admin**: Acceso completo al sistema, puede gestionar usuarios, turnos, dispositivos, reportes y exportaciones
- **manager**: Puede gestionar turnos, dispositivos, acceder a reportes y exportaciones
- **worker**: Puede ver sus turnos semanales y crear marcas remotas desde la aplicación

### Reglas de Asociación Usuario-Trabajador

- Un trabajador puede tener o no acceso al sistema (si tiene `user_id` asociado)
- Jefaturas (managers) y administradores SIEMPRE son users (no tienen worker asociado)
- Solo usuarios con rol `worker` pueden estar asociados a un trabajador

Los middlewares `RoleMiddleware` y `ApiKeyMiddleware` controlan el acceso a las rutas según el rol del usuario o la presencia de una API Key válida.

## Integraciones Externas

El sistema soporta integraciones externas mediante API Keys:

1. Configurar `EXTERNAL_API_KEY` en el archivo `.env`
2. Incluir el header `X-API-KEY` en las peticiones a endpoints de integración
3. El middleware `ApiKeyMiddleware` validará automáticamente la clave

Endpoints que requieren API Key:
- `POST /api/v1/marks/external` - Crear marca desde sistema externo

## Testing

Ejecutar tests:

```bash
php artisan test
```

Los tests incluyen:
- Tests de autenticación (login exitoso/fallido)
- Tests de turnos (creación válida, rechazo por solapamiento)
- Tests de marcas (creación válida, rechazo de duplicados)
- Tests de permisos (acceso restringido por rol)

## Documentación de la API

El proyecto incluye documentación completa de la API usando Swagger/OpenAPI. La documentación está disponible en:

**URL de la documentación**: `http://localhost:8000/api/documentation`

### Generar documentación

Para generar o actualizar la documentación Swagger:

```bash
php artisan swagger:generate
```

**Nota**: Se usa un comando personalizado (`swagger:generate`) que suprime warnings no críticos de Swagger-php durante la generación. Si prefieres usar el comando original, puedes ejecutar `php artisan l5-swagger:generate`, pero puede mostrar warnings sobre clases de documentación.

Este comando debe ejecutarse:
- Después de instalar el proyecto
- Después de agregar o modificar endpoints
- En el proceso de build/despliegue

### Estructura de la documentación

La documentación está organizada en archivos separados para mantener la arquitectura limpia:

- `app/Documentation/Info.php` - Información general del API
- `app/Documentation/Schemas/` - Definiciones de modelos y requests
- `app/Documentation/Auth/` - Documentación de autenticación
- `app/Documentation/Attendance/` - Documentación de turnos y marcas
- `app/Documentation/Workers/` - Documentación de trabajadores
- `app/Documentation/Devices/` - Documentación de dispositivos
- `app/Documentation/Reports/` - Documentación de reportes
- `app/Documentation/Users/` - Documentación de usuarios
- `app/Documentation/Export/` - Documentación de exportación

**Importante**: Toda la documentación OpenAPI está en archivos separados. Los controladores, servicios y repositorios no contienen anotaciones Swagger para mantener el código limpio.

### Características de la documentación

- **Interfaz interactiva**: Prueba los endpoints directamente desde el navegador
- **Autenticación**: Soporte para tokens Sanctum y API Keys
- **Schemas completos**: Todos los modelos y requests están documentados
- **Ejemplos**: Cada endpoint incluye ejemplos de request y response
- **Filtros y búsqueda**: Navega fácilmente entre los diferentes endpoints

### Configuración

La configuración de Swagger se encuentra en `config/l5-swagger.php`. Variables de entorno relevantes:

- `L5_SWAGGER_CONST_HOST`: URL base del API (por defecto usa `APP_URL`)
- `L5_SWAGGER_GENERATE_ALWAYS`: Regenerar documentación en cada request (solo desarrollo)

## Estructura de Base de Datos

El sistema utiliza las siguientes tablas principales:

- `holdings` - Grupos empresariales
- `companies` - Empresas
- `branches` - Sucursales
- `areas` - Áreas de trabajo
- `devices` - Dispositivos de marcación
- `users` - Usuarios del sistema (con roles: worker, manager, admin)
- `workers` - Trabajadores (pueden tener `user_id` asociado)
- `shifts` - Turnos de trabajo
- `marks` - Marcas de asistencia
- `absences` - Ausencias
- `shift_marks` - Asociación explícita entre turnos y marcas

Todas las tablas incluyen foreign keys, índices y constraints para garantizar integridad referencial.

## Reglas de Negocio

### Turnos
- No se puede crear/actualizar un turno que se solape con otro del mismo trabajador
- No se puede crear/actualizar un turno con un trabajador inactivo
- Solo se puede eliminar un turno si no tiene marcas asociadas

### Marcas
- Una marca es válida si: worker existe y está activo, device existe y está activo, timestamp válido
- Regla de duplicado: mismo trabajador + mismo sentido (in/out) + mismo minuto = duplicado → rechazar
- Se genera `truncated_minute` (datetime sin segundos) para índice UNIQUE

### Asociación Marcas ↔ Turnos
- Primera entrada = inicio turno
- Última salida = término turno
- Estados posibles: `completed`, `in_progress`, `inconsistent` (si falta salida)

### Ausencias
- Turnos del día que no tienen ninguna marca
- Se crea registro en `absences` y se actualiza estado del turno a `absent`

## Futuras Mejoras

- **Migración a microservicios**: Separar el módulo de marcas en un microservicio independiente para mejorar escalabilidad
- **Notificaciones**: Implementar sistema de notificaciones para ausencias y atrasos
- **Dashboard**: Crear endpoints para dashboard con métricas en tiempo real
- **Exportación**: Agregar funcionalidad de exportación de reportes a Excel/PDF
- **API de webhooks**: Permitir que sistemas externos se suscriban a eventos del sistema
- **Auditoría**: Implementar sistema de logs de auditoría para cambios críticos
- **Cache**: Implementar cache para consultas frecuentes (listados, reportes)
- **Rate limiting**: Agregar límites de tasa para prevenir abuso de la API

## Contribución

1. Crear una rama para la nueva funcionalidad
2. Realizar cambios siguiendo las convenciones del proyecto
3. Agregar tests para nuevas funcionalidades
4. Actualizar documentación si es necesario
5. Crear pull request

## Licencia

Este proyecto es privado y de uso interno.
