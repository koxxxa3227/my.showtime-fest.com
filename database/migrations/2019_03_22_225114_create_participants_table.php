<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'participants', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->unsignedInteger( "application_id" );
            $table->string( 'name' );

            $table->foreign( 'application_id' )
                  ->references( 'id' )->on( 'applications' )
                  ->onDelete( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'participants' );
    }
}
