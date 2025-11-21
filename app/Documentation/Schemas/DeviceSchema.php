<?php

namespace App\Documentation\Schemas;

/**
 * @OA\Schema(
 *     schema="Device",
 *     type="object",
 *     title="Dispositivo",
 *     description="Modelo de dispositivo de marcación",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="area_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Reloj Biométrico Principal"),
 *     @OA\Property(property="device_key", type="string", example="CLOCK-001"),
 *     @OA\Property(property="type", type="string", enum={"clock", "logical", "external"}, example="clock"),
 *     @OA\Property(property="status", type="string", enum={"active", "disabled"}, example="active"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class DeviceSchema 
{
    public static function dummy() {}
}

/**
 * @OA\Schema(
 *     schema="DeviceStore",
 *     type="object",
 *     required={"area_id", "name", "device_key"},
 *     title="Crear Dispositivo",
 *     description="Datos para crear un nuevo dispositivo",
 *     @OA\Property(property="area_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Reloj Biométrico Principal"),
 *     @OA\Property(property="device_key", type="string", example="CLOCK-001", description="Clave única del dispositivo"),
 *     @OA\Property(property="type", type="string", enum={"clock", "logical", "external"}, example="clock"),
 *     @OA\Property(property="status", type="string", enum={"active", "disabled"}, example="active"),
 * )
 */
class DeviceStoreSchema 
{
    public static function dummy() {}
}
