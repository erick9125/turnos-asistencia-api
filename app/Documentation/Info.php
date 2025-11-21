<?php

namespace App\Documentation;

/**
 * @OA\Info(
 *     title="Sistema de Gestión de Turnos y Asistencia API",
 *     version="1.0.0",
 *     description="API REST para la gestión de turnos de trabajo y control de asistencia de trabajadores. Permite registrar marcas de entrada y salida desde diferentes fuentes (relojes biométricos, aplicaciones remotas, sistemas externos) y asociarlas automáticamente con los turnos programados.",
 *     @OA\Contact(
 *         email="support@example.com",
 *         name="Soporte Técnico"
 *     ),
 *     @OA\License(
 *         name="Propietario",
 *         url=""
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor de la API"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Autenticación mediante token Sanctum. Obtén el token mediante POST /api/v1/auth/login"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="apiKey",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-KEY",
 *     description="API Key para integraciones externas. Configurar en variable de entorno EXTERNAL_API_KEY"
 * )
 *
 * @OA\Tag(
 *     name="Autenticación",
 *     description="Endpoints de autenticación"
 * )
 *
 * @OA\Tag(
 *     name="Turnos",
 *     description="Gestión de turnos de trabajo"
 * )
 *
 * @OA\Tag(
 *     name="Marcas",
 *     description="Registro de marcas de asistencia"
 * )
 *
 * @OA\Tag(
 *     name="Trabajadores",
 *     description="Gestión de trabajadores"
 * )
 *
 * @OA\Tag(
 *     name="Dispositivos",
 *     description="Gestión de dispositivos de marcación"
 * )
 *
 * @OA\Tag(
 *     name="Reportes",
 *     description="Generación de reportes de asistencia"
 * )
 *
 * @OA\Tag(
 *     name="Exportación",
 *     description="Exportación de datos para sistema legado"
 * )
 *
 * @OA\Tag(
 *     name="Usuarios",
 *     description="Gestión de usuarios del sistema"
 * )
 *
 * @OA\Schema(
 *     schema="LoginRequest",
 *     type="object",
 *     required={"email", "password"},
 *     title="Login",
 *     description="Datos para autenticación",
 *     @OA\Property(property="email", type="string", format="email", example="manager@example.com"),
 *     @OA\Property(property="password", type="string", format="password", example="password123"),
 * )
 *
 * @OA\Schema(
 *     schema="LoginResponse",
 *     type="object",
 *     title="Respuesta de Login",
 *     description="Respuesta exitosa de autenticación",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Login exitoso"),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         @OA\Property(property="user", type="object"),
 *         @OA\Property(property="token", type="string", example="1|abcdef123456..."),
 *     ),
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     title="Respuesta de Error",
 *     description="Formato de respuesta cuando ocurre un error",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Error en la operación"),
 *     @OA\Property(property="errors", type="object", nullable=true, description="Errores de validación"),
 * )
 */
class Info 
{
    public static function info() {}
}
