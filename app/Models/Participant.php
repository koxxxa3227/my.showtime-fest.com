<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    public $timestamps = false;

    public function document(){
        return $this->hasOne(Document::class);
    }
}
