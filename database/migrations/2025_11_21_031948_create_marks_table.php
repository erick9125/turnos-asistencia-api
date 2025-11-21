<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     * 
     * Crea la tabla de marks (marcas de asistencia)
     * Previene duplicados: mismo trabajador + mismo sentido + mismo minuto
     */
    public function up(): void
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('workers')->onDelete('cascade');
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->enum('direction', ['in', 'out']);
            $table->enum('source_type', ['remote', 'clock', 'external'])->default('clock');
            $table->dateTime('marked_at');
            $table->dateTime('truncated_minute'); // Sin segundos para índice único
            $table->timestamps();
            $table->softDeletes(); // Eliminación suave
            
            // Índices para búsquedas
            $table->index('worker_id');
            $table->index('device_id');
            $table->index('marked_at');
            $table->index('truncated_minute');
            
            // Constraint único: previene duplicados (mismo worker + direction + minuto)
            $table->unique(['worker_id', 'direction', 'truncated_minute'], 'marks_unique_worker_direction_minute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
