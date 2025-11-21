<?php

namespace App\Documentation\Devices;

class DeviceDocs 
{
    /**
     * @OA\Get(
     *     path="/api/v1/devices",
     *     summary="Listar dispositivos",
     *     description="Obtiene una lista de dispositivos con filtros opcionales. Requiere rol de manager.",
     *     tags={"Dispositivos"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="area_id",
     *         in="query",
     *         description="Filtrar por ID de área",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filtrar por tipo",
     *         required=false,
     *         @OA\Schema(type="string", enum={"clock", "logical", "external"})
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar por estado",
     *         required=false,
     *         @OA\Schema(type="string", enum={"active", "disabled"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de dispositivos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Dispositivos obtenidos exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Device")
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
    public static function index() {}

    /**
     * @OA\Post(
     *     path="/api/v1/devices",
     *     summary="Crear dispositivo",
     *     description="Crea un nuevo dispositivo de marcación. Requiere rol de manager.",
     *     tags={"Dispositivos"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/DeviceStore")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Dispositivo creado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Dispositivo creado exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Device")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public static function store() {}

    /**
     * @OA\Get(
     *     path="/api/v1/devices/{id}",
     *     summary="Obtener dispositivo",
     *     description="Obtiene los detalles de un dispositivo específico. Requiere rol de manager.",
     *     tags={"Dispositivos"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del dispositivo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del dispositivo",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Dispositivo obtenido exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Device")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Dispositivo no encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public static function show() {}

    /**
     * @OA\Patch(
     *     path="/api/v1/devices/{id}/disable",
     *     summary="Desactivar dispositivo",
     *     description="Desactiva un dispositivo cambiando su estado a 'disabled'. Requiere rol de manager.",
     *     tags={"Dispositivos"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del dispositivo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dispositivo desactivado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Dispositivo desactivado exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Device")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error al desactivar",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public static function disable() {}
}
