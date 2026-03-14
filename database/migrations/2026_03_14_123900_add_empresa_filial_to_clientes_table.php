<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'responsavel',
            'aluno',
            'detalhes_aluno',
            'matricula',
            'detalhes_matricula',
            'mensalidade',
            'detalhes_mensalidade',
            'professor',
            'detalhes_professor',
            'horario_treino',
            'grade_horario',
            'frequencia_aluno',
            'graduacao',
            'modalidade',
            'preco_modalidade'
        ];

        foreach ($tables as $tableName) {

            Schema::table($tableName, function (Blueprint $table) {

                $table->foreignId('id_emp_id')
                    ->nullable()
                    ->constrained('empresas', 'id_empresa');

                $table->foreignId('id_filial_id')
                    ->nullable()
                    ->constrained('filiais', 'id_filial');

                $table->index(['id_emp_id', 'id_filial_id']);
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'responsavel',
            'aluno',
            'detalhes_aluno',
            'matricula',
            'detalhes_matricula',
            'mensalidade',
            'detalhes_mensalidade',
            'professor',
            'detalhes_professor',
            'horario_treino',
            'grade_horario',
            'frequencia_aluno',
            'graduacao',
            'modalidade',
            'preco_modalidade'
        ];

        foreach ($tables as $tableName) {

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {

                $table->dropForeign([$tableName . '_id_emp_id_foreign']);
                $table->dropForeign([$tableName . '_id_filial_id_foreign']);
                $table->dropColumn(['id_emp_id', 'id_filial_id']);
            });
        }
    }
};
