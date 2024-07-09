<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Botmessage extends Model
{
    //

    protected $fillable = ['next_step', 'parent_param', 'isAuth'];
}
