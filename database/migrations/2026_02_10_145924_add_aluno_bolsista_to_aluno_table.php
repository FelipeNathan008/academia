<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aluno', function (Blueprint $table) {
            $table->char('aluno_bolsista', 20)
                  ->notNull()
                  ->after('aluno_foto');
        });
    }

    public function down(): void
    {
        Schema::table('aluno', function (Blueprint $table) {
            $table->dropColumn('aluno_bolsista');
        });
    }
};
