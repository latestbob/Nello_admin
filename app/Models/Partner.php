<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = [
        'name',
        'email',
        'api_key'
    ];

    protected $hidden = ['api_key'];
    
}
