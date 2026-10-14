<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitante_grade', function (Blueprint $table) {
            $table->id('id_visitante_grade');
            $table->unsignedBigInteger('visitante_id_visitante');
            $table->unsignedBigInteger('grade_id_grade');
            $table->date('visgra_data');
            $table->string('visgra_desc', 150)->nullable();
            $table->unsignedBigInteger('id_emp_id');
            $table->timestamps();

            $table->foreign('visitante_id_visitante')
                ->references('id_visitante')
                ->on('visitantes')
                ->cascadeOnDelete();

            $table->foreign('grade_id_grade')
                ->references('id_grade')
                ->on('grade_horario')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitante_grade');
    }
};
