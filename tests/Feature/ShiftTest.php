<?php

namespace Tests\Feature;

use App\Models\Attendance\Shift;
use App\Models\Attendance\Worker;
use App\Models\Client\Area;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Tests de gestión de turnos
 * 
 * Verifica la creación de turnos válidos y el rechazo de turnos inválidos
 */
class ShiftTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Creación de un turno válido
     * 
     * Verifica que un manager puede crear un turno correctamente
     * cuando se cumplen todas las reglas de negocio
     */
    public function test_can_create_valid_shift(): void
    {
        // Arrange: Crear usuario manager autenticado
        $manager = User::factory()->create([
            'role' => 'manager',
        ]);
        Sanctum::actingAs($manager);

        // Crear datos necesarios
        $area = Area::factory()->create();
        $worker = Worker::factory()->create([
            'area_id' => $area->id,
            'status' => 'active',
        ]);

        $startAt = Carbon::tomorrow()->setTime(8, 0);
        $endAt = Carbon::tomorrow()->setTime(17, 0);

        // Act: Crear un turno
        $response = $this->postJson('/api/v1/shifts', [
            'worker_id' => $worker->id,
            'area_id' => $area->id,
            'start_at' => $startAt->toDateTimeString(),
            'end_at' => $endAt->toDateTimeString(),
        ]);

        // Assert: Verificar que el turno se creó correctamente
        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'worker_id',
                    'area_id',
                    'start_at',
                    'end_at',
                    'status',
                ],
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'worker_id' => $worker->id,
                    'area_id' => $area->id,
                    'status' => 'planned',
                ],
            ]);

        // Verificar que el turno existe en la base de datos
        $this->assertDatabaseHas('shifts', [
            'worker_id' => $worker->id,
            'area_id' => $area->id,
            'status' => 'planned',
        ]);
    }

    /**
     * Test: Rechazo de turno por solapamiento
     * 
     * Verifica que el sistema rechaza la creación de un turno
     * que se solapa con otro turno existente del mismo trabajador
     */
    public function test_cannot_create_overlapping_shift(): void
    {
        // Arrange: Crear usuario manager autenticado
        $manager = User::factory()->create([
            'role' => 'manager',
        ]);
        Sanctum::actingAs($manager);

        // Crear datos necesarios
        $area = Area::factory()->create();
        $worker = Worker::factory()->create([
            'area_id' => $area->id,
            'status' => 'active',
        ]);

        $startAt = Carbon::tomorrow()->setTime(8, 0);
        $endAt = Carbon::tomorrow()->setTime(17, 0);

        // Crear un turno existente
        Shift::create([
            'worker_id' => $worker->id,
            'area_id' => $area->id,
            'start_at' => $startAt,
            'end_at' => $endAt,
            'status' => 'planned',
        ]);

        // Act: Intentar crear un turno que se solapa (9:00 - 18:00)
        $overlappingStart = Carbon::tomorrow()->setTime(9, 0);
        $overlappingEnd = Carbon::tomorrow()->setTime(18, 0);

        $response = $this->postJson('/api/v1/shifts', [
            'worker_id' => $worker->id,
            'area_id' => $area->id,
            'start_at' => $overlappingStart->toDateTimeString(),
            'end_at' => $overlappingEnd->toDateTimeString(),
        ]);

        // Assert: Verificar que se rechazó el turno
        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonFragment([
                'message' => 'El turno se solapa con otro turno existente del mismo trabajador.',
            ]);
    }

    /**
     * Test: Worker no puede crear turnos
     * 
     * Verifica que un usuario con rol worker no puede acceder
     * a la ruta de creación de turnos (solo managers)
     */
    public function test_worker_cannot_create_shift(): void
    {
        // Arrange: Crear usuario worker autenticado
        $worker = User::factory()->create([
            'role' => 'worker',
        ]);
        Sanctum::actingAs($worker);

        // Crear datos necesarios
        $area = Area::factory()->create();
        $attendanceWorker = Worker::factory()->create([
            'area_id' => $area->id,
            'status' => 'active',
        ]);

        $startAt = Carbon::tomorrow()->setTime(8, 0);
        $endAt = Carbon::tomorrow()->setTime(17, 0);

        // Act: Intentar crear un turno
        $response = $this->postJson('/api/v1/shifts', [
            'worker_id' => $attendanceWorker->id,
            'area_id' => $area->id,
            'start_at' => $startAt->toDateTimeString(),
            'end_at' => $endAt->toDateTimeString(),
        ]);

        // Assert: Verificar que se rechazó por falta de permisos
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'No tienes permisos para acceder a este recurso',
            ]);
    }
}

