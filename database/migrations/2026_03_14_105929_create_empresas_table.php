<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id('id_empresa');

            $table->string('emp_nome');
            $table->string('emp_apelido');
            $table->string('emp_nome_responsavel');
            $table->string('emp_email_responsavel');
            $table->string('emp_telefone_responsavel', 20);
            $table->string('emp_cpf', 14);
            $table->string('emp_tipo', 150);
            $table->string('emp_foto')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
