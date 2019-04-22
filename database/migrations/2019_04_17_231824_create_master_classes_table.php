<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_classes', function (Blueprint $table) {
            $table->increments( 'id' );
            $table->string( 'name' );
            $table->date( 'date' );
            $table->integer( "master_class_category_id" );
            $table->varchar( "level" );
            $table->decimal( 'price' );
            $table->string( 'address' );
            $table->string( 'time' );
            $table->integer( "count" );
            $table->string( 'image' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_classes');
    }
}
