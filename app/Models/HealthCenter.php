<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthCenter extends Model
{

    protected $fillable = ['name', 'logo','address1','center_type','phone','email','address2','state','city','is_active', 'uuid', 'vendor_id', 'fee'];

    protected $casts = ['is_active' => 'bool'];

}
