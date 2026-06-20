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
        Schema::create('matches', function (Blueprint $table) {
            //-- Primary Key
            $table->id();
            //-- Chaves Estrangeiras
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->foreignId('map_id')->nullable()->constrained();
            $table->foreignId('team_a_id')->nullable()->constrained('teams');
            $table->foreignId('team_b_id')->nullable()->constrained('teams');
            $table->foreignId('winner_id')->nullable()->constrained('teams');;
            //--
            $table->integer('score_a')->nullable();
            $table->integer('score_b')->nullable();
            $table->enum('stage', ['Oitavas de Final','Quartas de Final', 'Semi Final', 'Final']);
            $table->string('order_of_keys');
            $table->enum('match_status', ['Agendada', 'Em Andamento', 'Finalizada', 'W.O.'])->default('Agendada');
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
