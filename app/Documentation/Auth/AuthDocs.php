<?php

namespace App\Documentation\Auth;

class AuthDocs 
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="Autenticación de usuario",
     *     description="Autentica un usuario y retorna un token Sanctum para acceder a los endpoints protegidos",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login exitoso",
     *         @OA\JsonContent(ref="#/components/schemas/LoginResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public static function login() {}
}
