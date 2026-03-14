<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalhes_filiais', function (Blueprint $table) {
            $table->id('id_det_filial');

            $table->foreignId('id_filial_id')
                ->unique()
                ->constrained('filiais', 'id_filial')
                ->cascadeOnDelete();

            $table->string('det_filial_cep', 15);
            $table->string('det_filial_logradouro');
            $table->string('det_filial_numero');
            $table->string('det_filial_complemento')->nullable();
            $table->string('det_filial_bairro');
            $table->string('det_filial_cidade');
            $table->string('det_filial_uf', 2);
            $table->string('det_filial_regiao');
            $table->string('det_filial_pais');
            $table->string('det_filial_cnpj', 20)->nullable();
            $table->string('det_filial_email');
            $table->string('det_filial_telefone');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalhes_filiais');
    }
};