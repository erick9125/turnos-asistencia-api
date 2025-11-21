<?php

namespace App\Application\Shifts;

use App\Exceptions\InactiveWorkerException;
use App\Exceptions\OverlappingShiftException;
use App\Exceptions\ShiftHasMarksException;
use App\Infrastructure\Persistence\Repositories\ShiftRepository;
use App\Infrastructure\Persistence\Repositories\WorkerRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Servicio de aplicación para gestión de turnos
 * Contiene toda la lógica de negocio relacionada con shifts
 */
class ShiftService
{
    public function __construct(
        private ShiftRepository $shiftRepository,
        private WorkerRepository $workerRepository
    ) {
    }

    /**
     * Obtiene todos los turnos con filtros opcionales
     */
    public function getAllShifts(array $filters = []): array
    {
        return $this->shiftRepository->searchByFilters($filters)->toArray();
    }

    /**
     * Obtiene un turno por ID
     */
    public function getShiftById(int $id): ?array
    {
        $shift = $this->shiftRepository->findById($id);
        return $shift ? $shift->toArray() : null;
    }

    /**
     * Crea un nuevo turno
     * 
     * Reglas de negocio:
     * - No se puede crear un turno que se solape con otro del mismo trabajador
     * - No se puede crear un turno con un trabajador inactivo
     */
    public function createShift(array $data): array
    {
        // Validar que el trabajador existe y está activo
        $worker = $this->workerRepository->findById($data['worker_id']);
        if (!$worker) {
            throw new \Exception('El trabajador no existe.');
        }

        if ($worker->status !== 'active') {
            throw new InactiveWorkerException('No se puede crear un turno para un trabajador inactivo.');
        }

        // Validar que no se solape con otro turno
        $startAt = Carbon::parse($data['start_at']);
        $endAt = Carbon::parse($data['end_at']);

        $overlappingShift = $this->shiftRepository->findOverlappingShift(
            $data['worker_id'],
            $startAt,
            $endAt
        );

        if ($overlappingShift) {
            throw new OverlappingShiftException();
        }

        // Crear el turno
        $shift = $this->shiftRepository->create([
            'worker_id' => $data['worker_id'],
            'area_id' => $data['area_id'],
            'start_at' => $startAt,
            'end_at' => $endAt,
            'status' => 'planned',
        ]);

        return $shift->toArray();
    }

    /**
     * Actualiza un turno
     * 
     * Reglas de negocio:
     * - No se puede actualizar un turno que se solape con otro del mismo trabajador
     * - No se puede actualizar un turno con un trabajador inactivo
     */
    public function updateShift(int $id, array $data): array
    {
        $shift = $this->shiftRepository->findById($id);
        if (!$shift) {
            throw new \Exception('El turno no existe.');
        }

        // Validar que el trabajador existe y está activo
        if (isset($data['worker_id'])) {
            $worker = $this->workerRepository->findById($data['worker_id']);
            if (!$worker) {
                throw new \Exception('El trabajador no existe.');
            }

            if ($worker->status !== 'active') {
                throw new InactiveWorkerException('No se puede actualizar un turno con un trabajador inactivo.');
            }
        }

        // Validar que no se solape con otro turno
        $startAt = isset($data['start_at']) ? Carbon::parse($data['start_at']) : $shift->start_at;
        $endAt = isset($data['end_at']) ? Carbon::parse($data['end_at']) : $shift->end_at;
        $workerId = $data['worker_id'] ?? $shift->worker_id;

        $overlappingShift = $this->shiftRepository->findOverlappingShift(
            $workerId,
            $startAt,
            $endAt,
            $id
        );

        if ($overlappingShift) {
            throw new OverlappingShiftException();
        }

        // Actualizar el turno
        $updateData = [];
        if (isset($data['worker_id'])) {
            $updateData['worker_id'] = $data['worker_id'];
        }
        if (isset($data['area_id'])) {
            $updateData['area_id'] = $data['area_id'];
        }
        if (isset($data['start_at'])) {
            $updateData['start_at'] = $startAt;
        }
        if (isset($data['end_at'])) {
            $updateData['end_at'] = $endAt;
        }
        if (isset($data['status'])) {
            $updateData['status'] = $data['status'];
        }

        $this->shiftRepository->update($shift, $updateData);
        $shift->refresh();

        return $shift->toArray();
    }

    /**
     * Elimina un turno
     * 
     * Reglas de negocio:
     * - Solo se puede eliminar un turno si no tiene marcas asociadas
     */
    public function deleteShift(int $id): bool
    {
        $shift = $this->shiftRepository->findById($id);
        if (!$shift) {
            throw new \Exception('El turno no existe.');
        }

        // Validar que no tenga marcas asociadas
        if ($this->shiftRepository->hasMarks($shift)) {
            throw new ShiftHasMarksException();
        }

        return $this->shiftRepository->delete($shift);
    }

    /**
     * Obtiene los turnos de un trabajador para una semana
     */
    public function getWorkerShiftsForWeek(int $workerId, ?string $weekStart = null): array
    {
        $weekStartDate = $weekStart ? Carbon::parse($weekStart) : Carbon::now()->startOfWeek();

        $shifts = $this->shiftRepository->getWorkerShiftsForWeek($workerId, $weekStartDate);

        return $shifts->toArray();
    }
}

