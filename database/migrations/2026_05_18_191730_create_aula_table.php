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
        Schema::create('aula', function (Blueprint $table) {

            $table->id('id_aula');

            $table->unsignedBigInteger('professor_id');
            $table->unsignedBigInteger('grade_horario_id');

            $table->string('aula_posicao_ensino', 150);

            $table->date('aula_periodo_inicial');
            $table->date('aula_periodo_final');

            // EMPRESA
            $table->foreignId('id_emp_id')
                ->nullable()
                ->constrained('empresas', 'id_empresa');

            // FILIAL
            $table->foreignId('id_filial_id')
                ->nullable()
                ->constrained('filiais', 'id_filial');

            $table->timestamps();

            $table->foreign('professor_id')
                ->references('id_professor')
                ->on('professor')
                ->onDelete('cascade');

            $table->foreign('grade_horario_id')
                ->references('id_grade')
                ->on('grade_horario')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aula');
    }
};