<?php

namespace App\Documentation\Schemas;

/**
 * @OA\Schema(
 *     schema="Mark",
 *     type="object",
 *     title="Marca de Asistencia",
 *     description="Modelo de marca de asistencia",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="worker_id", type="integer", example=1),
 *     @OA\Property(property="device_id", type="integer", example=1),
 *     @OA\Property(property="direction", type="string", enum={"in", "out"}, example="in"),
 *     @OA\Property(property="source_type", type="string", enum={"remote", "clock", "external"}, example="remote"),
 *     @OA\Property(property="marked_at", type="string", format="date-time", example="2024-01-15T08:05:00"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class MarkSchema 
{
    public static function dummy() {}
}

/**
 * @OA\Schema(
 *     schema="MarkRemote",
 *     type="object",
 *     required={"worker_id", "device_id", "direction", "marked_at"},
 *     title="Crear Marca Remota",
 *     description="Datos para crear una marca desde aplicación remota",
 *     @OA\Property(property="worker_id", type="integer", example=1, description="ID del trabajador"),
 *     @OA\Property(property="device_id", type="integer", example=1, description="ID del dispositivo"),
 *     @OA\Property(property="direction", type="string", enum={"in", "out"}, example="in", description="Dirección: entrada o salida"),
 *     @OA\Property(property="marked_at", type="string", format="date-time", example="2024-01-15T08:05:00", description="Fecha y hora de la marca"),
 * )
 */
class MarkRemoteSchema 
{
    public static function dummy() {}
}

/**
 * @OA\Schema(
 *     schema="MarkClock",
 *     type="object",
 *     required={"worker_rut", "device_key", "direction", "marked_at"},
 *     title="Marca desde Reloj",
 *     description="Datos para crear una marca desde reloj biométrico",
 *     @OA\Property(property="worker_rut", type="string", example="11111111-1", description="RUT del trabajador"),
 *     @OA\Property(property="device_key", type="string", example="CLOCK-001", description="Clave única del dispositivo"),
 *     @OA\Property(property="direction", type="string", enum={"in", "out"}, example="in"),
 *     @OA\Property(property="marked_at", type="string", format="date-time", example="2024-01-15T08:05:00"),
 * )
 */
class MarkClockSchema 
{
    public static function dummy() {}
}

/**
 * @OA\Schema(
 *     schema="MarkBatchClock",
 *     type="object",
 *     required={"marks"},
 *     title="Marcas Batch desde Reloj",
 *     description="Array de marcas desde reloj biométrico",
 *     @OA\Property(
 *         property="marks",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/MarkClock")
 *     )
 * )
 */
class MarkBatchClockSchema 
{
    public static function dummy() {}
}

/**
 * @OA\Schema(
 *     schema="MarkExternal",
 *     type="object",
 *     required={"worker_id", "device_id", "direction", "marked_at"},
 *     title="Crear Marca Externa",
 *     description="Datos para crear una marca desde sistema externo",
 *     @OA\Property(property="worker_id", type="integer", example=1),
 *     @OA\Property(property="device_id", type="integer", example=1),
 *     @OA\Property(property="direction", type="string", enum={"in", "out"}, example="in"),
 *     @OA\Property(property="marked_at", type="string", format="date-time", example="2024-01-15T08:05:00"),
 * )
 */
class MarkExternalSchema 
{
    public static function dummy() {}
}
