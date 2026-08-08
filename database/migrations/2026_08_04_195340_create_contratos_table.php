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
        Schema::create('contratos', function (Blueprint $table) {

            $table->id('id_contrato');

            $table->unsignedBigInteger('empresa_id');

            $table->string('contrato_status', 30)->default('ativo');
            $table->string('contrato_situacao_financeira', 50)->default('Em dia');
            $table->string('contrato_forma_pagamento', 50);
            $table->unsignedTinyInteger('contrato_dia_vencimento');

            $table->dateTime('contrato_assinado_em');
            $table->dateTime('contrato_encerrado_em')->nullable();

            //empresa
            $table->string('contrato_empresa_nome');
            $table->string('contrato_empresa_email');
            $table->string('contrato_empresa_responsavel');
            $table->string('contrato_empresa_cpf', 20);
            $table->string('contrato_empresa_telefone', 20);
            $table->string('contrato_empresa_tipo');
            $table->string('contrato_endereco', 255);
            $table->string('contrato_numero', 20);
            $table->string('contrato_bairro', 120);
            $table->string('contrato_cidade', 120);
            $table->string('contrato_estado', 2);
            $table->string('contrato_cep', 10);
            $table->string('contrato_complemento', 100)->nullable();


            //superadmin
            $table->string('contrato_pix_chave');
            $table->string('contrato_pix_recebedor');
            $table->string('contrato_pix_cidade');

            $table->timestamps();

            $table->foreign('empresa_id')
                ->references('id_empresa')
                ->on('empresas')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
