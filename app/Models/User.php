<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;

class User extends Authenticatable {
    use Notifiable;
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    // roles
    const ADMIN_ID = 1;
    const USER_ID  = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'name',
            'surname',
            'tel',
            'country',
            'city',
            'email',
            'password',
            'crew',
            'school_id',
            'verify_code'
        ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden
        = [
            'password', 'remember_token',
        ];

    public function track() {
        return $this->hasOne( Track::class );
    }

    public function applications() {
        return $this->hasMany( Application::class );
    }
    public function school(){
        return $this->belongsTo(School::class);
    }
}
