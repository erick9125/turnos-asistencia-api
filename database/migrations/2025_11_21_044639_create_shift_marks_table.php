<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Crea la tabla intermedia shift_marks para asociación explícita entre turnos y marcas
     * Mejora la trazabilidad y facilita consultas para sistema legado
     */
    public function up(): void
    {
        Schema::create('shift_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('cascade');
            $table->foreignId('mark_id')->constrained('marks')->onDelete('cascade');
            $table->enum('mark_type', ['start', 'end', 'other'])->default('other'); // Tipo de marca: inicio, fin, u otra
            $table->timestamps();

            // Índices para búsquedas
            $table->index('shift_id');
            $table->index('mark_id');
            $table->index(['shift_id', 'mark_type']);
            
            // Constraint único: una marca solo puede estar asociada una vez a un turno
            $table->unique(['shift_id', 'mark_id'], 'shift_marks_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_marks');
    }
};
