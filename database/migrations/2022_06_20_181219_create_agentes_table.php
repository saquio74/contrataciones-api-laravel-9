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
        Schema::create('agentes', function (Blueprint $table) {
            $table->id();
            $table->integer("legajo")->nullable();
            $table->bigInteger("dni")->nullable();
            $table->string("nombre")->nullable();
            $table->unsignedBigInteger("hospital_id")->nullable();
            $table->unsignedBigInteger("servicio_id")->nullable();
            $table->unsignedBigInteger("sector_id")->nullable();
            $table->string("horario")->nullable();
            $table->string("telefono")->nullable();
            $table->string("activo")->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("hospital_id")->references("id")->on("hospitales");
            $table->foreign("sector_id")->references("id")->on("sector");
            $table->foreign("servicio_id")->references("id")->on("servicio");
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
        Schema::dropIfExists('agentes');
    }
};
