<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitantes', function (Blueprint $table) {
            $table->id('id_visitante');
            $table->unsignedBigInteger('id_emp_id');
            $table->string('vis_nome', 120);
            $table->string('vis_email', 150)->nullable();
            $table->string('vis_telefone', 20)->nullable();
            $table->date('vis_data_nasc')->nullable();
            $table->string('vis_status', 20)->default('Ativo');
            $table->timestamps();

            $table->index('id_emp_id');
            $table->index('vis_nome');
            $table->index('vis_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitantes');
    }
};