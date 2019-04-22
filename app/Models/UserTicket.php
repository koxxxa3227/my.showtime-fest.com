<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTicket extends Model
{
    protected $fillable = ['is_paid'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
