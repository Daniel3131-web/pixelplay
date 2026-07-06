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
        Schema::create('tournaments', function (Blueprint $table) {
            //-- Primary key
            $table->id();

            // Vincula este torneio a um evento pai. Se o evento for deletado, os torneios dele também serão.
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');

            //-- Criador do torneio (Utilizando o padrão moderno do Laravel)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            //-- Informações Básicas
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['valorant', 'cs2', 'lol', 'mlbb', 'ow2', 'mr']);

            //-- Regras de Vagas (Ajustado para Escalabilidade)
            $table->integer('max_participants')->default(4);
            $table->integer('current_participants')->default(0);

            //-- Status
            // $table->boolean('live')->default(false);
            // $table->enum('status', ['Aberto', 'Agendado', 'Em andamento', 'Finalizado'])->default('Agendado');

            //-- Link de Transmissão Específico do Torneio
            $table->string('streaming_url')->nullable();

            //-- Financeiro/Premiação
            $table->decimal('entrance_fee', 8, 2)->default(0.00); // Taxa de inscrição
            $table->decimal('awards', 8, 2);

            //-- Datas e Horários Cronológicos
            $table->date('start_date'); // Data de início do torneio
            $table->date('end_date');   // Data de término do torneio
            $table->time('start_time'); // Horário diário de início dos jogos
            $table->time('end_time');   // Horário estimado de término do dia

            //-- Datas de Inscrição
            $table->date('entry_date'); // Data limite para os times se inscreverem

            //-- Url da imagem do torneio
            $table->string('img')->nullable();

            //-- Tempo e data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};