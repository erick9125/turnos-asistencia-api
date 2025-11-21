<?php

namespace App\Application\Workers;

use App\Infrastructure\Persistence\Repositories\WorkerRepository;
use Illuminate\Support\Facades\Log;

/**
 * Servicio de aplicación para gestión de trabajadores
 * Contiene la lógica de negocio relacionada con workers
 */
class WorkerService
{
    public function __construct(
        private WorkerRepository $workerRepository
    ) {
    }

    /**
     * Obtiene todos los trabajadores con filtros opcionales
     */
    public function getAllWorkers(array $filters = []): array
    {
        return $this->workerRepository->searchByFilters($filters)->toArray();
    }

    /**
     * Obtiene un trabajador por ID
     */
    public function getWorkerById(int $id): ?array
    {
        $worker = $this->workerRepository->findById($id);
        return $worker ? $worker->toArray() : null;
    }

    /**
     * Obtiene solo trabajadores activos
     */
    public function getActiveWorkers(): array
    {
        return $this->workerRepository->getActiveWorkers()->toArray();
    }
}

