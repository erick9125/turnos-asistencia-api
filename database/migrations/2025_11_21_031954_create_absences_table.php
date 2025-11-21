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
     * Crea la tabla de absences (ausencias de trabajadores)
     */
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->onDelete('cascade');
            $table->foreignId('worker_id')->constrained('workers')->onDelete('cascade');
            $table->date('date');
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Eliminación suave
            
            // Índices para búsquedas
            $table->index('shift_id');
            $table->index('worker_id');
            $table->index('date');
            $table->index(['worker_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
