<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graduacao', function (Blueprint $table) {

            $table->unsignedBigInteger('id_modalidade')
                ->nullable()
                ->after('id_graduacao');

            $table->string('gradu_ordem', 60)
                ->nullable()
                ->after('gradu_grau');

            $table->foreign('id_modalidade')
                ->references('id_modalidade')
                ->on('modalidade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('graduacao', function (Blueprint $table) {

            $table->dropForeign(['id_modalidade']);

            $table->dropColumn([
                'id_modalidade',
                'gradu_ordem'
            ]);
        });
    }
};
