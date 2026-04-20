<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turma', function (Blueprint $table) {
            $table->id('id_turma');
            $table->string('turma_nome', 100);
            $table->foreignId('id_emp_id')
                ->nullable()
                ->constrained('empresas', 'id_empresa');

            $table->foreignId('id_filial_id')
                ->nullable()
                ->constrained('filiais', 'id_filial');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turmas');
    }
};
