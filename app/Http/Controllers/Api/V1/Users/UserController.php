<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Application\Users\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(
        private UserService $userService
    ) {
    }

    /**
     * Lista todos los usuarios con filtros opcionales
     * Solo admin
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['role', 'status', 'search']);
            
            // Paginación
            $perPage = $request->input('per_page', 15);
            $page = $request->input('page', 1);
            
            $users = $this->userService->getAllUsers($filters);
            
            // Paginación manual
            $total = count($users);
            $offset = ($page - 1) * $perPage;
            $paginatedUsers = array_slice($users, $offset, $perPage);

            return $this->successResponse(
                [
                    'users' => $paginatedUsers,
                    'pagination' => [
                        'total' => $total,
                        'per_page' => $perPage,
                        'current_page' => $page,
                        'last_page' => ceil($total / $perPage),
                    ],
                ],
                'Usuarios obtenidos exitosamente'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Crea un nuevo usuario
     * Solo admin
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->createUser($request->validated());

            return $this->successResponse($user, 'Usuario creado exitosamente', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Muestra un usuario específico
     * Admin o el mismo usuario
     */
    public function show(Request $request, string $id): JsonResponse
    {
        try {
            $user = $this->userService->getUserById((int) $id);

            if (!$user) {
                return $this->notFoundResponse('Usuario no encontrado');
            }

            // Verificar que el usuario autenticado sea admin o el mismo usuario
            $authUser = $request->user();
            if (!$authUser->isAdmin() && $authUser->id != (int) $id) {
                return $this->forbiddenResponse('No tienes permisos para ver este usuario');
            }

            return $this->successResponse($user, 'Usuario obtenido exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Actualiza un usuario (rol o estado)
     * Solo admin
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        try {
            $user = $this->userService->updateUser((int) $id, $request->validated());

            return $this->successResponse($user, 'Usuario actualizado exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Elimina un usuario
     * Solo admin
     * No borrar si está asociado a worker activo
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        try {
            $this->userService->deleteUser((int) $id);

            return $this->successResponse(null, 'Usuario eliminado exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
