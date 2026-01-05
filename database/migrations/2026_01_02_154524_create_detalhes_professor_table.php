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
        Schema::create('detalhes_professor', function (Blueprint $table) {
            $table->id('id_det_professor');
            $table->unsignedBigInteger('professor_id_professor');
            $table->string('det_gradu_nome_cor', 80);
            $table->integer('det_grau');
            $table->string('det_modalidade', 100);
            $table->timestamps();

            $table->foreign('professor_id_professor')
                ->references('id_professor')
                ->on('professor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalhes_professor');
    }
};
