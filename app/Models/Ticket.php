<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class);
    }
}
