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
        Schema::table('responsavel', function (Blueprint $table) {
            $table->string('resp_parentesco', 60)->nullable()->change();
            $table->text('resp_cpf')->nullable()->change();
            $table->string('resp_telefone', 20)->nullable()->change();
            $table->string('resp_email', 150)->nullable()->change();
            $table->string('resp_cep', 8)->nullable()->change();
            $table->string('resp_logradouro', 150)->nullable()->change();
            $table->string('resp_numero', 10)->nullable()->change();
            $table->string('resp_complemento', 100)->nullable()->change();
            $table->string('resp_bairro', 150)->nullable()->change();
            $table->string('resp_cidade', 150)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('responsavel', function (Blueprint $table) {
            $table->string('resp_parentesco', 60)->nullable(false)->change();
            $table->text('resp_cpf')->nullable(false)->change();
            $table->string('resp_telefone', 20)->nullable(false)->change();
            $table->string('resp_email', 150)->nullable(false)->change();
            $table->string('resp_cep', 8)->nullable(false)->change();
            $table->string('resp_logradouro', 150)->nullable(false)->change();
            $table->string('resp_numero', 10)->nullable(false)->change();
            $table->string('resp_complemento', 100)->nullable(false)->change();
            $table->string('resp_bairro', 150)->nullable(false)->change();
            $table->string('resp_cidade', 150)->nullable(false)->change();
        });
    }
};
