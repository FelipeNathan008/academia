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
        Schema::create('aula', function (Blueprint $table) {

            $table->id('id_aula');

            // Grade de Horário
            $table->unsignedBigInteger('id_grade_horario');

            // Dados da aula
            $table->string('aula_nome_exercicio', 150);
            $table->string('aula_caract_exercicio', 255);
            $table->date('aula_inicio');
            $table->date('aula_fim');
            $table->string('aula_link')->nullable();
            $table->string('aula_status', 20)->default('ativo');
            $table->string('aula_desc', 255);

            // Empresa
            $table->foreignId('id_emp_id')
                ->nullable()
                ->constrained('empresas', 'id_empresa');

            // Filial
            $table->foreignId('id_filial_id')
                ->nullable()
                ->constrained('filiais', 'id_filial');

            $table->timestamps();

            // Foreign Key
            $table->foreign('id_grade_horario')
                ->references('id_grade')
                ->on('grade_horario')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aula');
    }
};
