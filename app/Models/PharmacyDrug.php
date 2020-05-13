<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyDrug extends Model
{
    protected $table = 'pharmacy_drugs';
    protected $primaryKey = 'id';

    protected $fillable = ['brand', 'name', 'description', 'price', 'require_prescription', 'image', 'uuid', 'vendor_id', 'category_id' , 'dosage_type'];

    public function category() {
        return $this->belongsTo('App\Models\DrugCategory', 'category_id', 'id');
    }
}
