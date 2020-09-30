<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'payment_method', 'customer_id', 'cart_uuid',
        'amount', 'firstname', 'lastname', 'email', 'phone',
        'order_ref', 'company', 'address1', 'address2', 'location_id',
        'pickup_location_id', 'city', 'delivery_method',
        'payment_confirmed', 'accepted_pick_up', 'accepted_pick_up_by',
        'is_picked_up', 'picked_up_by', 'delivery_status', 'delivered_by'
    ];

    protected $appends = ['pending'];

    public function items() {
        return $this->hasMany('App\Models\Cart', 'cart_uuid', 'cart_uuid');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\User', 'customer_id');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location', 'location_id', 'id');
    }

    public function pickup_location()
    {
        return $this->belongsTo('App\Models\Pharmacy', 'pickup_location_id', 'id');
    }

    public function accepted_pickup() {

        return $this->belongsTo('App\Models\User', 'accepted_pick_up_by', 'id');
    }

    public function picked_up() {

        return $this->belongsTo('App\Models\User', 'picked_up_by', 'id');
    }

    public function delivered() {

        return $this->belongsTo('App\Models\User', 'delivered_by', 'id');
    }

}
