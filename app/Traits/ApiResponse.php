<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/**
 * Trait para respuestas API centralizadas
 * 
 * Proporciona métodos estándar para formatear respuestas JSON
 * siguiendo el formato: { success, message, data }
 * 
 * Esto evita repetir el formato en todos los controladores
 * y mantiene consistencia en toda la API.
 */
trait ApiResponse
{
    /**
     * Retorna una respuesta exitosa
     */
    protected function successResponse($data = null, string $message = 'Operación exitosa', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Retorna una respuesta de error
     */
    protected function errorResponse(string $message, int $statusCode = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Retorna una respuesta de recurso no encontrado
     */
    protected function notFoundResponse(string $message = 'Recurso no encontrado'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * Retorna una respuesta de no autorizado
     */
    protected function unauthorizedResponse(string $message = 'No autorizado'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }

    /**
     * Retorna una respuesta de prohibido
     */
    protected function forbiddenResponse(string $message = 'No tienes permisos para esta acción'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }
}

