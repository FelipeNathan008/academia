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
        Schema::table('grade_horario', function (Blueprint $table) {
            $table->unique('horario_treino_id_hora');
        });
    }

    public function down(): void
    {
        Schema::table('grade_horario', function (Blueprint $table) {
            $table->dropUnique(['horario_treino_id_hora']);
        });
    }
};
