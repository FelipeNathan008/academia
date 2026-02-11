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
        Schema::create('preco_modalidade', function (Blueprint $table) {
            $table->id('id_preco_modalidade');
            $table->unsignedBigInteger('modalidade_id');
            $table->decimal('preco_modalidade', 10, 2);
            $table->timestamps();

            $table->foreign('modalidade_id')
                ->references('id_modalidade')
                ->on('modalidade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preco_modalidade');
    }
};
