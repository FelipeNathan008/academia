<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modalidade_molde', function (Blueprint $table) {
            $table->id('id_modalidade_molde');
            $table->string('mod_nome');
            $table->string('mod_desc')->nullable();
            $table->timestamps();
        });

        Schema::create('graduacao_molde', function (Blueprint $table) {
            $table->id('id_graduacao_molde');
            $table->string('gradu_nome_cor');
            $table->integer('gradu_grau')->nullable();
            $table->integer('gradu_meta')->nullable();
            $table->integer('gradu_ordem')->nullable();
            $table->unsignedBigInteger('id_modalidade_molde');
            $table->timestamps();

            $table->foreign('id_modalidade_molde')
                  ->references('id_modalidade_molde')
                  ->on('modalidade_molde')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('graduacao_molde');
        Schema::dropIfExists('modalidade_molde');
    }
};