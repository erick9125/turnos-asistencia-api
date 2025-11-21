<?php

namespace App\Application\Reports;

use App\Infrastructure\Persistence\Repositories\MarkRepository;
use App\Infrastructure\Persistence\Repositories\ShiftRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Servicio de aplicación para generación de reportes
 * Contiene la lógica de negocio para reportes de asistencia
 */
class ReportService
{
    public function __construct(
        private ShiftRepository $shiftRepository,
        private MarkRepository $markRepository
    ) {
    }

    /**
     * Genera reporte de asistencia
     * 
     * @param array $filters Filtros incluyendo paginación (per_page, page)
     * @return array Con 'data' y 'pagination' si se usa paginación
     */
    public function getAttendanceReport(array $filters): array
    {
        $startDate = isset($filters['start_date']) ? Carbon::parse($filters['start_date']) : Carbon::now()->startOfMonth();
        $endDate = isset($filters['end_date']) ? Carbon::parse($filters['end_date']) : Carbon::now()->endOfMonth();

        $shifts = $this->shiftRepository->searchByFilters([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $report = [];
        foreach ($shifts as $shift) {
            $marks = $this->markRepository->getMarksForShift(
                $shift->worker_id,
                $shift->start_at,
                $shift->end_at
            );

            $report[] = [
                'shift_id' => $shift->id,
                'worker_id' => $shift->worker_id,
                'worker_name' => $shift->worker->name,
                'start_at' => $shift->start_at,
                'end_at' => $shift->end_at,
                'status' => $shift->status,
                'marks_count' => $marks->count(),
                'first_mark' => $marks->first()?->marked_at,
                'last_mark' => $marks->last()?->marked_at,
            ];
        }

        // Aplicar paginación si se especifica
        if (isset($filters['per_page']) && $filters['per_page'] > 0) {
            $perPage = (int) $filters['per_page'];
            $page = isset($filters['page']) ? (int) $filters['page'] : 1;
            $total = count($report);
            $offset = ($page - 1) * $perPage;
            $paginatedReport = array_slice($report, $offset, $perPage);

            return [
                'data' => $paginatedReport,
                'pagination' => [
                    'total' => $total,
                    'per_page' => $perPage,
                    'current_page' => $page,
                    'last_page' => ceil($total / $perPage),
                ],
            ];
        }

        return $report;
    }

    /**
     * Genera reporte de atrasos
     * 
     * @param array $filters Filtros incluyendo paginación (per_page, page)
     * @return array Con 'data' y 'pagination' si se usa paginación
     */
    public function getDelaysReport(array $filters): array
    {
        $startDate = isset($filters['start_date']) ? Carbon::parse($filters['start_date']) : Carbon::now()->startOfMonth();
        $endDate = isset($filters['end_date']) ? Carbon::parse($filters['end_date']) : Carbon::now()->endOfMonth();

        $shifts = $this->shiftRepository->searchByFilters([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $delays = [];
        foreach ($shifts as $shift) {
            $firstIn = $this->markRepository->getFirstInMark(
                $shift->worker_id,
                $shift->start_at->copy()->subHour(),
                $shift->end_at->copy()->addHour()
            );

            if ($firstIn && $firstIn->marked_at->isAfter($shift->start_at)) {
                $delayMinutes = $shift->start_at->diffInMinutes($firstIn->marked_at);

                $delays[] = [
                    'shift_id' => $shift->id,
                    'worker_id' => $shift->worker_id,
                    'worker_name' => $shift->worker->name,
                    'scheduled_start' => $shift->start_at,
                    'actual_start' => $firstIn->marked_at,
                    'delay_minutes' => $delayMinutes,
                ];
            }
        }

        // Aplicar paginación si se especifica
        if (isset($filters['per_page']) && $filters['per_page'] > 0) {
            $perPage = (int) $filters['per_page'];
            $page = isset($filters['page']) ? (int) $filters['page'] : 1;
            $total = count($delays);
            $offset = ($page - 1) * $perPage;
            $paginatedDelays = array_slice($delays, $offset, $perPage);

            return [
                'data' => $paginatedDelays,
                'pagination' => [
                    'total' => $total,
                    'per_page' => $perPage,
                    'current_page' => $page,
                    'last_page' => ceil($total / $perPage),
                ],
            ];
        }

        return $delays;
    }

    /**
     * Genera reporte de horas extras
     * 
     * @param array $filters Filtros incluyendo paginación (per_page, page)
     * @return array Con 'data' y 'pagination' si se usa paginación
     */
    public function getOvertimeReport(array $filters): array
    {
        $startDate = isset($filters['start_date']) ? Carbon::parse($filters['start_date']) : Carbon::now()->startOfMonth();
        $endDate = isset($filters['end_date']) ? Carbon::parse($filters['end_date']) : Carbon::now()->endOfMonth();

        $shifts = $this->shiftRepository->searchByFilters([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $overtime = [];
        foreach ($shifts as $shift) {
            $lastOut = $this->markRepository->getLastOutMark(
                $shift->worker_id,
                $shift->start_at->copy()->subHour(),
                $shift->end_at->copy()->addHour()
            );

            if ($lastOut && $lastOut->marked_at->isAfter($shift->end_at)) {
                $overtimeMinutes = $shift->end_at->diffInMinutes($lastOut->marked_at);

                $overtime[] = [
                    'shift_id' => $shift->id,
                    'worker_id' => $shift->worker_id,
                    'worker_name' => $shift->worker->name,
                    'scheduled_end' => $shift->end_at,
                    'actual_end' => $lastOut->marked_at,
                    'overtime_minutes' => $overtimeMinutes,
                ];
            }
        }

        // Aplicar paginación si se especifica
        if (isset($filters['per_page']) && $filters['per_page'] > 0) {
            $perPage = (int) $filters['per_page'];
            $page = isset($filters['page']) ? (int) $filters['page'] : 1;
            $total = count($overtime);
            $offset = ($page - 1) * $perPage;
            $paginatedOvertime = array_slice($overtime, $offset, $perPage);

            return [
                'data' => $paginatedOvertime,
                'pagination' => [
                    'total' => $total,
                    'per_page' => $perPage,
                    'current_page' => $page,
                    'last_page' => ceil($total / $perPage),
                ],
            ];
        }

        return $overtime;
    }
}

