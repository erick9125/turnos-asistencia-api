<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para validar roles de usuario
 * 
 * Este middleware separa las responsabilidades de acceso según el rol:
 * - admin: Acceso completo al sistema, puede gestionar usuarios
 * - manager: Puede gestionar turnos, dispositivos y reportes
 * - worker: Puede acceder a planificación semanal y marcaje remoto
 * 
 * Intención de negocio: Separar responsabilidades de acceso para mantener
 * la seguridad y claridad en los permisos del sistema.
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado',
            ], 401);
        }

        // Verificar que el usuario esté activo
        if (!$user->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Tu cuenta está inactiva. Contacta al administrador.',
            ], 403);
        }

        if (!in_array($user->role, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para acceder a este recurso',
            ], 403);
        }

        return $next($request);
    }
}

