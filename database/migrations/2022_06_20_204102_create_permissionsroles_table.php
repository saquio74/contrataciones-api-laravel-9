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
        Schema::create('permissionsroles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("permmissions_id");
            $table->unsignedBigInteger("roles_id");
            $table->softDeletes();
            $table->timestamps();
            $table->foreign("permmissions_id")->references("id")->on("permissions");
            $table->foreign("roles_id")->references("id")->on("roles");
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
        Schema::dropIfExists('permissionsroles');
    }
};
