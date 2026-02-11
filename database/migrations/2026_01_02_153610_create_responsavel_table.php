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
            $table->string('resp_nome', 120);
            $table->string('resp_parentesco', 60);
            $table->string('resp_cpf', 11);
            $table->string('resp_telefone', 20);
            $table->string('resp_email', 150);
            $table->string('resp_cep', 8);
            $table->string('resp_logradouro', 150);
            $table->string('resp_bairro', 150);
            $table->string('resp_cidade', 150);
            $table->string('resp_numero', 10);
            $table->string('resp_complemento', 150)->nullable();
            $table->timestamps();

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
