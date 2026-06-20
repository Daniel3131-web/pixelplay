<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {   
        // tabela pivo de times
        Schema::create('tournament_team', function (Blueprint $table) {
            $table->id();
            // ID do torneio
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('confirmado');
            // ID do time
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_team');
    }
};
