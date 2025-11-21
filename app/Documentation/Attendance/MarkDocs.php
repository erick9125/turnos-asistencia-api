<?php

namespace App\Documentation\Attendance;

class MarkDocs 
{
    /**
     * @OA\Post(
     *     path="/api/v1/marks/remote",
     *     summary="Crear marca remota",
     *     description="Crea una marca de asistencia desde una aplicación remota. Requiere rol de worker.",
     *     tags={"Marcas"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MarkRemote")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Marca creada exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Marca creada exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Mark")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación o marca duplicada",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Sin permisos (requiere rol worker)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public static function createRemote() {}

    /**
     * @OA\Post(
     *     path="/api/v1/marks/batch/clock",
     *     summary="Crear marcas batch desde reloj",
     *     description="Crea múltiples marcas desde un reloj biométrico. Requiere autenticación Sanctum.",
     *     tags={"Marcas"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MarkBatchClock")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Marcas procesadas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Marcas procesadas"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="created",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/Mark")
     *                 ),
     *                 @OA\Property(
     *                     property="errors",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="index", type="integer"),
     *                         @OA\Property(property="error", type="string")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public static function createBatchClock() {}

    /**
     * @OA\Post(
     *     path="/api/v1/marks/external",
     *     summary="Crear marca externa",
     *     description="Crea una marca desde un sistema externo. Requiere API Key en header X-API-KEY.",
     *     tags={"Marcas"},
     *     security={{"apiKey": {}}},
     *     @OA\Parameter(
     *         name="X-API-KEY",
     *         in="header",
     *         required=true,
     *         description="API Key para autenticación",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/MarkExternal")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Marca externa creada exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Marca externa creada exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Mark")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación o marca duplicada",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="API Key inválida o faltante",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public static function createExternal() {}
}
