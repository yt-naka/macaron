<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'owner_id', 'content', 'price', 'place',
    ];
}
