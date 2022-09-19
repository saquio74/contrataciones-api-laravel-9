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
        Schema::create('agenfac', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("agente_id");
            $table->string("periodo")->nullable();
            $table->string("anio")->nullable();
            $table->string("horas")->nullable();
            $table->string("inc")->nullable();
            $table->double("valor",8,2)->nullable();
            $table->integer("bonificacion")->nullable();
            $table->double("subtot",8,2)->nullable();
            $table->double("total",8,2)->nullable();
            $table->unsignedBigInteger("hospital")->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("agente_id")->references("id")->on("agentes");
            $table->foreign("hospital")->references("id")->on("hospitales");
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
        Schema::dropIfExists('agenfacs');
    }
};
