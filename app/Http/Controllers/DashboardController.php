<?php

namespace App\Http\Controllers;

use App\Models\Feedbacks;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $today = Carbon::today()->format('Y-m-d');
        $month = Carbon::today()->format('Y-m');

        $locationID = null;

        if (Auth::check() && Auth::user()->admin_type == "agent") {
            $locationID = Auth::user()->location_id;
        }

        $total = [
            'order' => [
                'day' => [

                    'paid' => Order::query()->join('carts', 'orders.cart_uuid', '=',
                        'carts.cart_uuid', 'INNER')->where(['carts.vendor_id' => $request->user()->vendor_id,
                        'orders.payment_confirmed' => 1])->whereRaw(
                        "(orders.created_at between ? and ?)",
                        ["{$today} 00:00:00", "{$today} 23:59:59"]
                    )->when($locationID, function ($query, $locationID) {
                        $query->where('orders.location_id', $locationID);
                    })->distinct()->count('orders.id'),

                    'unpaid' => Order::query()->join('carts', 'orders.cart_uuid', '=',
                        'carts.cart_uuid', 'INNER')->where(['carts.vendor_id' => $request->user()->vendor_id,
                        'orders.payment_confirmed' => 0])->whereRaw(
                        "(orders.created_at between ? and ?)",
                        ["{$today} 00:00:00", "{$today} 23:59:59"]
                    )->when($locationID, function ($query, $locationID) {
                        $query->where('orders.location_id', $locationID);
                    })->distinct()->count('orders.id')

                ],
                'month' => [

                    'paid' => Order::query()->join('carts', 'orders.cart_uuid', '=',
                        'carts.cart_uuid', 'INNER')->where(['carts.vendor_id' => $request->user()->vendor_id,
                        'orders.payment_confirmed' => 1])->whereRaw(
                        "(orders.created_at between ? and ?)",
                        ["{$month}-01 00:00:00", "{$today} 23:59:59"]
                    )->when($locationID, function ($query, $locationID) {
                        $query->where('orders.location_id', $locationID);
                    })->distinct()->count('orders.id'),

                    'unpaid' => Order::query()->join('carts', 'orders.cart_uuid', '=',
                        'carts.cart_uuid', 'INNER')->where(['carts.vendor_id' => $request->user()->vendor_id,
                        'orders.payment_confirmed' => 0])->whereRaw(
                        "(orders.created_at between ? and ?)",
                        ["{$month}-01 00:00:00", "{$today} 23:59:59"]
                    )->when($locationID, function ($query, $locationID) {
                        $query->where('orders.location_id', $locationID);
                    })->distinct()->count('orders.id')

                ],
            ],
            'feedback' => [
                'day' => !$locationID ? Feedbacks::whereBetween('created_at', ["{$today} 00:00:00", "{$today} 23:59:59"])
                    ->where('vendor_id', $request->user()->vendor_id)->count('id') : 0,

                'month' => !$locationID ? Feedbacks::whereBetween('created_at', ["{$month}-01 00:00:00", "{$today} 23:59:59"])
                    ->where('vendor_id', $request->user()->vendor_id)->count('id') : 0
            ]
        ];

        $orders = Order::query()->join('carts', 'orders.cart_uuid', '=', 'carts.cart_uuid', 'INNER');

        $orders->when($locationID, function ($query, $locationID) {
            $query->where('orders.location_id', $locationID);
        });

        $orders = $orders->where('carts.vendor_id', $request->user()->vendor_id);

        $orders = $orders->select(['*', 'orders.created_at'])->selectRaw("ROUND(SUM(carts.price), 2) as amount");

        $orders = $orders->groupBy('carts.cart_uuid')->orderByDesc('orders.id')->limit(10)->get();

        $feedbacks = !$locationID ? Feedbacks::where('vendor_id', '=', $request->user()->vendor_id)->orderByDesc('id')->limit(10)->get() : null;

        return view('dashboard', compact('total', 'orders', 'feedbacks'));
    }
}
