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
        Schema::create('song_tutorials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('song_id')->constrained()->onDelete('cascade');
            $table->enum('instrument', ['Guitarra', 'Teclado', 'Violão', 'Bateria', 'Baixo', 'Sopro', 'Cordas']);
            $table->string('title')->nullable(); // Título do tutorial (opcional)
            $table->text('url'); // URL do tutorial
            $table->timestamps();

            // Garantir que não haja tutoriais duplicados para o mesmo instrumento na mesma música
            $table->unique(['song_id', 'instrument']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('song_tutorials');
    }
};
