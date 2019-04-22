<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterClassCategory extends Model
{
    public $timestamps = false;

    protected $fillable = ['is_paid'];

    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }
}
