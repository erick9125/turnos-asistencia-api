<?php

namespace App\Documentation\Schemas;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="Usuario",
 *     description="Modelo de usuario del sistema",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Juan Pérez"),
 *     @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *     @OA\Property(property="role", type="string", enum={"worker", "manager", "admin"}, example="worker"),
 *     @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="worker", type="object", nullable=true, description="Trabajador asociado (solo si role es worker)")
 * )
 */
class UserSchema 
{
    public static function dummy() {}
}

/**
 * @OA\Schema(
 *     schema="UserStore",
 *     type="object",
 *     required={"name", "email", "password", "role"},
 *     title="Crear Usuario",
 *     description="Datos para crear un nuevo usuario",
 *     @OA\Property(property="name", type="string", example="Juan Pérez", description="Nombre completo"),
 *     @OA\Property(property="email", type="string", format="email", example="user@example.com", description="Email único"),
 *     @OA\Property(property="password", type="string", format="password", example="password123", description="Contraseña (mínimo 8 caracteres)"),
 *     @OA\Property(property="role", type="string", enum={"worker", "manager", "admin"}, example="worker", description="Rol del usuario"),
 *     @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active", description="Estado del usuario (opcional)")
 * )
 */
class UserStoreSchema 
{
    public static function dummy() {}
}

/**
 * @OA\Schema(
 *     schema="UserUpdate",
 *     type="object",
 *     title="Actualizar Usuario",
 *     description="Datos para actualizar un usuario existente",
 *     @OA\Property(property="name", type="string", example="Juan Pérez", description="Nombre completo"),
 *     @OA\Property(property="email", type="string", format="email", example="user@example.com", description="Email único"),
 *     @OA\Property(property="password", type="string", format="password", example="newpassword123", description="Nueva contraseña (opcional)"),
 *     @OA\Property(property="role", type="string", enum={"worker", "manager", "admin"}, example="manager", description="Rol del usuario"),
 *     @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active", description="Estado del usuario")
 * )
 */
class UserUpdateSchema 
{
    public static function dummy() {}
}

