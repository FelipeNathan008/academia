<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aluno', function (Blueprint $table) {
            $table->text('aluno_desc')->change();
        });

        Schema::table('matricula', function (Blueprint $table) {
            $table->text('matri_desc')->change();
        });

        Schema::table('grade_horario', function (Blueprint $table) {
            $table->text('grade_desc')->change();
        });

        Schema::table('professor', function (Blueprint $table) {
            $table->text('prof_desc')->change();
        });
    }

    public function down(): void
    {
        Schema::table('aluno', function (Blueprint $table) {
            $table->string('aluno_desc', 120)->change();
        });

        Schema::table('matricula', function (Blueprint $table) {
            $table->string('matri_desc', 150)->change();
        });

        Schema::table('grade_horario', function (Blueprint $table) {
            $table->string('grade_desc', 150)->change();
        });

        Schema::table('professor', function (Blueprint $table) {
            $table->string('prof_desc', 150)->change();
        });
    }
};
