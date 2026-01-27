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
            // Número da residência
            $table->string('resp_numero', 10)
                  ->after('resp_logradouro');

            // Complemento
            $table->string('resp_complemento', 150)
                  ->nullable()
                  ->after('resp_numero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('responsavel', function (Blueprint $table) {
            $table->dropColumn(['resp_numero', 'resp_complemento']);
        });
    }
};
