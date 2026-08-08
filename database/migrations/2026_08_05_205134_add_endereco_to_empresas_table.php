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
        Schema::table('empresas', function (Blueprint $table) {

            $table->string('emp_end_cep', 9)->nullable()->after('emp_cidade');
            $table->string('emp_end_logradouro', 255)->nullable()->after('emp_end_cep');
            $table->string('emp_end_numero', 10)->nullable()->after('emp_end_logradouro');
            $table->string('emp_end_complemento', 100)->nullable()->after('emp_end_numero');
            $table->string('emp_end_bairro', 120)->nullable()->after('emp_end_complemento');
            $table->string('emp_end_cidade', 120)
                ->nullable()
                ->after('emp_end_bairro');

            $table->string('emp_end_estado', 2)
                ->nullable()
                ->after('emp_end_cidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'emp_end_cep',
                'emp_end_logradouro',
                'emp_end_numero',
                'emp_end_complemento',
                'emp_end_bairro',
                'emp_end_cidade',
                'emp_end_estado',
            ]);
        });
    }
};