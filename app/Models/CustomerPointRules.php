<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPointRules extends Model
{
    protected $fillable = ['max_point_per_day', 'point_value', 'earn_point_amount'];
}
