<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('responsavel', function (Blueprint $table) {
            $table->string('resp_telefone', 20)
                  ->after('resp_cpf');

            $table->string('resp_email', 150)
                  ->after('resp_telefone');
        });
    }

    public function down(): void
    {
        Schema::table('responsavel', function (Blueprint $table) {
            $table->dropColumn([
                'resp_telefone',
                'resp_email'
            ]);
        });
    }
};
