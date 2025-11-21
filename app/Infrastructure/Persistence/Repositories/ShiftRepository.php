<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Models\Attendance\Shift;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repositorio para gestión de turnos
 * Encapsula todas las consultas relacionadas con shifts
 */
class ShiftRepository
{
    /**
     * Busca un turno por ID
     */
    public function findById(int $id): ?Shift
    {
        return Shift::with(['worker', 'area'])->find($id);
    }

    /**
     * Busca turnos con filtros opcionales
     */
    public function searchByFilters(array $filters = []): Collection
    {
        $query = Shift::with(['worker', 'area']);

        if (isset($filters['worker_id'])) {
            $query->where('worker_id', $filters['worker_id']);
        }

        if (isset($filters['area_id'])) {
            $query->where('area_id', $filters['area_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['date'])) {
            $query->forDate($filters['date']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('start_at', [$filters['start_date'], $filters['end_date']]);
        }

        return $query->orderBy('start_at', 'desc')->get();
    }

    /**
     * Busca turnos que se solapan con un rango de tiempo para un trabajador
     */
    public function findOverlappingShift(int $workerId, Carbon $startAt, Carbon $endAt, ?int $excludeShiftId = null): ?Shift
    {
        $query = Shift::overlapping($startAt, $endAt, $workerId);

        if ($excludeShiftId) {
            $query->where('id', '!=', $excludeShiftId);
        }

        return $query->first();
    }

    /**
     * Obtiene turnos del día en estados específicos
     */
    public function getTodayShifts(array $statuses = ['planned', 'in_progress']): Collection
    {
        return Shift::with(['worker', 'area'])
            ->forDate(Carbon::today())
            ->whereIn('status', $statuses)
            ->get();
    }

    /**
     * Obtiene turnos de un trabajador para una semana
     */
    public function getWorkerShiftsForWeek(int $workerId, Carbon $weekStart): Collection
    {
        $weekEnd = $weekStart->copy()->endOfWeek();

        return Shift::with(['area'])
            ->where('worker_id', $workerId)
            ->whereBetween('start_at', [$weekStart, $weekEnd])
            ->orderBy('start_at', 'asc')
            ->get();
    }

    /**
     * Obtiene turnos del día que no tienen marcas asociadas
     */
    public function getShiftsWithoutMarksForDate(Carbon $date): Collection
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        return Shift::with(['worker', 'area'])
            ->forDate($date)
            ->withStatus('planned')
            ->whereDoesntHave('worker.marks', function ($query) use ($startOfDay, $endOfDay) {
                $query->whereBetween('marked_at', [$startOfDay, $endOfDay]);
            })
            ->get();
    }

    /**
     * Crea un nuevo turno
     */
    public function create(array $data): Shift
    {
        return Shift::create($data);
    }

    /**
     * Actualiza un turno
     */
    public function update(Shift $shift, array $data): bool
    {
        return $shift->update($data);
    }

    /**
     * Elimina un turno
     */
    public function delete(Shift $shift): bool
    {
        return $shift->delete();
    }

    /**
     * Verifica si un turno tiene marcas asociadas
     */
    public function hasMarks(Shift $shift): bool
    {
        $startOfDay = $shift->start_at->copy()->startOfDay();
        $endOfDay = $shift->start_at->copy()->endOfDay();

        $worker = $shift->worker;
        if (!$worker) {
            return false;
        }

        return $worker->marks()
            ->whereBetween('marked_at', [$startOfDay, $endOfDay])
            ->exists();
    }
}

