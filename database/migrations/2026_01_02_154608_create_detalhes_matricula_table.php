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
        Schema::create('detalhes_matricula', function (Blueprint $table) {
            $table->id('id_det_matricula');
            $table->unsignedBigInteger('matricula_id_matricula');
            $table->unsignedBigInteger('modalidade_id_modalidade');
            $table->unsignedBigInteger('grade_horario_id_grade');
            $table->timestamps();

            $table->foreign('matricula_id_matricula')->references('id_matricula')->on('matricula');
            $table->foreign('modalidade_id_modalidade')->references('id_modalidade')->on('modalidade');
            $table->foreign('grade_horario_id_grade')->references('id_grade')->on('grade_horario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalhes_matricula');
    }
};
