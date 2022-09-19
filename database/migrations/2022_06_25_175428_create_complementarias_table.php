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
        Schema::create('complementaria', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string("periodo")->nullable();
            $table->integer("anio")->nullable();
            $table->integer("horas")->nullable();
            $table->integer("bonificacion")->nullable();
            $table->double("valor",8,2)->nullable();
            $table->double("subtotal",8,2)->nullable();
            $table->double("bonvalor",8,2)->nullable();
            $table->double("total",8,2)->nullable();
            $table->string("fecha")->nullable();
            $table->unsignedBigInteger("hospital_id")->nullable();
            $table->unsignedBigInteger("agente_id")->nullable();
            $table->unsignedBigInteger("inciso_id")->nullable();
            $table->foreign("hospital_id")->references("id")->on("hospitales");
            $table->foreign("agente_id")->references("id")->on("agentes");
            $table->foreign("inciso_id")->references("id")->on("incisos");
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
        Schema::dropIfExists('complementarias');
    }
};
