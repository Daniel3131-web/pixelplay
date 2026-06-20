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
        Schema::create('player_infos', function (Blueprint $table) {
            //-- Primary Key
            $table->id();
            //-- Chaves Estrangeiras
            //-- Tem q salvar o time no player_info, para pegar o time que ele jogou na hora, em caso de troca
            $table->foreignId('user_id')->constrained();
            $table->foreignId('team_id')->constrained('teams');
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('character_id')->nullable()->constrained('characters');
            //--
            $table->integer('kill')->default(0);
            $table->integer('death')->default(0);
            $table->integer('assistance')->default(0);
            $table->integer('gold')->default(0);
            $table->integer('score')->default(0);
            //-- Tempo e data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_infos');
    }
};
