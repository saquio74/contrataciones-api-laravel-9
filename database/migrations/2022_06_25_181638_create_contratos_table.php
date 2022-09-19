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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("proveedor_id")->nullable();
            $table->unsignedBigInteger("especialidad_id")->nullable();
            $table->integer("contrato")->nullable();
            $table->date("fecha_inicio")->nullable();
            $table->date("fecha_fin")->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger("created_by")->nullable();
            $table->unsignedBigInteger("updated_by")->nullable();
            $table->unsignedBigInteger("deleted_by")->nullable();
            $table->foreign("proveedor_id")->references("id")->on("proveedors");
            $table->foreign("especialidad_id")->references("id")->on("especialidades");
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
        Schema::dropIfExists('contratos');
    }
};
