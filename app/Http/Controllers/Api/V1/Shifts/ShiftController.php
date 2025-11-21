<?php

namespace App\Http\Controllers\Api\V1\Shifts;

use App\Application\Shifts\ShiftService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shifts\StoreShiftRequest;
use App\Http\Requests\Shifts\UpdateShiftRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function __construct(
        private ShiftService $shiftService
    ) {
    }

    /**
     * Lista todos los turnos con filtros opcionales
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['worker_id', 'area_id', 'status', 'date', 'start_date', 'end_date']);
            
            // Paginación
            $perPage = $request->input('per_page', 15);
            $page = $request->input('page', 1);
            
            $shifts = $this->shiftService->getAllShifts($filters);
            
            // Paginación manual
            $total = count($shifts);
            $offset = ($page - 1) * $perPage;
            $paginatedShifts = array_slice($shifts, $offset, $perPage);

            return response()->json([
                'success' => true,
                'message' => 'Turnos obtenidos exitosamente',
                'data' => $paginatedShifts,
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
     * Crea un nuevo turno
     */
    public function store(StoreShiftRequest $request): JsonResponse
    {
        try {
            $shift = $this->shiftService->createShift($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Turno creado exitosamente',
                'data' => $shift,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Muestra un turno específico
     */
    public function show(string $id): JsonResponse
    {
        try {
            $shift = $this->shiftService->getShiftById((int) $id);

            if (!$shift) {
                return response()->json([
                    'success' => false,
                    'message' => 'Turno no encontrado',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Turno obtenido exitosamente',
                'data' => $shift,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Actualiza un turno
     */
    public function update(UpdateShiftRequest $request, string $id): JsonResponse
    {
        try {
            $shift = $this->shiftService->updateShift((int) $id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Turno actualizado exitosamente',
                'data' => $shift,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Elimina un turno
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->shiftService->deleteShift((int) $id);

            return response()->json([
                'success' => true,
                'message' => 'Turno eliminado exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
