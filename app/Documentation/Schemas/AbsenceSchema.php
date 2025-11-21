<?php

namespace App\Documentation\Schemas;

/**
 * @OA\Schema(
 *     schema="Absence",
 *     type="object",
 *     title="Ausencia",
 *     description="Modelo de ausencia",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="shift_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="worker_id", type="integer", example=1),
 *     @OA\Property(property="date", type="string", format="date", example="2024-01-15"),
 *     @OA\Property(property="reason", type="string", nullable=true, example="Sin marcas de asistencia"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class AbsenceSchema 
{
    public static function dummy() {}
}
