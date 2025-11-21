<?php

namespace App\Documentation\Schemas;

/**
 * @OA\Schema(
 *     schema="ApiResponse",
 *     type="object",
 *     title="Respuesta API Estándar",
 *     description="Formato estándar de respuesta de la API",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Operación exitosa"),
 *     @OA\Property(property="data", type="object", description="Datos de la respuesta"),
 * )
 */
class ApiResponseSchema 
{
    public static function dummy() {}
}
