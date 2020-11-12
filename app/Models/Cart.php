<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['cart_uuid', 'drug_id', 'quantity', 'price', 'prescription', 'prescribed_by', 'user_id', 'vendor_id', 'status', 'is_ready'];

    protected $appends = ['has_prescription'];

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'cart_uuid', 'cart_uuid');
    }

    public function drug()
    {
        return $this->belongsTo('App\Models\PharmacyDrug', 'drug_id', 'id');
    }

    public function doctors_prescription()
    {
        return $this->belongsTo(DoctorsPrescription::class, 'cart_uuid', 'cart_uuid');
    }

    public function vendor() {

        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id');
    }

    public function accepted_by() {

        return $this->belongsTo('App\Models\Pharmacy', 'is_ready_by', 'id');
    }

    public function getHasPrescriptionAttribute() {
        return !empty($this->prescription) || $this->doctors_prescription()->exists();
    }
}
