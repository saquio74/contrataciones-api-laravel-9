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
        Schema::create('provhosps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("hospital_id")->nullable();
            $table->unsignedBigInteger("proveedor_id")->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign("hospital_id")->references("id")->on("hospitales");
            $table->foreign("proveedor_id")->references("id")->on("proveedors");
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
        Schema::dropIfExists('provhosps');
    }
};
