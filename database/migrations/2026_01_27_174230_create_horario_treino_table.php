<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horario_treino', function (Blueprint $table) {
            $table->increments('id_hora');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->string('hora_semana', 80);
            $table->string('hora_modalidade', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horario_treino');
    }
};
