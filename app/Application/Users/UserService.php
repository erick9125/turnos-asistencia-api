<?php

namespace App\Application\Users;

use App\Exceptions\ShiftHasMarksException;
use App\Infrastructure\Persistence\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

/**
 * Servicio de aplicación para gestión de usuarios
 * Contiene toda la lógica de negocio relacionada con users
 */
class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * Obtiene todos los usuarios con filtros opcionales
     */
    public function getAllUsers(array $filters = []): array
    {
        return $this->userRepository->getAll($filters)->toArray();
    }

    /**
     * Obtiene un usuario por ID
     */
    public function getUserById(int $id): ?array
    {
        $user = $this->userRepository->findById($id);
        return $user ? $user->toArray() : null;
    }

    /**
     * Crea un nuevo usuario
     * 
     * Reglas de negocio:
     * - El password se hashea automáticamente
     * - El email debe ser único
     */
    public function createUser(array $data): array
    {
        // Hashear password si se proporciona
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Establecer status por defecto si no se proporciona
        if (!isset($data['status'])) {
            $data['status'] = 'active';
        }

        $user = $this->userRepository->create($data);

        return $user->toArray();
    }

    /**
     * Actualiza un usuario
     * 
     * Reglas de negocio:
     * - Solo se puede actualizar role y status
     * - Si se proporciona password, se hashea
     */
    public function updateUser(int $id, array $data): array
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            throw new \Exception('El usuario no existe.');
        }

        // Hashear password si se proporciona
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // No actualizar password si no se proporciona
            unset($data['password']);
        }

        // Solo permitir actualizar ciertos campos
        $allowedFields = ['name', 'email', 'password', 'role', 'status'];
        $updateData = array_intersect_key($data, array_flip($allowedFields));

        $this->userRepository->update($user, $updateData);
        $user->refresh();

        return $user->toArray();
    }

    /**
     * Elimina un usuario
     * 
     * Reglas de negocio:
     * - No se puede eliminar si está asociado a un worker activo
     */
    public function deleteUser(int $id): bool
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            throw new \Exception('El usuario no existe.');
        }

        // Validar que no tenga worker activo asociado
        if ($this->userRepository->hasActiveWorker($user)) {
            throw new ShiftHasMarksException('No se puede eliminar un usuario que está asociado a un trabajador activo.');
        }

        return $this->userRepository->delete($user);
    }
}
