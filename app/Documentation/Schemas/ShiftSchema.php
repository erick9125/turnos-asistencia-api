<?php

namespace App\Documentation\Schemas;

/**
 * @OA\Schema(
 *     schema="Shift",
 *     type="object",
 *     title="Turno",
 *     description="Modelo de turno de trabajo",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="worker_id", type="integer", example=1),
 *     @OA\Property(property="area_id", type="integer", example=1),
 *     @OA\Property(property="start_at", type="string", format="date-time", example="2024-01-15T08:00:00"),
 *     @OA\Property(property="end_at", type="string", format="date-time", example="2024-01-15T17:00:00"),
 *     @OA\Property(property="status", type="string", enum={"planned", "in_progress", "completed", "inconsistent", "absent"}, example="planned"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class ShiftSchema 
{
    public static function dummy() {}
}

/**
 * @OA\Schema(
 *     schema="ShiftStore",
 *     type="object",
 *     required={"worker_id", "area_id", "start_at", "end_at"},
 *     title="Crear Turno",
 *     description="Datos para crear un nuevo turno",
 *     @OA\Property(property="worker_id", type="integer", example=1, description="ID del trabajador"),
 *     @OA\Property(property="area_id", type="integer", example=1, description="ID del área"),
 *     @OA\Property(property="start_at", type="string", format="date-time", example="2024-01-15T08:00:00", description="Fecha y hora de inicio"),
 *     @OA\Property(property="end_at", type="string", format="date-time", example="2024-01-15T17:00:00", description="Fecha y hora de fin"),
 * )
 */
class ShiftStoreSchema 
{
    public static function dummy() {}
}

/**
 * @OA\Schema(
 *     schema="ShiftUpdate",
 *     type="object",
 *     title="Actualizar Turno",
 *     description="Datos para actualizar un turno existente",
 *     @OA\Property(property="worker_id", type="integer", example=1, description="ID del trabajador"),
 *     @OA\Property(property="area_id", type="integer", example=1, description="ID del área"),
 *     @OA\Property(property="start_at", type="string", format="date-time", example="2024-01-15T08:00:00"),
 *     @OA\Property(property="end_at", type="string", format="date-time", example="2024-01-15T17:00:00"),
 *     @OA\Property(property="status", type="string", enum={"planned", "in_progress", "completed", "inconsistent", "absent"}),
 * )
 */
class ShiftUpdateSchema 
{
    public static function dummy() {}
}
