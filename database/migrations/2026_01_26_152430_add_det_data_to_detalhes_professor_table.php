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
        Schema::table('detalhes_professor', function (Blueprint $table) {
            $table->date('det_data')->after('det_modalidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalhes_professor', function (Blueprint $table) {
            $table->dropColumn('det_data');
        });
    }
};
