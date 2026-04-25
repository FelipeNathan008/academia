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
        Schema::table('users', function (Blueprint $table) {

            $table->unsignedBigInteger('professor_id')->nullable();
            $table->unsignedBigInteger('responsavel_id')->nullable();

            $table->foreign('professor_id')
                ->references('id_professor')
                ->on('professor')
                ->nullOnDelete();

            $table->foreign('responsavel_id')
                ->references('id_responsavel')
                ->on('responsavel')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['professor_id']);
            $table->dropForeign(['responsavel_id']);

            $table->dropColumn(['professor_id', 'responsavel_id']);
        });
    }
};
