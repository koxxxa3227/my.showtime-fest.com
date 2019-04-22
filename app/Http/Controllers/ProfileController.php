<?php

namespace App\Http\Controllers;

use App\Libs\MP3File;
use App\Models\Application;
use App\Models\Category;
use App\Models\City;
use App\Models\Date;
use App\Models\Document;
use App\Models\MasterClass;
use App\Models\Participant;
use App\Models\School;
use App\Models\Track;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserMasterClass;
use App\Models\UserTicket;
use App\Notifications\EMailChangeNotify;
use App\Notifications\NewApplicationCreatedNotify;
use Illuminate\Http\Request;

class ProfileController extends Controller {
    public function index() {
        $user = \Auth::user();

        $schools = School::all();

        $view          = view( "profile.index" );
        $view->user    = $user;
        $view->schools = $schools;
        return $view;
    }

    public function postIndex( Request $request ) {
        $this->validate( $request, [
            'name'    => [ 'required', 'string', 'min:2' ],
            'surname' => [ 'required', 'string', 'min:2' ],
            'email'   => [ 'required', 'string', 'min:2' ],
            'tel'     => [ 'required', 'string', 'min:2' ],
            'country' => [ 'required', 'string', 'min:2' ],
            'city'    => [ 'required', 'string', 'min:2' ],
            'crew'    => [ 'required', 'string', 'min:2' ],
        ] );
        $user = \Auth::user();

        $user->fill( $request->except( 'password', 'email', 'school' ) );
        $school = School::whereTitle( $request->school )->first();
        if ( !$school ) {
            $school        = new School();
            $school->title = $request->school;
            $school->save();
        }
        $user->school_id = $school->id;
        if ( $request->email != $user->email ) {
            if ( User::whereEmail( $request->email )->first() || User::whereNewEmail( $request->email )->first() ) {
                flash( 'Почта уже используется', 'danger' );
                return back();
            }
            $user->new_email = $request->email;
            do {
                $verify_code = str_random();
            } while ( User::whereVerifyCode( $verify_code )->first() );

            $user->verify_code = $verify_code;

            \Notification::route( 'mail', $request->email )->notify( new EMailChangeNotify( $verify_code ) );
        }
        $user->save();

        return back();
    }

    public function getPassword( $verify_code ) {
        $user = User::whereVerifyCode( $verify_code )->first();
        if ( $user ) {
            $view              = view( 'profile.getPassword' );
            $view->verify_code = $verify_code;
            return $view;
        }
        echo 'User not found';
    }

    public function postGetPassword( Request $request ) {
        $user           = User::whereVerifyCode( $request->verify_code )->first();
        $user->password = \Hash::make( $request->password );
        $user->save();
        \Auth::login( $user );
        flash( 'Пароль успешно сохранён. Вы были авторизированы' );
        return redirect()->action( 'ProfileController@applications' );

    }

    public function applications() {
        $user         = \Auth::user();
        $applications = $user->applications;

        $view               = view( 'profile.application.index' );
        $view->applications = $applications;
        return $view;
    }

    public function application( $type, $id = null ) {
        $application = null;
        $dates       = Date::oldest( 'title' )->get();
        $categories  = Category::oldest( 'title' )->get();
        $view        = false;
        switch ( $type ) {
            case 'create':
                $application = new Application();
                $view        = view( 'profile.application.create' );
                break;
            case 'edit':
                $application = Application::findOrFail( $id );
                $view        = view( 'profile.application.edit' );
                $denied      = false;
                if ( Document::whereApplicationId( $id )->whereAccepted( true )->first() ) {
                    $denied = true;
                }
                $view->denied = $denied;
                break;
        }
        $track = Track::whereUserId( \Auth::id() )->whereApplicationId( $application->id )->first();
        if ( !$track ) {
            $track = new Track();
        }

        //        dd($application->participants()->with('document')->get());
        $documents = [];
        if ( $type == 'edit' ) {
            foreach ( $application->participants as $participant ) {
                if ( $document = $participant->document )
                    $documents[] = $document->title;
            }
        }
        $view->user            = \Auth::user();
        $view->type            = $type;
        $view->apl             = $application;
        $view->documents       = $documents;
        $view->participants    = json_encode( Participant::with( 'document' )
                                                         ->whereApplicationId( $application->id )
                                                         ->get() );
        $view->dates           = json_encode( $dates );
        $view->categories      = json_encode( $categories );
        $view->track           = $track;
        $view->choosedCategory = $application->category ? json_encode( $application->category ) : "";
        $view->doc_titles      = Document::whereIn( 'participant_id', $application->participants()->pluck( 'id' ) )
                                         ->pluck( 'title' );
        $view->schools         = School::all();
        return $view;
    }

    public function postApplication( Request $request, $type, $id = null ) {
        if ( count( $request->participants ) <= 0 ) {
            flash( 'Нужно добавить хотя бы одного участника', 'danger' );
            return back();
        }
        if ( $track = $request->track ) {
            try {
                $mp3file  = new MP3File( $track );
                $category = Category::findOrFail( $request->category_id );
                if ( $mp3file->getDuration() > $category->length + 5 ) {
                    //                old( 'category_id', $request->category_id );
                    flash( "Трек слишком длинный. Допустимая длинна трека " . secToMin( $category->length ) . "  мин.<br> Ваш трек длинной " . secToMin( $mp3file->getDuration() ) . " мин.", 'danger' );
                    $request->flash();
                    return back();
                }
            } catch ( \Exception $exception ) {
                $request->flash();
                flash( "При загрузке трека что-то пошло не так. Свяжитесь с нами в случае повторения этой ситуации. <a href='mailto:info@showtime-fest.com'>info@showtime-fest.com</a> или <a href='tel:+380675605959'>+380675605959</a>.", 'danger' );
                return back();
            }
        }
        $user = \Auth::user();
        switch ( $type ) {
            case 'create':
                $application          = new Application();
                $application->user_id = $user->id;
                break;
            case 'edit':
                $application = Application::findOrFail( $id );
                if ( $application->category_id != $request->category_id ) {
                    Participant::whereApplicationId( $application->id )->delete();
                }

                if ( ( $docs_ids = $request->remove_docs )
                     && count( $docs_ids ) > 0
                     && $docs_ids[ 0 ] != null
                ) {
                    foreach ( explode( ',', $docs_ids[ 0 ] ) as $doc_id ) {
                        $doc = Document::findOrFail( $doc_id );
                        \File::delete( public_path( '/uploads/' . $doc->path ) );
                        $doc->delete();
                    }
                }

                if ( $request->remove_track != 'false' ) {
                    $track = Track::whereUserId( $application->user_id )->whereApplicationId( $id )->first();
                    if ( $track ) {
                        \File::delete( public_path( '/uploads/' . $track->path ) );
                        $track->delete();
                    }
                }

                if ( count( $request->pts_remove ) > 0 ) {
                    foreach ( explode( ',', $request->pts_remove[ 0 ] ) as $item ) {
                        if ( $item != 0 )
                            Participant::findOrFail( $item )->delete();
                    }
                }
                break;
        }
        $application->fill( $request->except( 'country', 'city', 'crew' ) );
        $application->country   = $user->country;
        $application->city      = $user->city;
        $application->school_id = $user->school_id;
        $application->crew      = $user->crew;
        $application->save();


        $this->participantsFunc( $request->participants, $application->id, $request->documents, $type );

        $this->fileUploader( $request->track, $user->id, $application );

        if ( $type == 'create' ) {
            rescue( function () use ( $application ) {
                \Notification::route( 'mail', [ 'borovikov.s@gmail.com', 'denis.mirgoyazov@gmail.com' ] )
                             ->notify( new NewApplicationCreatedNotify( $application ) );
            }, false );
        }

        if ( $request->pay ) {
            return redirect()->action( 'LiqpayController@pay', $application->id );
        } else {
            return redirect()->action( 'ProfileController@applications' );
        }
    }

    protected function participantsFunc( $participants, $application_id, $documents, $type ) {
        foreach ( $participants as $i => $participant ) {
            if ( !$participant[ 'name' ] ) {
                continue;
            }
            if ( $type == 'create' ) {
                $pt = Participant::whereApplicationId( $application_id )
                                 ->whereName( $participant[ 'name' ] )->first();
            } elseif ( $type == 'edit' ) {
                $pt = Participant::whereApplicationId( $application_id )
                                 ->find( $participant[ 'id' ] );
            }
            if ( !$pt ) {
                $pt                 = new Participant();
                $pt->application_id = $application_id;
            }

            $pt->name = $participant[ 'name' ];
            $pt->save();
            if (
                isset( $documents[ $i ] )
                && $document = $documents[ $i ]
            ) {
                $doc = Document::whereParticipantId( $pt->id )->first();
                if ( !$doc ) {
                    $doc                 = new Document();
                    $doc->application_id = $application_id;
                    $doc->participant_id = $pt->id;
                }
                $filename   = $document->getClientOriginalName();
                $path       = $document->store( "documents/participant_$pt->id" );
                $doc->path  = $path;
                $doc->title = $filename;
                $doc->save();
            }
        }
    }

    protected function fileUploader( $track, $user_id, $application ) {
        if ( $track ) {
            $filename = $application->id . '_' . $track->getClientOriginalName();
            $path     = $track->storeAs( "tracks/" . $application->category->title, $filename );
            $track    = Track::whereUserId( $user_id )->whereApplicationId( $application->id )->first();
            if ( !$track ) {
                $track                 = new Track();
                $track->user_id        = $user_id;
                $track->application_id = $application->id;
            }
            $track->path  = $path;
            $track->title = $filename;
            $track->save();
        }
    }

    public function acceptEmailChange( $verify ) {
        $user = User::whereVerifyCode( $verify )->first();
        if ( $user ) {
            $user->email     = $user->new_email;
            $user->new_email = null;
            $user->save();
        } else {
            echo 'User not found';
        }

        return redirect()->action( 'ProfileController@index' );
    }

    public function removeApplication( $id ) {
        $apl = Application::findOrFail( $id );
        $pts = $apl->participants;
        foreach ( $pts as $pt ) {
            $doc = Document::whereParticipantId( $pt->id )
                           ->first();
            if ( $doc ) {
                \File::delete( public_path( "/uploads/documents/participant_$pt->id/$doc->title" ) );
                $doc->delete();
            }
            $pt->delete();
        }

        if ( $track = Track::whereUserId( $apl->user_id )->whereApplicationId( $apl->id )->first() ) {
            \File::delete( public_path( "/uploads/tracks/" . $apl->category->title . "/$track->title" ) );
            $track->delete();
        }

        $apl->delete();

        return redirect()->action( 'ProfileController@applications' );
    }

    public function tickets() {
        $tickets       = UserTicket::whereUserId( \Auth::id() )->whereIsPaid( true )->get();
        $view          = view( "profile.tickets" );
        $view->tickets = $tickets;
        return $view;
    }

    public function postTickets( Request $request ) {
        $this->validate( $request, [
            'count' => [ 'required', 'min:1', 'numeric' ]
        ] );
        $user_id              = \Auth::id();
        $transaction          = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->amount  = config( 'custom.ticket_price' ) * $request->count;
        $transaction->type_id = Transaction::TICKET_ID;
        $transaction->save();
        for ( $i = 0; $i < $request->count; $i++ ) {
            $userTicket                 = new UserTicket();
            $userTicket->user_id        = $user_id;
            $userTicket->transaction_id = $transaction->id;
            $userTicket->save();
        }

        return redirect()->action( 'LiqpayController@pay', [ 'id'   => $transaction->id,
                                                             'type' => 'ticket' ] );
    }

    public function myMasterClasses() {
        $view                = view( "profile.masterClasses" );
        $view->umcs          = $umcs = UserMasterClass::whereUserId( \Auth::id() )->whereIsPaid( true )->get();
        $view->masterClasses = MasterClass::find( $umcs->pluck( 'master_class_id' ) )->keyBy( 'id' );
        return $view;
    }

    public function masterClasses() {
        $view                = view( "public.masterClasses" );
        $dates               = MasterClass::all()->unique( 'date' )->pluck( 'date' );
        $view->dates         = $dates;
        $view->masterClasses = MasterClass::latest( 'date' )->get();
        return $view;
    }

    public function postMasterClasses( Request $request ) {
        $mc_ids        = explode( ',', $request->mc_ids );
        $masterClasses = MasterClass::find( $mc_ids );
        $count         = count( $masterClasses );
        $percents      = config('custom.master_class_percents');
        $discount      = 0;
        if ( $count == 2 || $count == 3 ) {
            $discount = $percents[ $count ];
        } elseif ( $count >= 4 ) {
            $discount = $percents[ 4 ];
        }
        $amount               = $masterClasses->sum( 'price' );
        $amount               = $amount - ( $amount * $discount / 100 );
        $user_id              = \Auth::id();
        $transaction          = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->amount  = $amount;
        $transaction->type_id = Transaction::MK_ID;
        $transaction->save();
        foreach ( $masterClasses as $masterClass ) {
            $umc                  = new UserMasterClass();
            $umc->user_id         = $user_id;
            $umc->master_class_id = $masterClass->id;
            $umc->transaction_id  = $transaction->id;
            $umc->discount        = $discount;
            $umc->save();
        }
        return redirect()->action( 'LiqpayController@pay', [ 'id' => $transaction->id, 'type' => 'master-class' ] );
    }
}
