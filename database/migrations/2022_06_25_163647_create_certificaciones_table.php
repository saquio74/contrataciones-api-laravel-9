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
        Schema::create('certificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("profesional_id")->nullable();
            $table->unsignedBigInteger("prestacion_id")->nullable();
            $table->string("mes")->nullable();
            $table->integer("anio")->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger("created_by")->nullable();
            $table->unsignedBigInteger("updated_by")->nullable();
            $table->unsignedBigInteger("deleted_by")->nullable();
            $table->foreign("profesional_id")->references("id")->on("profesional");
            $table->foreign("prestacion_id")->references("id")->on("prestacion");
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
        Schema::dropIfExists('certificaciones');
    }
};
