<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalhes_mensalidade', function (Blueprint $table) {
            $table->string('det_mensa_comprovante_path')->nullable()->after('det_mensa_status');
            $table->timestamp('det_mensa_comprovante_enviado_em')->nullable()->after('det_mensa_comprovante_path');
        });
    }

    public function down(): void
    {
        Schema::table('detalhes_mensalidade', function (Blueprint $table) {
            $table->dropColumn(['det_mensa_comprovante_path', 'det_mensa_comprovante_enviado_em']);
        });
    }
};