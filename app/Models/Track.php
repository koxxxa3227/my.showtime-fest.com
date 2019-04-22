<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Track extends Model {
    public    $timestamps = false;

    public function application(){
        return $this->belongsTo(Application::class);
    }
}
