<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('version')->nullable();
            $table->string('image')->nullable();
            $table->text('snippet')->nullable();
            $table->string('tone')->nullable();
            $table->string('tempo')->nullable();
            $table->string('measure')->nullable();
            $table->enum('type', ['hino', 'corinho', 'cantico', 'atual'])->nullable();
            $table->enum('intensity', ['lenta', 'media', 'rapida'])->nullable();
            $table->longText('bible_reference')->nullable();
            $table->string('link_youtube')->nullable();
            $table->string('link_spotify')->nullable();
            $table->string('link_drive')->nullable();
            $table->longText('chords')->nullable();
            $table->longText('lyrics')->nullable();
            $table->integer('times')->default(0);
            $table->foreignId('id_user')->constrained('users');
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('songs');
    }
};
