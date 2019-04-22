<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMasterClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_master_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("user_id");
            $table->unsignedInteger("master_class_id");
            $table->integer("transaction_id");
            $table->boolean('is_paid')->default(false);
            $table->integer("discount")->default(0);
            $table->timestamps();

            $table->foreign('master_class_id')
                ->references('id')->on('master_classes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_master_classes');
    }
}
