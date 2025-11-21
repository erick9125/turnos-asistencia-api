<?php

namespace App\Documentation\Workers;

class WorkerDocs 
{
    /**
     * @OA\Get(
     *     path="/api/v1/workers",
     *     summary="Listar trabajadores",
     *     description="Obtiene una lista de trabajadores con filtros opcionales. Disponible para managers y workers.",
     *     tags={"Trabajadores"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="area_id",
     *         in="query",
     *         description="Filtrar por ID de área",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar por estado",
     *         required=false,
     *         @OA\Schema(type="string", enum={"active", "inactive"})
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Buscar por nombre o RUT",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de trabajadores",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Trabajadores obtenidos exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Worker")
     *             )
     *         )
     *     )
     * )
     */
    public static function index() {}

    /**
     * @OA\Get(
     *     path="/api/v1/workers/{id}",
     *     summary="Obtener trabajador",
     *     description="Obtiene los detalles de un trabajador específico. Disponible para managers y workers.",
     *     tags={"Trabajadores"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del trabajador",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del trabajador",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Trabajador obtenido exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Worker")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Trabajador no encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public static function show() {}
}
