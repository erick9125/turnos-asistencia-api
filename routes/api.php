<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Devices\DeviceController;
use App\Http\Controllers\Api\V1\Export\ExportController;
use App\Http\Controllers\Api\V1\Marks\MarkController;
use App\Http\Controllers\Api\V1\Reports\ReportController;
use App\Http\Controllers\Api\V1\Shifts\ShiftController;
use App\Http\Controllers\Api\V1\Users\UserController;
use App\Http\Controllers\Api\V1\Workers\WorkerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas de la API para la aplicación.
| Todas las rutas están bajo el prefijo /api/v1
|
*/

Route::prefix('v1')->group(function () {
    // Autenticación (con rate limiting para prevenir fuerza bruta)
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login']);
    });

    // Rutas protegidas con autenticación Sanctum y rate limiting
    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        // Rutas para admins: gestión de usuarios
        Route::middleware('role:admin')->group(function () {
            Route::apiResource('users', UserController::class);
        });

        // Rutas para managers y admins: gestión de turnos, dispositivos y reportes
        Route::middleware('role:manager,admin')->group(function () {
            // Turnos (solo managers pueden crear/editar/eliminar)
            Route::apiResource('shifts', ShiftController::class);
            
            // Dispositivos (solo managers pueden gestionar)
            Route::apiResource('devices', DeviceController::class)->except(['update', 'destroy']);
            Route::patch('/devices/{id}/disable', [DeviceController::class, 'disable']);
            
            // Reportes (managers y admins pueden acceder)
            Route::get('/reports/attendance', [ReportController::class, 'attendance']);
            Route::get('/reports/delays', [ReportController::class, 'delays']);
            Route::get('/reports/overtime', [ReportController::class, 'overtime']);
            
            // Exportación para sistema legado (managers y admins)
            Route::get('/export/marks', [ExportController::class, 'exportMarks']);
            Route::post('/export/marks/mark-as-exported', [ExportController::class, 'markAsExported']);
            Route::get('/export/statistics', [ExportController::class, 'statistics']);
        });

        // Rutas para workers: planificación semanal y marcaje remoto
        Route::middleware('role:worker')->group(function () {
            // Planificación semanal (workers pueden ver sus turnos)
            Route::get('/workers/{id}/shifts/week', [WorkerController::class, 'getShiftsForWeek']);
            
            // Marcaje remoto (workers pueden crear marcas desde la app)
            Route::post('/marks/remote', [MarkController::class, 'createRemote']);
        });

        // Rutas compartidas (tanto managers como workers)
        Route::middleware('role:manager,worker')->group(function () {
            // Trabajadores (todos pueden ver listado y detalles)
            Route::apiResource('workers', WorkerController::class)->only(['index', 'show']);
        });

        // Marcas desde reloj biométrico (sin restricción de rol, pero requiere autenticación)
        Route::post('/marks/batch/clock', [MarkController::class, 'createBatchClock']);
    });

    // Rutas de integración externa (requieren API Key, no autenticación de usuario, con rate limiting)
    Route::middleware(['api.key', 'throttle:100,1'])->group(function () {
        Route::post('/marks/external', [MarkController::class, 'createExternal']);
    });
});

