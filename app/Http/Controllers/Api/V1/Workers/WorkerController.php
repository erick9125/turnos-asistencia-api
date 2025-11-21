<?php

namespace App\Http\Controllers\Api\V1\Workers;

use App\Application\Shifts\ShiftService;
use App\Application\Workers\WorkerService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function __construct(
        private WorkerService $workerService,
        private ShiftService $shiftService
    ) {
    }

    /**
     * Lista todos los trabajadores con filtros opcionales
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['area_id', 'status', 'search']);
            
            // Paginación
            $perPage = $request->input('per_page', 15);
            $page = $request->input('page', 1);
            
            $workers = $this->workerService->getAllWorkers($filters);
            
            // Paginación manual
            $total = count($workers);
            $offset = ($page - 1) * $perPage;
            $paginatedWorkers = array_slice($workers, $offset, $perPage);

            return response()->json([
                'success' => true,
                'message' => 'Trabajadores obtenidos exitosamente',
                'data' => $paginatedWorkers,
                'pagination' => [
                    'total' => $total,
                    'per_page' => $perPage,
                    'current_page' => $page,
                    'last_page' => ceil($total / $perPage),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Muestra un trabajador específico
     */
    public function show(string $id): JsonResponse
    {
        try {
            $worker = $this->workerService->getWorkerById((int) $id);

            if (!$worker) {
                return response()->json([
                    'success' => false,
                    'message' => 'Trabajador no encontrado',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Trabajador obtenido exitosamente',
                'data' => $worker,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtiene los turnos de un trabajador para una semana
     */
    public function getShiftsForWeek(Request $request, string $id): JsonResponse
    {
        try {
            $weekStart = $request->input('week_start');
            $shifts = $this->shiftService->getWorkerShiftsForWeek((int) $id, $weekStart);

            return response()->json([
                'success' => true,
                'message' => 'Turnos obtenidos exitosamente',
                'data' => $shifts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
