<?php

namespace App\Http\Controllers\Api\V1\Reports;

use App\Application\Reports\ReportService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) {
    }

    /**
     * Genera reporte de asistencia
     */
    public function attendance(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['start_date', 'end_date', 'worker_id', 'area_id', 'per_page', 'page']);
            
            // Paginación por defecto
            if (!isset($filters['per_page'])) {
                $filters['per_page'] = 15;
            }
            if (!isset($filters['page'])) {
                $filters['page'] = 1;
            }
            
            $result = $this->reportService->getAttendanceReport($filters);

            // Si tiene paginación, retornar con estructura de paginación
            if (isset($result['pagination'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Reporte de asistencia generado exitosamente',
                    'data' => $result['data'],
                    'pagination' => $result['pagination'],
                ]);
            }

            // Compatibilidad hacia atrás (sin paginación)
            return response()->json([
                'success' => true,
                'message' => 'Reporte de asistencia generado exitosamente',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Genera reporte de atrasos
     */
    public function delays(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['start_date', 'end_date', 'worker_id', 'area_id', 'per_page', 'page']);
            
            // Paginación por defecto
            if (!isset($filters['per_page'])) {
                $filters['per_page'] = 15;
            }
            if (!isset($filters['page'])) {
                $filters['page'] = 1;
            }
            
            $result = $this->reportService->getDelaysReport($filters);

            // Si tiene paginación, retornar con estructura de paginación
            if (isset($result['pagination'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Reporte de atrasos generado exitosamente',
                    'data' => $result['data'],
                    'pagination' => $result['pagination'],
                ]);
            }

            // Compatibilidad hacia atrás (sin paginación)
            return response()->json([
                'success' => true,
                'message' => 'Reporte de atrasos generado exitosamente',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Genera reporte de horas extras
     */
    public function overtime(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['start_date', 'end_date', 'worker_id', 'area_id', 'per_page', 'page']);
            
            // Paginación por defecto
            if (!isset($filters['per_page'])) {
                $filters['per_page'] = 15;
            }
            if (!isset($filters['page'])) {
                $filters['page'] = 1;
            }
            
            $result = $this->reportService->getOvertimeReport($filters);

            // Si tiene paginación, retornar con estructura de paginación
            if (isset($result['pagination'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Reporte de horas extras generado exitosamente',
                    'data' => $result['data'],
                    'pagination' => $result['pagination'],
                ]);
            }

            // Compatibilidad hacia atrás (sin paginación)
            return response()->json([
                'success' => true,
                'message' => 'Reporte de horas extras generado exitosamente',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
