<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'applications', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->unsignedInteger( "user_id" );
            $table->unsignedInteger("date_id");
            $table->unsignedInteger("category_id");
            $table->decimal('amount')->defaull(0);
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('crew')->nullable();
            $table->string('school_id')->nullable();
            $table->boolean("accepted")->default(0);
            $table->boolean('is_paid')->default(0);
            $table->timestamps();

            $table->foreign( 'user_id' )
                  ->references( 'id' )->on( 'users' )
                  ->onDelete( 'cascade' );

//            $table->foreign( 'date_id' )
//                  ->references( 'id' )->on( 'dates' )
//                  ->onDelete( 'cascade' );
//
//            $table->foreign( 'category_id' )
//                  ->references( 'id' )->on( 'categories' )
//                  ->onDelete( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'applications' );
    }
}
