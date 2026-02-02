<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grade_horario', function (Blueprint $table) {

            // FK para horario_treino (tipo correto)
            $table->unsignedInteger('horario_treino_id_hora')
                  ->after('professor_id_professor');

            // Corrige tamanho da modalidade
            $table->string('grade_modalidade', 100)->change();

            // Nova coluna
            $table->string('grade_turma', 60)
                  ->after('grade_fim');

            // Foreign key
            $table->foreign('horario_treino_id_hora')
                ->references('id_hora')
                ->on('horario_treino')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    public function down(): void
    {
        Schema::table('grade_horario', function (Blueprint $table) {
            $table->dropForeign(['horario_treino_id_hora']);
            $table->dropColumn('horario_treino_id_hora');
            $table->dropColumn('grade_turma');
        });
    }
};
