<?php

namespace App\Http\Controllers\Api\V1\Devices;

use App\Application\Devices\DeviceService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Devices\StoreDeviceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function __construct(
        private DeviceService $deviceService
    ) {
    }

    /**
     * Lista todos los dispositivos con filtros opcionales
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['area_id', 'type', 'status']);
            
            // Paginación
            $perPage = $request->input('per_page', 15);
            $page = $request->input('page', 1);
            
            $devices = $this->deviceService->getAllDevices($filters);
            
            // Paginación manual
            $total = count($devices);
            $offset = ($page - 1) * $perPage;
            $paginatedDevices = array_slice($devices, $offset, $perPage);

            return response()->json([
                'success' => true,
                'message' => 'Dispositivos obtenidos exitosamente',
                'data' => $paginatedDevices,
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
     * Crea un nuevo dispositivo
     */
    public function store(StoreDeviceRequest $request): JsonResponse
    {
        try {
            $device = $this->deviceService->createDevice($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Dispositivo creado exitosamente',
                'data' => $device,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Muestra un dispositivo específico
     */
    public function show(string $id): JsonResponse
    {
        try {
            $device = $this->deviceService->getDeviceById((int) $id);

            if (!$device) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dispositivo no encontrado',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Dispositivo obtenido exitosamente',
                'data' => $device,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Desactiva un dispositivo
     */
    public function disable(string $id): JsonResponse
    {
        try {
            $device = $this->deviceService->disableDevice((int) $id);

            return response()->json([
                'success' => true,
                'message' => 'Dispositivo desactivado exitosamente',
                'data' => $device,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
