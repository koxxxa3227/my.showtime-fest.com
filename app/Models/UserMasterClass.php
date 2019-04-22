<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMasterClass extends Model
{
    //

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    public function masterClass(){
        return $this->belongsTo(MasterClass::class);
    }
}
