<?php

namespace App\Documentation\Attendance;

class ShiftDocs 
{
    /**
     * @OA\Get(
     *     path="/api/v1/shifts",
     *     summary="Listar turnos",
     *     description="Obtiene una lista de turnos con filtros opcionales. Requiere rol de manager.",
     *     tags={"Turnos"},
     *     security={{"sanctum": {}}},
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
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar por estado",
     *         required=false,
     *         @OA\Schema(type="string", enum={"planned", "in_progress", "completed", "inconsistent", "absent"})
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Filtrar por fecha (formato: Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Fecha de inicio del rango",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Fecha de fin del rango",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de turnos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Turnos obtenidos exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Shift")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Sin permisos (requiere rol manager)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public static function index() {}

    /**
     * @OA\Post(
     *     path="/api/v1/shifts",
     *     summary="Crear turno",
     *     description="Crea un nuevo turno. Valida que no se solape con otros turnos del mismo trabajador. Requiere rol de manager.",
     *     tags={"Turnos"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ShiftStore")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Turno creado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Turno creado exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Shift")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación o regla de negocio",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Sin permisos (requiere rol manager)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public static function store() {}

    /**
     * @OA\Get(
     *     path="/api/v1/shifts/{id}",
     *     summary="Obtener turno",
     *     description="Obtiene los detalles de un turno específico. Requiere rol de manager.",
     *     tags={"Turnos"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del turno",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del turno",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Turno obtenido exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Shift")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Turno no encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public static function show() {}

    /**
     * @OA\Put(
     *     path="/api/v1/shifts/{id}",
     *     summary="Actualizar turno",
     *     description="Actualiza un turno existente. Valida que no se solape con otros turnos. Requiere rol de manager.",
     *     tags={"Turnos"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del turno",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ShiftUpdate")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Turno actualizado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Turno actualizado exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Shift")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación o regla de negocio",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public static function update() {}

    /**
     * @OA\Delete(
     *     path="/api/v1/shifts/{id}",
     *     summary="Eliminar turno",
     *     description="Elimina un turno. Solo se puede eliminar si no tiene marcas asociadas. Requiere rol de manager.",
     *     tags={"Turnos"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del turno",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Turno eliminado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Turno eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="No se puede eliminar (tiene marcas asociadas)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public static function destroy() {}

    /**
     * @OA\Get(
     *     path="/api/v1/workers/{id}/shifts/week",
     *     summary="Turnos semanales de un trabajador",
     *     description="Obtiene los turnos de un trabajador para una semana específica. Requiere rol de worker.",
     *     tags={"Turnos"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del trabajador",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="week_start",
     *         in="query",
     *         description="Fecha de inicio de la semana (formato: Y-m-d). Si no se proporciona, usa la semana actual.",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de turnos de la semana",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Turnos obtenidos exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Shift")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Sin permisos (requiere rol worker)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public static function getShiftsForWeek() {}
}
