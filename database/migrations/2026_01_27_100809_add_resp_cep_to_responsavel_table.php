<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('responsavel', function (Blueprint $table) {
            $table->string('resp_cep', 8)
                  ->after('resp_cpf');
        });
    }

    public function down(): void
    {
        Schema::table('responsavel', function (Blueprint $table) {
            $table->dropColumn('resp_cep');
        });
    }
};
