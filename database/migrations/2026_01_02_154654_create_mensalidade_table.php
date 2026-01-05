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
        Schema::create('mensalidade', function (Blueprint $table) {
            $table->id('id_mensalidade');
            $table->unsignedBigInteger('aluno_id_aluno');
            $table->string('mensa_periodo_vigente', 60);
            $table->date('mensa_data_venc');
            $table->decimal('mensa_valor', 10, 2);
            $table->string('mensa_status', 60);
            $table->timestamps();

            $table->foreign('aluno_id_aluno')
                ->references('id_aluno')
                ->on('aluno');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensalidade');
    }
};
