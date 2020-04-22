<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

    protected $fillable = ['name', 'price', 'uuid', 'vendor_id'];

    public function orders() {

        return $this->hasMany('App\Models\Order', 'location_id', 'id');
    }
}
