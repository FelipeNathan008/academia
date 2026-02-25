<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('frequencia_aluno', function (Blueprint $table) {

            if (Schema::hasColumn('frequencia_aluno', 'freq_alunos')) {
                $table->dropColumn('freq_alunos');
            }

            $table->unsignedBigInteger('matricula_id_matricula')->nullable();

            $table->string('freq_presenca', 20)->nullable();
            $table->date('freq_data_aula')->nullable();
            $table->string('freq_observacao')->nullable();

            $table->foreign('matricula_id_matricula')
                ->references('id_matricula')
                ->on('matricula')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('frequencia_aluno', function (Blueprint $table) {

            $table->dropForeign(['matricula_id_matricula']);

            $table->dropColumn([
                'matricula_id_matricula',
                'freq_presenca',
                'freq_data_aula',
                'freq_observacao'
            ]);

            $table->string('freq_alunos', 60)->nullable();
        });
    }
};
