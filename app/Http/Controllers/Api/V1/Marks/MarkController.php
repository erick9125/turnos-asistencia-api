<?php

namespace App\Http\Controllers\Api\V1\Marks;

use App\Application\Marks\MarkIngestionService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Marks\CreateBatchClockMarksRequest;
use App\Http\Requests\Marks\CreateExternalMarkRequest;
use App\Http\Requests\Marks\CreateRemoteMarkRequest;
use Illuminate\Http\JsonResponse;

class MarkController extends Controller
{
    public function __construct(
        private MarkIngestionService $markIngestionService
    ) {
    }

    /**
     * Crea una marca desde fuente remota (API)
     */
    public function createRemote(CreateRemoteMarkRequest $request): JsonResponse
    {
        try {
            $mark = $this->markIngestionService->createRemoteMark($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Marca creada exitosamente',
                'data' => $mark,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Crea múltiples marcas desde reloj (batch)
     */
    public function createBatchClock(CreateBatchClockMarksRequest $request): JsonResponse
    {
        try {
            $result = $this->markIngestionService->createBatchClockMarks($request->validated()['marks']);

            return response()->json([
                'success' => true,
                'message' => 'Marcas procesadas',
                'data' => $result,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Crea una marca desde fuente externa
     */
    public function createExternal(CreateExternalMarkRequest $request): JsonResponse
    {
        try {
            $mark = $this->markIngestionService->createExternalMark($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Marca externa creada exitosamente',
                'data' => $mark,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
