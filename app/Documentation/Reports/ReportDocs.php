<?php

namespace App\Documentation\Reports;

class ReportDocs 
{
    /**
     * @OA\Get(
     *     path="/api/v1/reports/attendance",
     *     summary="Reporte de asistencia",
     *     description="Genera un reporte de asistencia con información de turnos y marcas. Requiere rol de manager.",
     *     tags={"Reportes"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Fecha de inicio del reporte (formato: Y-m-d). Por defecto: inicio del mes actual.",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Fecha de fin del reporte (formato: Y-m-d). Por defecto: fin del mes actual.",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="worker_id",
     *         in="query",
     *         description="Filtrar por ID de trabajador",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="area_id",
     *         in="query",
     *         description="Filtrar por ID de área",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reporte de asistencia generado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reporte de asistencia generado exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="shift_id", type="integer"),
     *                     @OA\Property(property="worker_id", type="integer"),
     *                     @OA\Property(property="worker_name", type="string"),
     *                     @OA\Property(property="start_at", type="string", format="date-time"),
     *                     @OA\Property(property="end_at", type="string", format="date-time"),
     *                     @OA\Property(property="status", type="string"),
     *                     @OA\Property(property="marks_count", type="integer"),
     *                     @OA\Property(property="first_mark", type="string", format="date-time", nullable=true),
     *                     @OA\Property(property="last_mark", type="string", format="date-time", nullable=true)
     *                 )
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
    public static function attendance() {}

    /**
     * @OA\Get(
     *     path="/api/v1/reports/delays",
     *     summary="Reporte de atrasos",
     *     description="Genera un reporte de atrasos de trabajadores. Requiere rol de manager.",
     *     tags={"Reportes"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Fecha de inicio del reporte (formato: Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Fecha de fin del reporte (formato: Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reporte de atrasos generado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reporte de atrasos generado exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="shift_id", type="integer"),
     *                     @OA\Property(property="worker_id", type="integer"),
     *                     @OA\Property(property="worker_name", type="string"),
     *                     @OA\Property(property="scheduled_start", type="string", format="date-time"),
     *                     @OA\Property(property="actual_start", type="string", format="date-time"),
     *                     @OA\Property(property="delay_minutes", type="integer")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public static function delays() {}

    /**
     * @OA\Get(
     *     path="/api/v1/reports/overtime",
     *     summary="Reporte de horas extras",
     *     description="Genera un reporte de horas extras trabajadas. Requiere rol de manager.",
     *     tags={"Reportes"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Fecha de inicio del reporte (formato: Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Fecha de fin del reporte (formato: Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reporte de horas extras generado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reporte de horas extras generado exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="shift_id", type="integer"),
     *                     @OA\Property(property="worker_id", type="integer"),
     *                     @OA\Property(property="worker_name", type="string"),
     *                     @OA\Property(property="scheduled_end", type="string", format="date-time"),
     *                     @OA\Property(property="actual_end", type="string", format="date-time"),
     *                     @OA\Property(property="overtime_minutes", type="integer")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public static function overtime() {}
}
