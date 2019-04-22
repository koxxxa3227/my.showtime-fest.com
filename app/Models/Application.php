<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model {

    protected $fillable
        = [
            'date_id',
            'category_id',
            'country',
            'city',
            'crew',
            'school_id',
        ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function participants(){
        return $this->hasMany(Participant::class);
    }

    public function date(){
        return $this->belongsTo(Date::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function documents(){
        return $this->hasMany(Document::class);
    }

    public function track(){
        return $this->hasOne(Track::class);
    }

    public function school(){
        return $this->belongsTo(School::class);
    }
}
