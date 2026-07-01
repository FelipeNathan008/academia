<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matricula', function (Blueprint $table) {
            $table->text('matri_motivo')->nullable()->after('matri_status');
        });
    }

    public function down(): void
    {
        Schema::table('matricula', function (Blueprint $table) {
            $table->dropColumn('matri_motivo');
        });
    }
};