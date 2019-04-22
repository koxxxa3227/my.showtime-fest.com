<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'country_id',
        'title'
    ];

    public function country(){
        return $this->belongsTo(Country::class);
    }
}
