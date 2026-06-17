<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // detalhes_aluno
        Schema::table('detalhes_aluno', function (Blueprint $table) {
            $table->unsignedBigInteger('id_graduacao')->nullable()->after('aluno_id_aluno');

            $table->foreign('id_graduacao')
                ->references('id_graduacao')
                ->on('graduacao');
        });

        // detalhes_professor
        Schema::table('detalhes_professor', function (Blueprint $table) {
            $table->unsignedBigInteger('id_graduacao')->nullable()->after('professor_id_professor');

            $table->foreign('id_graduacao')
                ->references('id_graduacao')
                ->on('graduacao');
        });

        // Remover colunas antigas
        Schema::table('detalhes_aluno', function (Blueprint $table) {
            $table->dropColumn([
                'det_gradu_nome_cor',
                'det_grau',
                'det_modalidade'
            ]);
        });

        Schema::table('detalhes_professor', function (Blueprint $table) {
            $table->dropColumn([
                'det_gradu_nome_cor',
                'det_grau',
                'det_modalidade'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('detalhes_aluno', function (Blueprint $table) {
            $table->string('det_gradu_nome_cor')->nullable();
            $table->integer('det_grau')->nullable();
            $table->string('det_modalidade')->nullable();

            $table->dropForeign(['id_graduacao']);
            $table->dropColumn('id_graduacao');
        });

        Schema::table('detalhes_professor', function (Blueprint $table) {
            $table->string('det_gradu_nome_cor')->nullable();
            $table->integer('det_grau')->nullable();
            $table->string('det_modalidade')->nullable();

            $table->dropForeign(['id_graduacao']);
            $table->dropColumn('id_graduacao');
        });
    }
};
