<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';
    protected $fillable = ['uuid', 'description','user_uuid','status','reason','date','time','location','source','session_id','ref_no','center_uuid'];

    public function center()
    {
        return $this->hasOne(HealthCenter::class, 'uuid', 'center_uuid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
