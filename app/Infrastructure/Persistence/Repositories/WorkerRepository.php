<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Models\Attendance\Worker;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repositorio para gestión de trabajadores
 * Encapsula todas las consultas relacionadas con workers
 */
class WorkerRepository
{
    /**
     * Busca un trabajador por ID
     */
    public function findById(int $id): ?Worker
    {
        return Worker::with(['area'])->find($id);
    }

    /**
     * Busca un trabajador por RUT
     */
    public function findByRut(string $rut): ?Worker
    {
        return Worker::with(['area'])->where('rut', $rut)->first();
    }

    /**
     * Busca trabajadores con filtros opcionales
     */
    public function searchByFilters(array $filters = []): Collection
    {
        $query = Worker::with(['area']);

        if (isset($filters['area_id'])) {
            $query->where('area_id', $filters['area_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('rut', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('name', 'asc')->get();
    }

    /**
     * Verifica si un trabajador está activo
     */
    public function isActive(int $workerId): bool
    {
        $worker = $this->findById($workerId);
        return $worker && $worker->status === 'active';
    }

    /**
     * Obtiene solo trabajadores activos
     */
    public function getActiveWorkers(): Collection
    {
        return Worker::with(['area'])->active()->get();
    }
}

