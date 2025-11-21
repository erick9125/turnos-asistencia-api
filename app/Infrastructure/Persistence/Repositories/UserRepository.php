<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repositorio para gestión de usuarios
 * Encapsula todas las consultas relacionadas con users
 */
class UserRepository
{
    /**
     * Busca un usuario por ID
     */
    public function findById(int $id): ?User
    {
        return User::with('worker')->find($id);
    }

    /**
     * Busca un usuario por email
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Obtiene todos los usuarios con filtros opcionales
     */
    public function getAll(array $filters = []): Collection
    {
        $query = User::with('worker');

        if (isset($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%");
            });
        }

        return $query->orderBy('name', 'asc')->get();
    }

    /**
     * Crea un nuevo usuario
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Actualiza un usuario
     */
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Elimina un usuario
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Verifica si un usuario tiene un worker activo asociado
     */
    public function hasActiveWorker(User $user): bool
    {
        if (!$user->worker) {
            return false;
        }

        return $user->worker->status === 'active';
    }
}
