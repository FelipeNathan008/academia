<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('preco_modalidade', function (Blueprint $table) {
            $table->string('preco_categoria', 120)
                  ->after('preco_plano');
        });
    }

    public function down(): void
    {
        Schema::table('preco_modalidade', function (Blueprint $table) {
            $table->dropColumn('preco_categoria');
        });
    }
};
