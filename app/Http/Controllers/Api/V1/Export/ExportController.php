<?php

namespace App\Http\Controllers\Api\V1\Export;

use App\Application\Export\ExportService;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    use ApiResponse;

    public function __construct(
        private ExportService $exportService
    ) {
    }

    /**
     * Exporta marcas de asistencia para sistema legado
     */
    public function exportMarks(Request $request): JsonResponse
    {
        try {
            $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null;
            $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null;
            $onlyNotExported = $request->boolean('only_not_exported', true);
            
            // Paginación
            $perPage = $request->input('per_page', 100); // Por defecto 100 para exportación
            $page = $request->input('page', 1);

            $result = $this->exportService->exportMarksForLegacySystem($startDate, $endDate, $onlyNotExported, $perPage, $page);

            // Si tiene paginación, retornar con estructura de paginación
            if (isset($result['pagination'])) {
                return $this->successResponse(
                    [
                        'marks' => $result['data'],
                        'pagination' => $result['pagination'],
                    ],
                    'Marcas exportadas exitosamente'
                );
            }

            // Si no tiene paginación (compatibilidad hacia atrás)
            return $this->successResponse(
                [
                    'marks' => $result,
                    'count' => count($result),
                ],
                'Marcas exportadas exitosamente'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Marca marcas como exportadas
     */
    public function markAsExported(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'mark_ids' => 'required|array',
                'mark_ids.*' => 'integer|exists:marks,id',
            ]);

            $updated = $this->exportService->markAsExported($request->input('mark_ids'));

            return $this->successResponse(
                ['updated_count' => $updated],
                "Se marcaron {$updated} marcas como exportadas"
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Obtiene estadísticas de exportación
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null;
            $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null;

            $statistics = $this->exportService->getExportStatistics($startDate, $endDate);

            return $this->successResponse($statistics, 'Estadísticas de exportación obtenidas exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
