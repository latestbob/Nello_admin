<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthTip extends Model
{
//    protected $fillable = ['tip','month','day','year','tip_range', 'uuid', 'vendor_id'];
    protected $fillable = ['uuid', 'title', 'body', 'date', 'vendor_id'];

    protected $hidden = ['vendor_id'];

}
