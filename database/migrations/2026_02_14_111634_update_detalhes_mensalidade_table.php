<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalhes_mensalidade', function (Blueprint $table) {

            $table->dropColumn('det_mensa_per_vig_pago');
            $table->string('det_mensa_mes_vigente', 60)
                ->after('det_mensa_forma_pagamento');

            $table->date('det_mensa_data_pagamento')
                ->nullable()
                ->after('det_mensa_data_venc');

            $table->string('det_mensa_status', 60)
                ->after('det_mensa_data_pagamento');

            $table->decimal('det_mensa_valor', 10, 2)
                ->after('det_mensa_status');
        });
    }

    public function down(): void
    {
        Schema::table('detalhes_mensalidade', function (Blueprint $table) {

            $table->dropColumn([
                'det_mensa_mes_vigente',
                'det_mensa_data_pagamento',
                'det_mensa_status',
                'det_mensa_valor'
            ]);

            $table->string('det_mensa_per_vig_pago', 60)
                ->after('det_mensa_forma_pagamento');
        });
    }
};
