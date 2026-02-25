<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matricula', function (Blueprint $table) {

            // Remove colunas antigas (sem dropForeign)
            if (Schema::hasColumn('matricula', 'matri_professor')) {
                $table->dropColumn('matri_professor');
            }

            if (Schema::hasColumn('matricula', 'matri_turma')) {
                $table->dropColumn('matri_turma');
            }

            // Criar nova coluna
            $table->unsignedBigInteger('grade_id_grade')
                  ->after('aluno_id_aluno');

            // Criar foreign key correta
            $table->foreign('grade_id_grade')
                  ->references('id_grade')
                  ->on('grade_horario')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('matricula', function (Blueprint $table) {

            $table->dropForeign(['grade_id_grade']);
            $table->dropColumn('grade_id_grade');

            // Recriar campos antigos
            $table->unsignedBigInteger('matri_professor')->nullable();
            $table->unsignedBigInteger('matri_turma')->nullable();
        });
    }
};