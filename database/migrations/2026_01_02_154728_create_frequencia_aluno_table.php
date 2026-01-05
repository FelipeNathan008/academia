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
        Schema::create('frequencia_aluno', function (Blueprint $table) {
            $table->id('id_frequencia_aluno');
            $table->unsignedBigInteger('grade_horario_id_grade');
            $table->string('freq_alunos', 60);
            $table->timestamps();

            $table->foreign('grade_horario_id_grade')
                ->references('id_grade')
                ->on('grade_horario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frequencia_aluno');
    }
};
