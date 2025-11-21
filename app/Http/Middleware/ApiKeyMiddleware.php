<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para validar API Keys de integraciones externas
 * 
 * Este middleware valida que las peticiones desde sistemas externos
 * incluyan una API Key válida en el header X-API-KEY.
 * 
 * Propósito: Permitir que sistemas externos (relojes biométricos,
 * sistemas de terceros) se autentiquen sin necesidad de tokens
 * de usuario, usando una API Key compartida.
 * 
 * Uso: Aplicar a rutas de integración como /marks/external y
 * otras que requieran autenticación por API Key.
 */
class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'API Key requerida. Incluye el header X-API-KEY',
            ], 401);
        }

        // Validar contra la API Key configurada en .env
        $validApiKey = config('attendance.external_api_key');

        if (!$validApiKey || $apiKey !== $validApiKey) {
            return response()->json([
                'success' => false,
                'message' => 'API Key inválida',
            ], 401);
        }

        return $next($request);
    }
}

