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
        Schema::create('game_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('clan_id')->nullable()->constrained()->onDelete('set null');
            $table->string('plarium_id')->unique();
            $table->string('player_name');
            $table->enum('role', ['leader', 'deputy', 'officer', 'member'])->default('member');
            $table->json('stats')->nullable();
            $table->enum('type', ['main', 'secondary', 'chinese account', 'friends program'])->default('secondary');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_accounts');
    }
};
