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
        Schema::table('prestacion', function (Blueprint $table) {
            $table->date("vigente_hasta")->nullable()->after("valor");
            $table->date("vigente_desde")->after("valor");
            $table->unsignedBigInteger("especialidad_id")->after("valor");
            $table->foreign("especialidad_id")->references("id")->on("especialidades");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestacion', function (Blueprint $table) {
            //
        });
    }
};
