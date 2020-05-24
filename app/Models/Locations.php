<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    protected $fillable = ['id', 'name', 'price', 'uuid'];

    public function orders() {

        return $this->hasMany('App\Models\Order', 'location_id', 'id');
    }

    public function pharmacies() {

        return $this->hasMany('App\Models\Pharmacies', 'location_id', 'id');
    }

    public function riders() {

        return $this->hasMany('App\Models\User', 'location_id', 'id');
    }
}
