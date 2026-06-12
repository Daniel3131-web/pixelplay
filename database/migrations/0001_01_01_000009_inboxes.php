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
        Schema::create('inboxes', function (Blueprint $table) {
            //-- Primary Key
            $table->id();
            //-- Chaves Estrangeiras
            $table->foreignId('user_id')->constrained()->onDelete('cascade');;
            $table->foreignId('tournament_id')->nullable()->constrained()->onDelete('cascade');
            //--
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            //-- Tempo e data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inboxes');
    }
};
