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
        Schema::create('mensalidades_contratos', function (Blueprint $table) {

            $table->id('id_mensalidade_contrato');

            $table->unsignedBigInteger('det_contrato_id');

            $table->string('mensa_contrato_referencia', 7); // Ex: 2026-08

            $table->date('mensa_contrato_vencimento');

            $table->date('mensa_contrato_pagamento')->nullable();

            $table->decimal('mensa_contrato_valor', 10, 2);
            $table->string('mensa_contrato_status', 30)->default('Em aberto');

            $table->string('mensa_contrato_comprovante')->nullable();

            $table->dateTime('mensa_contrato_comprovante_enviado_em')->nullable();

            $table->timestamps();

            $table->foreign('det_contrato_id')
                ->references('id_det_contrato')
                ->on('detalhes_contratos')
                ->cascadeOnDelete();

            $table->index('mensa_contrato_referencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensalidades_contratos');
    }
};