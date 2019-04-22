<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    public $timestamps = false;

    protected $fillable
        = [
            'date_id',
            'price',
            'min_participant_count',
            'participant_count',
            'title',
            'length'
        ];

    public function date(){
        return $this->belongsTo(Date::class);
    }
}
