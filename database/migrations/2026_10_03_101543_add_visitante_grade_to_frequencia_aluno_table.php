<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('frequencia_aluno', function (Blueprint $table) {
            $table->unsignedBigInteger('visitante_grade_id_visitante_grade')
                ->nullable()
                ->after('matricula_id_matricula');

            $table->foreign('visitante_grade_id_visitante_grade')
                ->references('id_visitante_grade')
                ->on('visitante_grade')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('frequencia_aluno', function (Blueprint $table) {
            $table->dropForeign(['visitante_grade_id_visitante_grade']);
            $table->dropColumn('visitante_grade_id_visitante_grade');
        });
    }
};
