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
        Schema::create('detalhes_mensalidade', function (Blueprint $table) {
            $table->id('id_detalhes_mensalidade');
            $table->unsignedBigInteger('mensalidade_id_mensalidade');
            $table->string('det_mensa_forma_pagamento', 60);
            $table->string('det_mensa_per_vig_pago', 60);
            $table->date('det_mensa_data_venc');
            $table->timestamps();

            $table->foreign('mensalidade_id_mensalidade')
                ->references('id_mensalidade')
                ->on('mensalidade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalhes_mensalidade');
    }
};
