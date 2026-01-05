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
        Schema::create('professor', function (Blueprint $table) {
            $table->id('id_professor');
            $table->string('prof_nome', 120);
            $table->date('prof_nascimento');
            $table->string('prof_desc', 150);
            $table->string('prof_foto', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professor');
    }
};
