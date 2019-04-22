<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Models\City;
use App\Models\Date;
use App\Models\Document;
use App\Models\MasterClass;
use App\Models\MasterClassCategory;
use App\Models\Participant;
use App\Models\School;
use App\Models\Track;
use App\Models\User;
use App\Models\UserMasterClass;
use App\Models\UserTicket;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminController extends Controller {
    public function index() {
        $users = User::with( 'school' )->get();

        $view        = view( "admin.users.index" );
        $view->users = $users;
        return $view;
    }

    public function editUser( $id ) {
        $user    = User::findOrFail( $id );
        $schools = School::all();

        $view          = view( "admin.users.edit" );
        $view->schools = $schools;
        $view->user    = $user;
        return $view;
    }

    public function postEditUser( Request $request, $id ) {
        $msg  = 'Сохранено';
        $type = 'success';

        $user = User::findOrFail( $id );
        $user->fill( $request->all() );
        $school = School::whereTitle( $request->school )->first();
        if ( !$school ) {
            $school        = new School();
            $school->title = $request->school;
            $school->save();
        }
        $user->role_id   = $request->role_id;
        $user->school_id = $school->id;
        $user->save();

        flash( $msg, $type );
        return redirect()->action( 'AdminController@index' );
    }

    public function categories( $type, $id = null ) {
        $category = null;
        switch ( $type ) {
            case 'create':
                $category = new Category();
                break;
            case 'edit':
                $category = Category::findOrFail( $id );
                break;
        }

        $view             = view( "admin.categories" );
        $view->categories = Category::all();
        $view->dates      = Date::oldest( 'title' )->get();
        $view->category   = $category;
        $view->type       = $type;
        return $view;
    }

    public function postCategories( Request $request, $type, $id = null ) {
        $category = null;
        switch ( $type ) {
            case 'create':
                $category = new Category();
                break;
            case 'edit':
                $category = Category::findOrFail( $id );
                break;
        }
        $category->fill( $request->except( 'without_track' ) );
        $category->without_track = $request->without_track ?: false;
        $category->save();

        return redirect()->action( 'AdminController@categories', 'create' );
    }

    public function removeCategory( $id ) {
        $category = Category::findOrFail( $id );

        $category->delete();

        flash( 'Категория удалена' );
        return back();
    }

    public function dates( $type, $id = null ) {
        $date = null;
        switch ( $type ) {
            case 'create':
                $date = new Date();
                break;
            case 'edit':
                $date = Date::findOrFail( $id );
                break;
        }

        $view        = view( "admin.dates" );
        $view->dates = Date::latest( 'title' )->get();
        $view->date  = $date;
        $view->type  = $type;
        return $view;
    }

    public function postDates( Request $request, $type, $id = null ) {
        $date = null;
        switch ( $type ) {
            case 'create':
                $date = new Date();
                break;
            case 'edit':
                $date = Date::findOrFail( $id );
                break;
        }
        $date->fill( $request->all() );
        $date->save();

        return redirect()->action( 'AdminController@dates', 'create' );
    }

    public function applications() {
        $apps               = Application::with( 'user', 'date', 'category', 'track', 'school' )
                                         ->latest( 'id' )
                                         ->get();
        $app_paid_count     = Application::whereIsPaid( true )->count();
        $app_accepted_count = Application::whereAccepted( true )->count();
        $app_with_tracks    = 0;
        $app_with_docs      = 0;
        $documents          = [];
        $pts_count          = [];
        foreach ( $apps as $app ) {
            $documents[ $app->id ] = $app->documents->count();
            $pts_count[ $app->id ] = $app->participants()->count();
            if ( $app->track ) {
                $app_with_tracks++;
            }
            if ( Document::whereApplicationId( $app->id )->first() ) {
                $app_with_docs++;
            }
        }
        $data = [
            'apps_count'          => $app->count(),
            'apps_paid_count'     => $app_paid_count,
            'apps_accepted_count' => $app_accepted_count,
            'apps_with_tracks'    => $app_with_tracks,
            'apps_with_docs'      => $app_with_docs
        ];

        $view                     = view( 'admin.applications.index' );
        $view->applications       = json_encode( $apps );
        $view->documents          = json_encode( $documents );
        $view->participants_count = json_encode( $pts_count );
        $view->_data              = $data;
        return $view;
    }

    public function removeApplication( $id ) {
        $appl = Application::findOrFail( $id );
        if ( $docs = Document::whereApplicationId( $id )->get() ) {
            foreach ( $docs as $doc ) {
                \File::delete( public_path( '/uploads/' . $doc->path ) );
            }
        }

        if ( $track = Track::whereApplicationId( $id )->first() ) {
            \File::delete( public_path( '/uploads/' . $track->path ) );
            $track->delete();
        }

        $appl->delete();

        return back();
    }

    public function editApplication( $id ) {
        $apl = Application::with( 'participants', 'documents', 'track', 'user', 'date', 'category' )
                          ->findOrFail( $id );
        $pts = $apl->participants;

        $view      = view( "admin.applications.edit" );
        $view->apl = $apl;
        $view->pts = $pts;
        return $view;
    }

    public function postEditApplication( Request $request, $id ) {
        //        dd($request->all());
        $apl       = Application::findOrFail( $id );
        $documents = Document::whereApplicationId( $id )->get();
        foreach ( $documents as $i => $document ) {
            $document->accepted = $request->accepted_docs[ $i ] ?: false;
            $document->save();
        }
        $apl->accepted = $request->accepted ?: false;
        $apl->is_paid  = $request->is_paid ?: false;
        $apl->save();

        flash( 'Сохранено' );
        return redirect()->action( 'AdminController@applications' );
    }

    public function schools( $type, $id = null ) {
        $view   = view( "admin.schools" );
        $school = null;
        switch ( $type ) {
            case 'create':
                $school = new School();

                $schools = School::latest( 'title' )->get();

                $usersCount = [];
                $appsCount  = [];
                $canRemove  = [];


                foreach ( $schools as $item ) {
                    if ( !isset( $usersCount[ $item->id ] ) ) {
                        $usersCount[ $item->id ] = 0;
                    }
                    if ( !isset( $appsCount[ $item->id ] ) ) {
                        $appsCount[ $item->id ] = 0;
                    }
                    $users_count             = User::whereSchoolId( $item->id )->count();
                    $usersCount[ $item->id ] += $users_count;
                    $apps_count              = Application::whereSchoolId( $item->id )->count();
                    $appsCount[ $item->id ]  += $apps_count;
                    $removed                 = true;

                    if (
                        User::whereSchoolId( $item->id )->first()
                        || Application::whereSchoolId( $item->id )->first()
                    ) {
                        $removed = false;
                    }

                    $canRemove[ $item->id ] = $removed;
                    $view->canRemove        = $canRemove;
                }

                $view->usersCount = $usersCount;
                $view->appsCount  = $appsCount;

                $view->schools = $schools;
                break;
            case 'edit':
                $school = School::findOrFail( $id );
                break;
        }

        $view->school = $school;
        $view->type   = $type;
        return $view;
    }

    public function postSchools( Request $request, $type, $id = null ) {
        switch ( $type ) {
            case 'create':
                $school = new School();
                break;
            case 'edit':
                $school = School::findOrFail( $id );
                break;
        }

        $school->title = $request->title;
        $school->save();

        flash( 'Сохранено' );
        return redirect()->action( 'AdminController@schools', 'create' );
    }

    public function removeSchool( $id ) {
        $school = School::findOrFail( $id );

        User::whereSchoolId( $id )->update( [
                                                'school_id' => null,
                                            ] );
        Application::whereSchoolId( $id )->update( [
                                                       'school_id' => null
                                                   ] );

        $school->delete();

        flash( 'Удалено' );

        return redirect()->action( 'AdminController@schools', 'create' );
    }

    public function loginLikeUser( $id ) {
        $user = User::findOrFail( $id );
        \Auth::logout();

        \Auth::login( $user );

        return redirect()->action( 'ProfileController@index' );
    }

    public function getApplicationsInExcel() {
        $applications = Application::with( 'user', 'track', 'school', 'category', 'participants' )->latest()->get();
        $rows         = [];

        foreach ( $applications as $application ) {
            $docs_statuses = '';
            $documents     = $application->documents;
            foreach ( $documents as $document ) {
                $docs_statuses .= ' ' . ( $document->accepeted ? '+' : '-' );
            }
            $row    = [
                $application->id,
                $application->user->name,
                $application->user->email,
                $application->date->title,
                $application->category->title,
                $application->city,
                $application->school->title,
                $application->track ? '+' : '-',
                $application->accepted ? '+' : '-',
                $application->is_paid ? '+' : '-',
                $application->category->price * $application->participants->count(),
                $documents->count(),
                $docs_statuses,
                $application->created_at->format( 'd.m.Y H:i' )
            ];
            $rows[] = $row;
        }

        $file = WriterFactory::create( Type::XLSX );
        $file->setTempFolder( storage_path() . '/xlsx' )
             ->setShouldUseInlineStrings( false )
             ->openToFile( storage_path() . "/xlsx/Заявки.xlsx" )
             ->addRow( array(
                           '#',
                           'Участник',
                           'Почта',
                           'Дата',
                           'Категория',
                           'Город',
                           'Студия/Школа',
                           'Трек',
                           'Статус',
                           'Оплачен',
                           'Сумма заявки',
                           'Кол-во документов',
                           'Статус документов',
                           'Дата создания заявки'
                       ) )
             ->addRows( $rows );
        $file->close();
        return response()->download( storage_path() . '/xlsx/Заявки.xlsx' );
    }

    public function tickets() {
        $view          = view( "admin.tickets" );
        $view->tickets = Transaction::whereIsPaid( true )
                                    ->whereTypeId( Transaction::TICKET_ID )
                                    ->latest()
                                    ->get();
        return $view;
    }

    public function masterClasses( $type, $id = null ) {
        $view                = view( "admin.masterClasses.index" );
        $view->masterClasses = $masterClasses = MasterClass::with( 'category' )
                                                           ->latest( 'id' )
                                                           ->get();
        $view->categories    = MasterClassCategory::find( $masterClasses->pluck( 'master_class_category_id' ) )
                                                  ->keyBy( 'id' );
        $view->type          = $type;
        return $view;
    }

    public function masterClass( $type, $id = null ) {
        $masterClass = new MasterClass();
        if ( $type == 'edit' ) {
            $masterClass = MasterClass::findOrFail( $id );
        }

        $view              = view( "admin.masterClasses.create" );
        $view->categories  = MasterClassCategory::all();
        $view->masterClass = $masterClass;
        $view->type        = $type;
        return $view;
    }

    public function postMasterClass( Request $request, $type, $id = null ) {
        $msg         = 'Сохранено';
        $msg_type    = 'success';
        $masterClass = new MasterClass();
        if ( $type == 'edit' ) {
            $masterClass = MasterClass::findOrFail( $id );
            if ( $request->image ) {
                \File::delete( public_path( "/uploads/$masterClass->image" ) );
            }
        }

        $masterClass->fill( $request->all() );
        if ( $image = $request->image ) {
            $masterClass->image = $image->store( '/master-classes' );
        }
        $masterClass->save();

        flash( $msg, $msg_type );
        return redirect()->action( 'AdminController@masterClasses' );
    }

    public function masterClassCategories( $type, $id = null ) {
        $view     = view( "admin.masterClasses.categories" );
        $category = false;
        switch ( $type ) {
            case 'create':
                $category         = new MasterClassCategory();
                $view->categories = MasterClassCategory::latest( 'id' )->get();
                break;
            case 'edit':
                $category = MasterClassCategory::findOrFail( $id );
                break;
        }
        $view->category = $category;
        $view->type     = $type;
        return $view;
    }

    public function postMasterClassCategories( Request $request, $type, $id = null ) {
        $msg                 = 'Сохранено';
        $msg_type            = 'success';
        $masterClassCategory = new MasterClassCategory();

        if ( $type == 'edit' ) {
            $masterClassCategory = MasterClassCategory::findOrFail( $id );
        }
        $masterClassCategory->title = $request->title;
        $masterClassCategory->save();

        flash( $msg, $msg_type );
        return redirect()->action( 'AdminController@masterClassCategories', 'create' );
    }

    public function masterClassRequests() {
        $reqquests = UserMasterClass::with( 'transaction', 'masterClass', 'user' )
                                    ->where( 'user_master_classes.is_paid', true )
                                    ->select( 'user_master_classes.*', 'transactions.amount' )
                                    ->leftJoin( 'transactions', 'user_master_classes.transaction_id', '=', 'transactions.id' );

        $masterClasses = MasterClass::find( $reqquests->pluck( 'master_class_id' ) )->keyBy( 'id' );
        $users         = User::find( $reqquests->pluck( 'user_id' ) )->keyBy( 'id' );

        $view                = view( "admin.masterClasses.requests" );
        $view->requests      = $reqquests->latest( 'user_master_classes.created_at' )->get();
        $view->masterClasses = $masterClasses;
        $view->users         = $users;
        return $view;
    }
}
