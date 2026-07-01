<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('responsavel', function (Blueprint $table) {
            $table->text('resp_cpf')->change();
        });
    }

    public function down()
    {
        Schema::table('responsavel', function (Blueprint $table) {
            $table->string('resp_cpf', 14)->change();
        });
    }
};