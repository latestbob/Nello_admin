<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyDrug extends Model
{
    protected $table = 'pharmacy_drugs';
    protected $primaryKey = 'id';

    protected $fillable = ['brand', 'name', 'price', 'require_prescription', 'image', 'category', 'uuid', 'vendor_id'];
}
