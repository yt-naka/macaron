<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'id', 'role', 'name', 'money', 'tax', 'government_id', 'chain',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

}
