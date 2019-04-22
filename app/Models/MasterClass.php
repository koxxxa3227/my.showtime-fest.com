<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterClass extends Model {
    protected $fillable
        = [
            'name',
            'date',
            'master_class_category_id',
            'level',
            'price',
            'address',
            'time',
            'count',
        ];
    protected $dates
        = [
            'date'
        ];

    public $timestamps = false;

    public function category() {
        return $this->belongsTo( MasterClassCategory::class, 'master_class_category_id', 'id' );
    }
}
