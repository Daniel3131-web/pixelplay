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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            
            // Relacionamento: Quem criou o evento (Organizador)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Informações Básicas e Banner
            $table->string('name', 255);
            $table->string('slug')->unique(); 
            $table->string('img')->nullable();
            $table->integer('max_capacity');

            // Logística e Distribuição
            $table->enum('type', ['online', 'presencial', 'corporativo']);
            $table->string('location');

            // Transmissão e Valores
            $table->string('streaming_url')->nullable();
            $table->decimal('entrance_fee', 8, 2)->default(0.00); // 8 dígitos no total, 2 decimais

            // Cronograma e Prazos
            $table->date('entry_date');   // Fim das inscrições
            $table->date('start_date');   // Data de início
            $table->date('end_date');     // Data de término
            $table->time('start_time');   // Horário de abertura
            $table->time('end_time');     // Horário estimado de término

            // Detalhes do Evento
            $table->text('description');  

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};