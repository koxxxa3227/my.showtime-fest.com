<?php

namespace App\Http\Controllers\Auth;

use App\Models\City;
use App\Models\School;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Notifications\NewAccountRegisteredNotify;
use App\Notifications\RegisterNotify;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    public function showRegistrationForm() {
        $cities        = City::oldest( 'title' )->get();
        $schools       = School::latest('id')->get();
        $view          = view( 'auth.register' );
        $view->cities  = json_encode( $cities );
        $view->schools = $schools;
        return $view;
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware( 'guest' );
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator( array $data ) {
        return Validator::make( $data, [
            'name'    => [ 'required', 'string', 'max:255' ],
            'email'   => [ 'required', 'string', 'email', 'max:255', 'unique:users' ],
            'surname' => [ 'required', 'string', 'min:1', 'max:255' ],
            'tel'     => [ 'required', 'string', 'min:1', 'max:255' ],
            'country' => [ 'required', 'string', 'min:1', 'max:255' ],
            'city'    => [ 'required', 'string', 'min:1', 'max:255' ],
            'crew'    => [ 'required', 'string', 'min:1', 'max:255' ],
            'school'  => [ 'string', 'min:1', 'max:255' ],
        ] );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create( array $data ) {
        $pass_code = '';

        do {
            $pass_code = str_random();
        } while ( User::whereVerifyCode( $pass_code )->first() );

        $school_id = null;
        if ( $data[ 'school' ] ) {
            $school = School::whereTitle( $data[ 'school' ] )->first();
            if ( !$school ) {
                $school        = new School();
                $school->title = $data[ 'school' ];
                $school->save();
            }

            $school_id = $school->id;
        }

        $user = User::create( [
                                  'name'        => $data[ 'name' ],
                                  'surname'     => $data[ 'surname' ],
                                  'tel'         => $data[ 'tel' ],
                                  'country'     => $data[ 'country' ],
                                  'city'        => $data[ 'city' ],
                                  'email'       => $data[ 'email' ],
                                  'crew'        => $data[ 'crew' ],
                                  'school_id'   => $school_id,
                                  'verify_code' => $pass_code
                              ] );

        $user->notify( new RegisterNotify( $pass_code ) );

        return $user;
    }

    public function register( Request $request ) {
        $this->validator( $request->all() )->validate();

        event( new Registered( $user = $this->create( $request->all() ) ) );

        // $this->guard()->login($user);

        return $this->registered( $request, $user )
            ?: redirect( $this->redirectPath() );
    }

    protected function registered( Request $request, $user ) {
        \Notification::route( 'mail', 'borovikov.s@gmail.com' )->notify( new NewAccountRegisteredNotify( $user ) );
        \Notification::route( 'mail', 'denis.mirgoyazov@gmail.com' )->notify( new NewAccountRegisteredNotify( $user ) );

        flash( 'Вы успешно зарегестрированы. Письмо для создания пароля - отправлено Вам на почту' );
    }
}
