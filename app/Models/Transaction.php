<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
//     Types
    const TICKET_ID = 1; // Билеты
    const MK_ID = 2; // Мастер-классы

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tickets(){
        return $this->hasMany(UserTicket::class);
    }
}
