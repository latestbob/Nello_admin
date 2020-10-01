<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';
    //
    protected $fillable = ['phone', 'feedback', 'experience', 'vendor_id', 'created_at'];


    public function vendor() {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id');
    }
}
