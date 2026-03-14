<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filiais', function (Blueprint $table) {
            $table->id('id_filial');

            $table->foreignId('id_emp_id')
                ->constrained('empresas', 'id_empresa')
                ->cascadeOnDelete();

            $table->string('filial_nome');
            $table->string('filial_apelido');
            $table->string('filial_nome_responsavel');
            $table->string('filial_email_responsavel');
            $table->string('filial_telefone_responsavel', 20);
            $table->string('filial_cpf', 20)->nullable();
            $table->string('filial_foto')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filiais');
    }
};
