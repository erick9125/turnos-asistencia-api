<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Tests de autenticación
 * 
 * Verifica el flujo de login exitoso y login fallido
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Login exitoso con credenciales válidas
     * 
     * Verifica que un usuario puede autenticarse correctamente
     * y recibir un token Sanctum válido
     */
    public function test_login_successful_with_valid_credentials(): void
    {
        // Arrange: Crear un usuario de prueba
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'worker',
        ]);

        // Act: Intentar hacer login
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // Assert: Verificar respuesta exitosa con token
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user',
                    'token',
                ],
            ])
            ->assertJson([
                'success' => true,
            ]);

        // Verificar que el token existe
        $this->assertNotEmpty($response->json('data.token'));
    }

    /**
     * Test: Login fallido con credenciales inválidas
     * 
     * Verifica que el sistema rechaza credenciales incorrectas
     * y retorna un error apropiado
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        // Arrange: Crear un usuario de prueba
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Act: Intentar hacer login con contraseña incorrecta
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // Assert: Verificar respuesta de error
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test: Login fallido con email inexistente
     * 
     * Verifica que el sistema rechaza emails que no existen
     */
    public function test_login_fails_with_nonexistent_email(): void
    {
        // Act: Intentar hacer login con email que no existe
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        // Assert: Verificar respuesta de error
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}

