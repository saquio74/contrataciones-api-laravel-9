<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("agente_id");
            $table->integer("anio");
            $table->integer("estado");
            $table->date("fecha_inicio");
            $table->date("fecha_fin");
            $table->timestamps();
            $table->foreign("agente_id")->references("id")->on("agentes");
            $table->unsignedBigInteger("created_by")->nullable();
            $table->unsignedBigInteger("updated_by")->nullable();
            $table->unsignedBigInteger("deleted_by")->nullable();
            $table->foreign("created_by")->references("id")->on("users");
            $table->foreign("deleted_by")->references("id")->on("users");
            $table->foreign("updated_by")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacaciones');
    }
};
