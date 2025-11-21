<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Estados de Turnos
    |--------------------------------------------------------------------------
    |
    | Define los estados posibles para un turno de trabajo.
    | Estos estados se utilizan en toda la aplicación para rastrear
    | el estado de los turnos.
    |
    */
    'shift_statuses' => [
        'planned' => 'Planificado',
        'in_progress' => 'En progreso',
        'completed' => 'Completado',
        'inconsistent' => 'Inconsistente',
        'absent' => 'Ausente',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tipos de Dispositivos
    |--------------------------------------------------------------------------
    |
    | Define los tipos de dispositivos de marcación disponibles.
    | - clock: Reloj biométrico físico
    | - logical: Aplicación remota (ej: REMOTE_APP)
    | - external: Sistema externo de integración
    |
    */
    'device_types' => [
        'clock' => 'Reloj Biométrico',
        'logical' => 'Aplicación Lógica',
        'external' => 'Sistema Externo',
    ],

    /*
    |--------------------------------------------------------------------------
    | Estados de Dispositivos
    |--------------------------------------------------------------------------
    |
    | Define los estados posibles para un dispositivo.
    |
    */
    'device_statuses' => [
        'active' => 'Activo',
        'disabled' => 'Desactivado',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tipos de Fuente de Marcas
    |--------------------------------------------------------------------------
    |
    | Define los tipos de fuente desde donde se pueden crear marcas.
    | - remote: Desde la API (aplicación remota)
    | - clock: Desde un reloj biométrico
    | - external: Desde un sistema externo
    |
    */
    'mark_source_types' => [
        'remote' => 'Remota',
        'clock' => 'Reloj',
        'external' => 'Externa',
    ],

    /*
    |--------------------------------------------------------------------------
    | Direcciones de Marcas
    |--------------------------------------------------------------------------
    |
    | Define las direcciones posibles para una marca.
    |
    */
    'mark_directions' => [
        'in' => 'Entrada',
        'out' => 'Salida',
    ],

    /*
    |--------------------------------------------------------------------------
    | Estados de Trabajadores
    |--------------------------------------------------------------------------
    |
    | Define los estados posibles para un trabajador.
    |
    */
    'worker_statuses' => [
        'active' => 'Activo',
        'inactive' => 'Inactivo',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Key para Integraciones Externas
    |--------------------------------------------------------------------------
    |
    | API Key utilizada para autenticar peticiones desde sistemas externos.
    | Se debe configurar en el archivo .env como EXTERNAL_API_KEY.
    |
    */
    'external_api_key' => env('EXTERNAL_API_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | Configuración de Jobs
    |--------------------------------------------------------------------------
    |
    | Configuración relacionada con los jobs programados.
    |
    */
    'jobs' => [
        'associate_marks_interval' => env('ASSOCIATE_MARKS_INTERVAL', 5), // minutos
        'daily_absence_time' => env('DAILY_ABSENCE_TIME', '23:59'),
    ],
];

