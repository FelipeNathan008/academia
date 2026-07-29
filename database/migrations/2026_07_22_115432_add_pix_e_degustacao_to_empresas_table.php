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
            $table->string('emp_chave_pix')->nullable()->after('emp_foto');
            $table->date('emp_degustacao_inicio')->nullable()->after('emp_chave_pix');
            $table->date('emp_degustacao_fim')->nullable()->after('emp_degustacao_inicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'emp_chave_pix',
                'emp_degustacao_inicio',
                'emp_degustacao_fim',
            ]);
        });
    }
};