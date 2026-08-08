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
        Schema::create('detalhes_contratos', function (Blueprint $table) {

            $table->id('id_det_contrato');

            $table->unsignedBigInteger('contrato_id');
            $table->unsignedBigInteger('plano_id');

            $table->string('det_plano_nome');
            $table->string('det_plano_tipo');
            $table->decimal('det_plano_valor', 10, 2);
            $table->integer('det_plano_quantidade')->nullable();

            $table->date('det_data_vigente');
            $table->date('det_data_fim')->nullable();

            $table->timestamps();

            $table->foreign('contrato_id')
                ->references('id_contrato')
                ->on('contratos')
                ->cascadeOnDelete();

            $table->foreign('plano_id')
                ->references('id_plano')
                ->on('planos')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalhes_contratos');
    }
};
