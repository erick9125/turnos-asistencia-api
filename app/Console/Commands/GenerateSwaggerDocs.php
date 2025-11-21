<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use L5Swagger\Generator;

/**
 * Comando personalizado para generar documentación Swagger
 * Suprime warnings de Swagger-php sobre clases "unknown"
 */
class GenerateSwaggerDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swagger:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera la documentación Swagger suprimiendo warnings no críticos';

    /**
     * Execute the console command.
     */
    public function handle(Generator $generator)
    {
        // Configurar error handler temporal para suprimir warnings de Swagger-php
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            // Suprimir warnings sobre clases "unknown" de Swagger-php
            if (str_contains($errstr, 'Skipping unknown')) {
                return true; // Suprimir el error
            }
            return false; // Dejar que otros errores se procesen normalmente
        }, E_USER_WARNING);

        try {
            $this->info('Generando documentación Swagger...');
            $generator->generateDocs();
            $this->info('Documentación generada exitosamente en storage/api-docs/api-docs.json');
        } catch (\Exception $e) {
            // Si el error no es sobre "Skipping unknown", mostrarlo
            if (!str_contains($e->getMessage(), 'Skipping unknown')) {
                $this->error('Error al generar documentación: ' . $e->getMessage());
                return 1;
            }
            // Si es solo un warning sobre "Skipping unknown", intentar generar de todas formas
            $this->warn('Advertencia: ' . $e->getMessage());
            $this->info('Intentando generar documentación de todas formas...');
            try {
                $generator->generateDocs();
                $this->info('Documentación generada exitosamente (con advertencias)');
            } catch (\Exception $e2) {
                $this->error('Error al generar documentación: ' . $e2->getMessage());
                return 1;
            }
        } finally {
            restore_error_handler();
        }

        return 0;
    }
}
