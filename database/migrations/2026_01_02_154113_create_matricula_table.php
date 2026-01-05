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
        Schema::create('matricula', function (Blueprint $table) {
            $table->id('id_matricula');
            $table->unsignedBigInteger('aluno_id_aluno');
            $table->string('matri_desc', 150);
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
        Schema::dropIfExists('matricula');
    }
};
