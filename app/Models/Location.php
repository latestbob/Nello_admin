<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['id', 'name', 'standard_price', 'same_day_price', 'next_day_price', 'uuid'];

    public function orders() {

        return $this->hasMany('App\Models\Order', 'location_id', 'id');
    }

    public function pharmacies() {

        return $this->hasMany('App\Models\Pharmacy', 'location_id', 'id');
    }

    public function riders() {

        return $this->hasMany('App\Models\User', 'location_id', 'id');
    }

    public function agents()
    {
        return $this->hasManyThrough(
            'App\Models\User', 
            'App\Models\Pharmacy',
            'location_id',
            'pharmacy_id'
        );
    }
}
