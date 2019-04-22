<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'users', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->string( 'name' );
            $table->string( 'surname' );
            $table->string( 'tel' );
            $table->string( 'country' );
            $table->string( 'city' );
            $table->string( 'email' )->unique();
            $table->string( 'new_email' );
            $table->string( 'password' )->nullable();
            $table->string( 'crew' );
            $table->string( 'school_id' )->nullable();
            $table->string( 'verify_code' );
            $table->integer( "role_id" )->default( \App\Models\User::USER_ID );
            $table->rememberToken();
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'users' );
    }
}
