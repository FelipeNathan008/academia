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
        Schema::table('aluno', function (Blueprint $table) {
            $table->string('aluno_parentesco', 60)->after('aluno_nome');
        });
    }

    public function down(): void
    {
        Schema::table('aluno', function (Blueprint $table) {
            $table->dropColumn('aluno_parentesco');
        });
    }
};
