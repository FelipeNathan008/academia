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
        Schema::create('aluno', function (Blueprint $table) {
            $table->id('id_aluno');
            $table->unsignedBigInteger('responsavel_id_responsavel');
            $table->string('aluno_nome', 120);
            $table->date('aluno_nascimento');
            $table->text('aluno_desc');
            $table->string('aluno_foto', 255);
            $table->timestamps();

            $table->foreign('responsavel_id_responsavel')
                ->references('id_responsavel')
                ->on('responsavel')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aluno');
    }
};
