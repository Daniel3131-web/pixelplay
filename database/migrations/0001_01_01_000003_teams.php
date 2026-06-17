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
        Schema::create('teams', function (Blueprint $table) {
            //-- Primary Key
            $table->id();
            //--
            $table->string('name');
            $table->string('acronym', 5);
            $table->text('description')->default('Descrição vazia');
            $table->enum('privacy', ['public', 'private'])->default('public');
            $table->string('password')->nullable();
            $table->string('img');
            //-- Tempo e data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
