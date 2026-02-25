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
        $table->string('gradu_meta', 50)
              ->after('gradu_grau');
    });
}

public function down(): void
{
    Schema::table('graduacao', function (Blueprint $table) {
        $table->dropColumn('gradu_meta');
    });
}
};
