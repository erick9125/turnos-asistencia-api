<?php

namespace App\Documentation\Schemas;

/**
 * @OA\Schema(
 *     schema="Worker",
 *     type="object",
 *     title="Trabajador",
 *     description="Modelo de trabajador",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="area_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Juan Pérez"),
 *     @OA\Property(property="rut", type="string", example="11111111-1"),
 *     @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class WorkerSchema 
{
    public static function dummy() {}
}
