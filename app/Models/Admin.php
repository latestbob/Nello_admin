<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'uuid', 'name', 'email', 'phone', 'picture', 'address', 'vendor_id', 'location_id', 'password', 'admin_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function vendor() {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id');
    }

    public function location() {
        return $this->belongsTo('App\Models\Location', 'location_id', 'id');
    }
}
