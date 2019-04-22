<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTracksTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'tracks', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->unsignedInteger( "user_id" );
            $table->unsignedInteger( "application_id" );
            $table->string('path')->nullable();
            $table->string( 'title' );

            $table->foreign( 'user_id' )
                  ->references( 'id' )->on( 'users' )
                  ->onDelete( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'tracks' );
    }
}
