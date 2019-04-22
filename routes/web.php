<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get( '/', function () {
    return redirect()->route('login');
} );

Auth::routes();
Route::get( 'logout', 'Auth\LoginController@logout' );
Route::get('user/{pass_code}/get-password', 'ProfileController@getPassword');
Route::post('user/get-password/post', 'ProfileController@postGetPassword');
Route::get('get-school', 'HomeController@getSchool');

Route::group( [ 'prefix' => 'liqpay' ], function () {
    Route::get( 'pay/{id}/{type?}', 'LiqpayController@pay' );
    Route::post( '{type}/status', 'LiqpayController@status' );
//    Route::post( 'tickets-status', 'LiqpayController@ticketStatus' );
//    Route::post( 'master-classes-status', 'LiqpayController@MCStatus' );
    Route::any( 'result/{type}', 'LiqpayController@result' );
} );

Route::get('accept-email-change/{verify_code}', 'ProfileController@acceptEmailChange');

Route::middleware( 'auth' )->prefix( 'profile' )->group( function () {
    Route::get( '/', 'ProfileController@index' );
    Route::post( '/', 'ProfileController@postIndex' );
    Route::get( 'applications', 'ProfileController@applications' );
    Route::get('application/remove/id{id}', 'ProfileController@removeApplication');
    Route::get( 'application/{type}/{id?}', 'ProfileController@application' );
    Route::post( 'application/{type}/{id?}', 'ProfileController@postApplication' );
    Route::get('tickets', 'ProfileController@tickets');
    Route::post('tickets', 'ProfileController@postTickets');
    Route::get('my-master-classes', 'ProfileController@myMasterClasses');
    Route::get('master-classes', 'ProfileController@masterClasses');
    Route::post('master-classes', 'ProfileController@postMasterClasses');
} );

Route::middleware( 'auth', 'is_admin' )->prefix( 'admin' )->group( function () {
    Route::get('/', function(){
        return redirect()->action('AdminController@index');
    });
    Route::get( 'users', 'AdminController@index' );
    Route::get( "user/{id}/edit",  'AdminController@editUser');
    Route::post('user/{id}/edit', 'AdminController@postEditUser');
    Route::get('categories/{type}/{id?}', 'AdminController@categories');
    Route::post('categories/{type}/{id?}', 'AdminController@postCategories');
    Route::get('category/remove/{id}', 'AdminController@removeCategory');
    Route::get('dates/{type}/{id?}', 'AdminController@dates');
    Route::post('dates/{type}/{id?}', 'AdminController@postDates');
    Route::get('applications', 'AdminController@applications');
    Route::get('application/{id}/remove', 'AdminController@removeApplication');
    Route::get('application/{id}/edit', 'AdminController@editApplication');
    Route::post('application/{id}/edit', 'AdminController@postEditApplication');
    Route::get('schools/{type}/{id?}', 'AdminController@schools');
    Route::post('schools/{type}/{id?}', 'AdminController@postSchools');
    Route::get('school/{id}/remove', 'AdminController@removeSchool');
    Route::get('attempt-login/user/{id}', 'AdminController@loginLikeUser');
    Route::get('applications/in-excel', 'AdminController@getApplicationsInExcel');
    Route::get('tickets', 'AdminController@tickets');
    Route::get('master-classes', 'AdminController@masterClasses');
    Route::get('master-class/{type}/{id?}', 'AdminController@masterClass');
    Route::post('master-class/{type}/{id?}', 'AdminController@postMasterClass');
    Route::get('master-class-categories/{type}/{id?}', 'AdminController@masterClassCategories');
    Route::post('master-class-categories/{type}/{id?}', 'AdminController@postMasterClassCategories');
    Route::get('master-classes/requests', 'AdminController@masterClassRequests');
} );

Route::get('get-csv', 'HomeController@getCSV');
