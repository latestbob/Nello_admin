<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedbacks extends Model
{
    //
    protected $fillable = ['phone', 'feedback', 'experience', 'vendor_id', 'created_at'];


    public function vendor() {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id');
    }
}
