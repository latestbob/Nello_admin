<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorsPrescription extends Model
{
    protected $fillable = ['uuid', 'cart_uuid', 'drug_id', 'dosage', 'note', 'doctor_id', 'vendor_id'];

    public function cart() {
        return $this->hasMany('App\Models\Cart', 'cart_uuid', 'cart_uuid');
    }

    public function drug() {
        return $this->belongsTo('App\Models\PharmacyDrug', 'drug_id', 'id');
    }

    public function doctor()
    {
        return $this->belongsTo('App\Models\User', 'doctor_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id');
    }
}
