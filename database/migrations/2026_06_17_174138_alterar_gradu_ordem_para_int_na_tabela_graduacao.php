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
        Schema::table('graduacao', function (Blueprint $table) {
            $table->integer('gradu_ordem')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('graduacao', function (Blueprint $table) {
            $table->string('gradu_ordem', 60)->change();
        });
    }
};