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
        Schema::create('agenincs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("agente_id");
            $table->unsignedBigInteger("inciso_id");
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('agenincs');
    }
};
