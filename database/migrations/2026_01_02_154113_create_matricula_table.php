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
            $table->text('matri_desc');
            $table->string('matri_status', 50);
            $table->date('matri_data');
            $table->timestamps();

            $table->foreign('aluno_id_aluno')
                ->references('id_aluno')
                ->on('aluno')
                ->onDelete('cascade');
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
