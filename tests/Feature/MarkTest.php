<?php

namespace Tests\Feature;

use App\Models\Attendance\Device;
use App\Models\Attendance\Mark;
use App\Models\Attendance\Worker;
use App\Models\Client\Area;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Tests de gestión de marcas
 * 
 * Verifica el registro correcto de marcas y el rechazo de duplicados
 */
class MarkTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Registro de una marca remota correcta
     * 
     * Verifica que un worker puede crear una marca remota
     * cuando se cumplen todas las validaciones
     */
    public function test_can_create_valid_remote_mark(): void
    {
        // Arrange: Crear usuario worker autenticado
        $user = User::factory()->create([
            'role' => 'worker',
        ]);
        Sanctum::actingAs($user);

        // Crear datos necesarios
        $area = Area::factory()->create();
        $worker = Worker::factory()->create([
            'area_id' => $area->id,
            'status' => 'active',
        ]);
        $device = Device::factory()->create([
            'area_id' => $area->id,
            'status' => 'active',
            'type' => 'logical',
        ]);

        $markedAt = Carbon::now();

        // Act: Crear una marca remota
        $response = $this->postJson('/api/v1/marks/remote', [
            'worker_id' => $worker->id,
            'device_id' => $device->id,
            'direction' => 'in',
            'marked_at' => $markedAt->toDateTimeString(),
        ]);

        // Assert: Verificar que la marca se creó correctamente
        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'worker_id',
                    'device_id',
                    'direction',
                    'source_type',
                    'marked_at',
                ],
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'worker_id' => $worker->id,
                    'device_id' => $device->id,
                    'direction' => 'in',
                    'source_type' => 'remote',
                ],
            ]);

        // Verificar que la marca existe en la base de datos
        $this->assertDatabaseHas('marks', [
            'worker_id' => $worker->id,
            'device_id' => $device->id,
            'direction' => 'in',
            'source_type' => 'remote',
        ]);
    }

    /**
     * Test: Rechazo de marca duplicada
     * 
     * Verifica que el sistema rechaza la creación de una marca
     * duplicada (mismo trabajador + mismo sentido + mismo minuto)
     */
    public function test_cannot_create_duplicate_mark(): void
    {
        // Arrange: Crear usuario worker autenticado
        $user = User::factory()->create([
            'role' => 'worker',
        ]);
        Sanctum::actingAs($user);

        // Crear datos necesarios
        $area = Area::factory()->create();
        $worker = Worker::factory()->create([
            'area_id' => $area->id,
            'status' => 'active',
        ]);
        $device = Device::factory()->create([
            'area_id' => $area->id,
            'status' => 'active',
            'type' => 'logical',
        ]);

        $markedAt = Carbon::now();
        $truncatedMinute = $markedAt->copy()->startOfMinute();

        // Crear una marca existente
        Mark::create([
            'worker_id' => $worker->id,
            'device_id' => $device->id,
            'direction' => 'in',
            'source_type' => 'remote',
            'marked_at' => $markedAt,
            'truncated_minute' => $truncatedMinute,
        ]);

        // Act: Intentar crear una marca duplicada (mismo minuto)
        $response = $this->postJson('/api/v1/marks/remote', [
            'worker_id' => $worker->id,
            'device_id' => $device->id,
            'direction' => 'in',
            'marked_at' => $markedAt->copy()->addSeconds(30)->toDateTimeString(), // Mismo minuto
        ]);

        // Assert: Verificar que se rechazó la marca duplicada
        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonFragment([
                'message' => 'Ya existe una marca duplicada para este trabajador, sentido y minuto.',
            ]);
    }
}

