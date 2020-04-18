<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'payment_method', 'customer_id', 'cart_uuid',
        'amount', 'firstname', 'lastname', 'email', 'phone',
        'order_ref', 'company', 'address1', 'address2',
        'city', 'state', 'postal_code', 'payment_confirmed'
    ];

    public function items($id = null) {
        $items = $this->hasMany('App\Models\Cart', 'cart_uuid', 'cart_uuid');

        return is_numeric($id) ? $items->where(['drug_id' => $id]) : $items;
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\User', 'customer_id');
    }


}
