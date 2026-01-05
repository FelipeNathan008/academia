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
        Schema::create('valor_aula', function (Blueprint $table) {
            $table->id('id_valor_aula');
            $table->unsignedBigInteger('modalidade_id');
            $table->decimal('valor_aula', 10, 2);
            $table->timestamps();

            $table->foreign('modalidade_id')
                ->references('id_modalidade')
                ->on('modalidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valor_aula');
    }
};
