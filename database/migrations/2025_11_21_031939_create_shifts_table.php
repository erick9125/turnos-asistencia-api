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
     * Crea la tabla de shifts (turnos de trabajo)
     * Un turno pertenece a un trabajador y un área
     */
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('workers')->onDelete('cascade');
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->enum('status', ['planned', 'in_progress', 'completed', 'inconsistent', 'absent'])->default('planned');
            $table->timestamps();
            $table->softDeletes(); // Eliminación suave
            
            // Índices para búsquedas y validaciones
            $table->index('worker_id');
            $table->index('area_id');
            $table->index('status');
            $table->index(['worker_id', 'start_at', 'end_at']);
            
            // Constraint: end_at debe ser mayor que start_at
            $table->check('end_at > start_at', 'shifts_end_after_start_check');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
