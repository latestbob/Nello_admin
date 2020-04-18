<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vendor_id', 'firstname','lastname','middlename','email','phone',
        'user_type','aos','cwork','password','picture','dob',
        'hwg','is_seen','ufield','height','weight','gender','source',
        'address','state','city','religion','sponsor', 'uuid'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'token'
    ];

}
