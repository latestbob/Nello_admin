<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_id', 'token',
        'firstname','lastname','middlename','email','phone',
        'user_type','aos','cwork','password','picture','dob',
        'hwg','is_seen','ufield','height','weight','gender','source',
        'session_id','address','state','city','religion','sponsor',
        'uuid', 'local_saved', 'pharmacy_id', 'location_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'token'
    ];

    public function vendor() {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id');
    }

    public function pharmacy() {
        return $this->belongsTo('App\Models\Pharmacies', 'pharmacy_id', 'id');
    }

    public function location() {
        return $this->belongsTo('App\Models\Locations', 'location_id', 'id');
    }

    public function prescriptions() {
        return $this->hasMany(DoctorsPrescriptions::class, 'doctor_id', 'id');
    }

}
