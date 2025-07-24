<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clan_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clan_id')->constrained()->onDelete('cascade');
            $table->string('label')->nullable();                                                    // Identificador específico (nombre del jefe, tipo de evento, etc.)
            $table->integer('min_score')->default(0);                                                      // Puntuación mínima requerida
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');           // Importancia (baja, media, alta, crítica)
            $table->text('description')->nullable();                                                     // Descripción opcional
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clan_requirements');
    }
};
