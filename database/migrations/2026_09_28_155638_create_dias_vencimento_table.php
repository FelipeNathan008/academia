<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dias_vencimento', function (Blueprint $table) {
            $table->id('id_dia_vencimento');
            $table->unsignedTinyInteger('dia_vencimento');
            $table->unsignedBigInteger('id_emp_id');
            $table->timestamps();

            $table->unique(['dia_vencimento', 'id_emp_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dias_vencimento');
    }
};