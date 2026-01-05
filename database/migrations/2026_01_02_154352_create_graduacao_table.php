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
        Schema::create('graduacao', function (Blueprint $table) {
            $table->id('id_graduacao');
            $table->string('gradu_nome_cor', 80);
            $table->integer('gradu_grau');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduacao');
    }
};
