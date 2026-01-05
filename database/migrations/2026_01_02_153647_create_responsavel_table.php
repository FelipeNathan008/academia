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
        Schema::create('responsavel', function (Blueprint $table) {
            $table->id('id_responsavel');
            $table->unsignedBigInteger('aluno_id_aluno');
            $table->string('resp_nome', 120);
            $table->string('resp_parentesco', 60);
            $table->string('resp_cpf', 11);
            $table->string('resp_logradouro', 150);
            $table->string('resp_bairro', 150);
            $table->string('resp_cidade', 150);
            $table->timestamps();

            $table->foreign('aluno_id_aluno')
                ->references('id_aluno')
                ->on('aluno');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsavel');
    }
};
