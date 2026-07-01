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
        Schema::table('matricula', function (Blueprint $table) {
            $table->date('matri_data_pausa')->nullable()->after('matri_data');
            $table->date('matri_data_encerramento')->nullable()->after('matri_data_pausa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matricula', function (Blueprint $table) {
            $table->dropColumn([
                'matri_data_pausa',
                'matri_data_encerramento'
            ]);
        });
    }
};
