<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->foreignId('id_emp_id')
                ->nullable()
                ->after('id')
                ->constrained('empresas', 'id_empresa')
                ->nullOnDelete();

            $table->foreignId('id_filial_id')
                ->nullable()
                ->after('id_emp_id')
                ->constrained('filiais', 'id_filial')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['id_emp_id']);
            $table->dropForeign(['id_filial_id']);

            $table->dropColumn(['id_emp_id', 'id_filial_id']);

        });
    }
};