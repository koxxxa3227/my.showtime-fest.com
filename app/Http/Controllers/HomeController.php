<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\School;
use Illuminate\Http\Request;

class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //        $this->middleware( 'auth' );
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view( 'profile.index' );
    }

    public function getCSV() {
        $csv   = fopen( public_path( '/uploads/GeoLite2-City-Locations-ru.csv' ), 'r' );
        $array = [];
        if ( $csv ) {
            while ( ( $data = fgetcsv( $csv, 1000, ',' ) ) != false ) {
                if ( $data[ 5 ] == "Украина" ) {
                    $array[] = $data;
                }
            }
            foreach ( $array as $item ) {
                array_map( 'trim', $item );
                if ( !empty( $item[ 10 ] ) && !City::whereTitle( $item[ 10 ] )->first() ) {
                    $city             = new City();
                    $city->country_id = 1;

                    $city->title = $item[ 10 ];

                    $city->save();
                }
            }
        }
    }

    public function getSchool(Request $request){
        $schools = School::where('title', 'like', "%$request->term%")->get();
        $array = [];

        foreach($schools as $school){
            $array[] = [
                'id' => $school->id,
                'value' => $school->title
            ];
        }

        return $array;
    }
}
