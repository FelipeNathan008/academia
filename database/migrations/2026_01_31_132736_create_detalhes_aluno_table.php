<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detalhes_aluno', function (Blueprint $table) {
            $table->id('id_det_aluno');
            $table->unsignedBigInteger('aluno_id_aluno');
            $table->string('det_gradu_nome_cor', 80);
            $table->integer('det_grau');
            $table->string('det_modalidade', 100);
            $table->date('det_data');
            $table->timestamps();

            $table->foreign('aluno_id_aluno')
                  ->references('id_aluno')
                  ->on('aluno')
                  ->onDelete('no action')
                  ->onUpdate('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalhes_aluno');
    }
};
