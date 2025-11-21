<?php

namespace App\Documentation\Export;

class ExportDocs 
{
    /**
     * @OA\Get(
     *     path="/api/v1/export/marks",
     *     summary="Exportar marcas para sistema legado",
     *     description="Exporta marcas de asistencia en formato compatible con sistemas legados. Incluye información completa de jerarquía (holding, company, branch, area). Requiere rol de manager.",
     *     tags={"Exportación"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Fecha de inicio (formato: Y-m-d). Por defecto: último mes.",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Fecha de fin (formato: Y-m-d). Por defecto: hoy.",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="only_not_exported",
     *         in="query",
     *         description="Si es true, solo exporta marcas no exportadas previamente. Por defecto: true.",
     *         required=false,
     *         @OA\Schema(type="boolean", default=true)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Marcas exportadas exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Marcas exportadas exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="marks",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="worker_id", type="integer", example=1),
     *                         @OA\Property(property="device_id", type="integer", example=1),
     *                         @OA\Property(property="direction", type="string", enum={"in", "out"}, example="in"),
     *                         @OA\Property(property="marked_at", type="string", format="date-time", example="2024-01-15T08:05:00"),
     *                         @OA\Property(property="source", type="string", enum={"remote", "clock", "external"}, example="clock"),
     *                         @OA\Property(property="area_id", type="integer", example=1),
     *                         @OA\Property(property="branch_id", type="integer", example=1),
     *                         @OA\Property(property="company_id", type="integer", example=1),
     *                         @OA\Property(property="holding_id", type="integer", example=1),
     *                         @OA\Property(property="worker_name", type="string", example="Juan Pérez"),
     *                         @OA\Property(property="worker_rut", type="string", example="11111111-1"),
     *                         @OA\Property(property="device_name", type="string", example="Reloj Biométrico Principal"),
     *                         @OA\Property(property="device_key", type="string", example="CLOCK-001"),
     *                         @OA\Property(property="area_name", type="string", example="Área de Producción"),
     *                         @OA\Property(property="area_code", type="string", example="PROD-001"),
     *                         @OA\Property(property="branch_name", type="string", example="Sucursal Centro"),
     *                         @OA\Property(property="branch_code", type="string", example="BR-001"),
     *                         @OA\Property(property="company_name", type="string", example="Empresa Ejemplo"),
     *                         @OA\Property(property="company_rut", type="string", example="12345678-9"),
     *                         @OA\Property(property="holding_name", type="string", example="Holding Principal")
     *                     )
     *                 ),
     *                 @OA\Property(property="count", type="integer", example=150)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Sin permisos (requiere rol manager)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public static function exportMarks() {}

    /**
     * @OA\Post(
     *     path="/api/v1/export/marks/mark-as-exported",
     *     summary="Marcar marcas como exportadas",
     *     description="Marca un conjunto de marcas como exportadas al sistema legado. Actualiza el campo exported_at. Requiere rol de manager.",
     *     tags={"Exportación"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"mark_ids"},
     *             @OA\Property(
     *                 property="mark_ids",
     *                 type="array",
     *                 @OA\Items(type="integer"),
     *                 example={1, 2, 3, 4, 5},
     *                 description="Array de IDs de marcas a marcar como exportadas"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Marcas marcadas como exportadas exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Se marcaron 5 marcas como exportadas"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="updated_count", type="integer", example=5)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public static function markAsExported() {}

    /**
     * @OA\Get(
     *     path="/api/v1/export/statistics",
     *     summary="Estadísticas de exportación",
     *     description="Obtiene estadísticas sobre el estado de exportación de marcas. Requiere rol de manager.",
     *     tags={"Exportación"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Fecha de inicio (formato: Y-m-d). Por defecto: último mes.",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Fecha de fin (formato: Y-m-d). Por defecto: hoy.",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estadísticas obtenidas exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Estadísticas de exportación obtenidas exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer", example=200),
     *                 @OA\Property(property="exported", type="integer", example=150),
     *                 @OA\Property(property="not_exported", type="integer", example=50),
     *                 @OA\Property(property="export_percentage", type="number", format="float", example=75.0),
     *                 @OA\Property(
     *                     property="date_range",
     *                     type="object",
     *                     @OA\Property(property="start", type="string", format="date", example="2024-01-01"),
     *                     @OA\Property(property="end", type="string", format="date", example="2024-01-31")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public static function statistics() {}
}

