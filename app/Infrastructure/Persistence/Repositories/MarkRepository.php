<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Models\Attendance\Mark;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repositorio para gestión de marcas
 * Encapsula todas las consultas relacionadas con marks
 */
class MarkRepository
{
    /**
     * Busca una marca por ID
     */
    public function findById(int $id): ?Mark
    {
        return Mark::with(['worker', 'device'])->find($id);
    }

    /**
     * Verifica si existe una marca duplicada
     * Mismo trabajador + mismo sentido + mismo minuto
     */
    public function existsDuplicate(int $workerId, string $direction, Carbon $truncatedMinute): bool
    {
        return Mark::where('worker_id', $workerId)
            ->where('direction', $direction)
            ->where('truncated_minute', $truncatedMinute)
            ->exists();
    }

    /**
     * Obtiene marcas para un turno en un rango de tiempo
     */
    public function getMarksForShift(int $workerId, Carbon $startAt, Carbon $endAt): Collection
    {
        return Mark::with(['device'])
            ->where('worker_id', $workerId)
            ->whereBetween('marked_at', [$startAt, $endAt])
            ->orderBy('marked_at', 'asc')
            ->get();
    }

    /**
     * Obtiene marcas de un trabajador en un rango de fechas
     */
    public function getMarksByWorkerAndDateRange(int $workerId, Carbon $startDate, Carbon $endDate): Collection
    {
        return Mark::with(['device'])
            ->where('worker_id', $workerId)
            ->inDateRange($startDate, $endDate)
            ->orderBy('marked_at', 'asc')
            ->get();
    }

    /**
     * Crea una nueva marca
     */
    public function createMark(array $data): Mark
    {
        return Mark::create($data);
    }

    /**
     * Crea múltiples marcas en batch
     */
    public function createBatch(array $marksData): bool
    {
        return Mark::insert($marksData);
    }

    /**
     * Obtiene la primera marca de entrada en un rango
     */
    public function getFirstInMark(int $workerId, Carbon $startAt, Carbon $endAt): ?Mark
    {
        return Mark::where('worker_id', $workerId)
            ->in()
            ->whereBetween('marked_at', [$startAt, $endAt])
            ->orderBy('marked_at', 'asc')
            ->first();
    }

    /**
     * Obtiene la última marca de salida en un rango
     */
    public function getLastOutMark(int $workerId, Carbon $startAt, Carbon $endAt): ?Mark
    {
        return Mark::where('worker_id', $workerId)
            ->out()
            ->whereBetween('marked_at', [$startAt, $endAt])
            ->orderBy('marked_at', 'desc')
            ->first();
    }
}

