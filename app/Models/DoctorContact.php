<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorContact extends Model
{
    protected $fillable = ['name', 'email', 'subject', 'message', 'user_id', 'doctor_id'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function doctor() {
        return $this->belongsTo(User::class, 'doctor_id', 'id');
    }
}
