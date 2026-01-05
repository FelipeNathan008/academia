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
        Schema::create('grade_horario', function (Blueprint $table) {
            $table->id('id_grade');
            $table->unsignedBigInteger('professor_id_professor');
            $table->integer('grade_modalidade');
            $table->string('grade_dia_semana', 80);
            $table->time('grade_inicio');
            $table->time('grade_fim');
            $table->string('grade_desc', 150);
            $table->timestamps();

            $table->foreign('professor_id_professor')
                ->references('id_professor')
                ->on('professor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_horario');
    }
};
