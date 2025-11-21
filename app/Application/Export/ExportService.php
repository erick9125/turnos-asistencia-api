<?php

namespace App\Application\Export;

use App\Infrastructure\Persistence\Repositories\MarkRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Servicio de aplicación para exportación de datos al sistema legado
 * Proporciona datos en formato compatible con sistemas antiguos
 */
class ExportService
{
    public function __construct(
        private MarkRepository $markRepository
    ) {
    }

    /**
     * Exporta marcas de asistencia para el sistema legado
     * 
     * Retorna datos con formato:
     * - worker_id
     * - device_id
     * - direction
     * - marked_at
     * - source (source_type)
     * - area_id (vía worker)
     * - branch_id (vía area)
     * - company_id (vía branch)
     * - holding_id (vía company)
     * - shift_id, shift_start_at, shift_end_at, shift_status (turno asociado)
     * 
     * @param Carbon|null $startDate Fecha de inicio (opcional, por defecto último mes)
     * @param Carbon|null $endDate Fecha de fin (opcional, por defecto hoy)
     * @param bool $onlyNotExported Si es true, solo exporta marcas no exportadas previamente
     * @param int|null $perPage Cantidad de registros por página (null = sin paginación)
     * @param int $page Número de página
     * @return array Con 'data' y 'pagination' si se usa paginación, o array directo si no
     */
    public function exportMarksForLegacySystem(?Carbon $startDate = null, ?Carbon $endDate = null, bool $onlyNotExported = true, ?int $perPage = null, int $page = 1): array
    {
        $startDate = $startDate ?? Carbon::now()->subMonth();
        $endDate = $endDate ?? Carbon::now();

        // Construir query con joins para obtener toda la información jerárquica y turnos asociados
        // Excluir registros eliminados suavemente (soft deletes)
        $query = DB::table('marks')
            ->whereNull('marks.deleted_at') // Excluir marcas eliminadas suavemente
            ->join('workers', 'marks.worker_id', '=', 'workers.id')
            ->whereNull('workers.deleted_at') // Excluir trabajadores eliminados suavemente
            ->join('areas', 'workers.area_id', '=', 'areas.id')
            ->whereNull('areas.deleted_at') // Excluir áreas eliminadas suavemente
            ->join('branches', 'areas.branch_id', '=', 'branches.id')
            ->whereNull('branches.deleted_at') // Excluir sucursales eliminadas suavemente
            ->join('companies', 'branches.company_id', '=', 'companies.id')
            ->whereNull('companies.deleted_at') // Excluir empresas eliminadas suavemente
            ->join('holdings', 'companies.holding_id', '=', 'holdings.id')
            ->whereNull('holdings.deleted_at') // Excluir holdings eliminados suavemente
            ->join('devices', 'marks.device_id', '=', 'devices.id')
            ->whereNull('devices.deleted_at') // Excluir dispositivos eliminados suavemente
            ->leftJoin('shift_marks', 'marks.id', '=', 'shift_marks.mark_id')
            ->leftJoin('shifts', 'shift_marks.shift_id', '=', 'shifts.id')
            ->whereNull('shifts.deleted_at') // Excluir turnos eliminados suavemente
            ->whereBetween('marks.marked_at', [$startDate, $endDate])
            ->select([
                'marks.id',
                'marks.worker_id',
                'marks.device_id',
                'marks.direction',
                'marks.marked_at',
                'marks.source_type as source',
                'marks.exported_at',
                'workers.area_id',
                'areas.branch_id',
                'branches.company_id',
                'companies.holding_id',
                'workers.name as worker_name',
                'workers.rut as worker_rut',
                'devices.name as device_name',
                'devices.device_key',
                'areas.name as area_name',
                'areas.code as area_code',
                'branches.name as branch_name',
                'branches.code as branch_code',
                'companies.name as company_name',
                'companies.rut as company_rut',
                'holdings.name as holding_name',
                // Información del turno asociado
                'shifts.id as shift_id',
                'shifts.start_at as shift_start_at',
                'shifts.end_at as shift_end_at',
                'shifts.status as shift_status',
            ])
            ->orderBy('marks.marked_at', 'asc');

        // Si solo se quieren marcas no exportadas
        if ($onlyNotExported) {
            $query->whereNull('marks.exported_at');
        }

        // Aplicar paginación si se especifica
        if ($perPage !== null) {
            $total = $query->count();
            $marks = $query->skip(($page - 1) * $perPage)->take($perPage)->get();
            
            return [
                'data' => $this->formatExportData($marks),
                'pagination' => [
                    'total' => $total,
                    'per_page' => $perPage,
                    'current_page' => $page,
                    'last_page' => ceil($total / $perPage),
                ],
            ];
        }

        $marks = $query->get();

        return $this->formatExportData($marks);
    }

    /**
     * Formatea los datos de exportación
     * 
     * @param \Illuminate\Support\Collection $marks
     * @return array
     */
    private function formatExportData($marks): array
    {
        $exportData = [];
        foreach ($marks as $mark) {
            $exportData[] = [
                'id' => $mark->id,
                'worker_id' => $mark->worker_id,
                'device_id' => $mark->device_id,
                'direction' => $mark->direction,
                'marked_at' => $mark->marked_at,
                'source' => $mark->source,
                'area_id' => $mark->area_id,
                'branch_id' => $mark->branch_id,
                'company_id' => $mark->company_id,
                'holding_id' => $mark->holding_id,
                // Datos adicionales para referencia
                'worker_name' => $mark->worker_name,
                'worker_rut' => $mark->worker_rut,
                'device_name' => $mark->device_name,
                'device_key' => $mark->device_key,
                'area_name' => $mark->area_name,
                'area_code' => $mark->area_code,
                'branch_name' => $mark->branch_name,
                'branch_code' => $mark->branch_code,
                'company_name' => $mark->company_name,
                'company_rut' => $mark->company_rut,
                'holding_name' => $mark->holding_name,
                // Información del turno asociado (si existe)
                'shift_id' => $mark->shift_id,
                'shift_start_at' => $mark->shift_start_at,
                'shift_end_at' => $mark->shift_end_at,
                'shift_status' => $mark->shift_status,
            ];
        }

        return $exportData;
    }

    /**
     * Marca las marcas como exportadas
     * 
     * @param array $markIds IDs de las marcas a marcar como exportadas
     * @return int Número de marcas actualizadas
     */
    public function markAsExported(array $markIds): int
    {
        return DB::table('marks')
            ->whereIn('id', $markIds)
            ->whereNull('exported_at')
            ->whereNull('deleted_at') // Solo marcar marcas no eliminadas suavemente
            ->update(['exported_at' => Carbon::now()]);
    }

    /**
     * Obtiene estadísticas de exportación
     * 
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @return array
     */
    public function getExportStatistics(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? Carbon::now()->subMonth();
        $endDate = $endDate ?? Carbon::now();

        $total = DB::table('marks')
            ->whereBetween('marked_at', [$startDate, $endDate])
            ->whereNull('deleted_at') // Excluir marcas eliminadas suavemente
            ->count();

        $exported = DB::table('marks')
            ->whereBetween('marked_at', [$startDate, $endDate])
            ->whereNotNull('exported_at')
            ->whereNull('deleted_at') // Excluir marcas eliminadas suavemente
            ->count();

        $notExported = $total - $exported;

        return [
            'total' => $total,
            'exported' => $exported,
            'not_exported' => $notExported,
            'export_percentage' => $total > 0 ? round(($exported / $total) * 100, 2) : 0,
            'date_range' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString(),
            ],
        ];
    }
}

