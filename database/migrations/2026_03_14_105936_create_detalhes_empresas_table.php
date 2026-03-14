<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalhes_empresas', function (Blueprint $table) {
            $table->id('id_det_emp');

            $table->foreignId('id_empresa_id')
                ->unique()
                ->constrained('empresas', 'id_empresa')
                ->cascadeOnDelete();

            $table->string('det_emp_cep', 15);
            $table->string('det_emp_logradouro');
            $table->string('det_emp_numero');
            $table->string('det_emp_complemento')->nullable();
            $table->string('det_emp_bairro');
            $table->string('det_emp_cidade');
            $table->string('det_emp_uf', 2);
            $table->string('det_emp_regiao');
            $table->string('det_emp_pais');
            $table->string('det_emp_cnpj', 20)->nullable();
            $table->string('det_emp_email');
            $table->string('det_emp_telefone');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalhes_empresas');
    }
};