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

            $table->string('emp_cidade', 255)->after('emp_chave_pix');

            $table->string('emp_nome_recebedor', 255)
                ->nullable()
                ->after('emp_cidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['emp_cidade', 'emp_nome_recebedor']);
        });
    }
};
