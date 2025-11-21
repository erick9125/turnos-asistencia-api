<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login del usuario
     * Devuelve token Sanctum y datos del usuario
     * Si el rol es worker, también devuelve worker_id si existe
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Verificar que el usuario esté activo
        if (!$user->isActive()) {
            throw ValidationException::withMessages([
                'email' => ['Tu cuenta está inactiva. Contacta al administrador.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        // Si es worker, obtener worker_id desde la tabla workers
        $workerId = null;
        if ($user->isWorker()) {
            $worker = $user->worker;
            $workerId = $worker ? $worker->id : null;
        }

        return response()->json([
            'success' => true,
            'message' => 'Login exitoso',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                ],
                'token' => $token,
                'worker_id' => $workerId,
            ],
        ]);
    }
}
