<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrugCategory extends Model
{
    protected $fillable = ['name'];

    public function drugs()
    {
        return $this->hasMany('App\Models\PharmacyDrug', 'category_id');
    }
}
