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
        Schema::create('matches', function (Blueprint $table) {
            //-- Primary Key
            $table->id();
            //-- Chaves Estrangeiras
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->foreignId('map_id')->nullable()->constrained();
            $table->foreignId('team_a_id')->nullable()->constrained('teams');
            $table->foreignId('team_b_id')->nullable()->constrained('teams');
            $table->foreignId('winner_id')->nullable()->constrained('teams');
            //-- Pontuação
            $table->integer('score_a')->nullable();
            $table->integer('score_b')->nullable();
            // --- CONTROLE DOS TORNEIOS DUPLOS ---
            // Identifica o lado da chave: 'upper' (superior), 'lower' (inferior) ou 'grand_final'
            $table->enum('bracket_type', ['upper', 'lower', 'grand_final'])->default('upper');

            // Controla a rodada e a posição do jogo (Ex: Rodada 1, Jogo 4)
            $table->integer('round')->default(1);
            $table->integer('bracket_position')->default(1);

            $table->string('match_status')->default('Pendente');
            //-- Tempo e data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
