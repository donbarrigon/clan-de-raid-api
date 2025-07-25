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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();       // Usuario que hizo la acción
            $table->string('model');                                 // Clase del modelo (indexado)
            $table->unsignedBigInteger('model_id');                  // ID del modelo (indexado)
            $table->string('action');                                // Acción realizada
            $table->json('changes')->nullable();                     // Cambios realizados (opcional)
            $table->timestamp('created_at')->useCurrent();           // created_at = hora de la acción

            $table->index('model');
            $table->index('model_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
