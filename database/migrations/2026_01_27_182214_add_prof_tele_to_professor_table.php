<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('professor', function (Blueprint $table) {
            $table->string('prof_telefone', 20)
                  ->after('prof_nascimento');
        });
    }

    public function down(): void
    {
        Schema::table('professor', function (Blueprint $table) {
            $table->dropColumn([
                'prof_telefone'
            ]);
        });
    }
};
