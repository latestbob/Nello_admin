<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Owcappointment;
use PDF;

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
        $userType = '';

        if (Auth::check() && ($userType = Auth::user()->user_type) == "agent") {
            $locationID = Auth::user()->pharmacy->location_id;
        }

        $orders = Order::when($locationID, function ($query, $locationID) {
            $query->where('location_id', $locationID);
        })->where('payment_confirmed', true);

        $total = [
            'order' => [
                'day' => [

                    'paid' => ($userType == 'admin' || $userType == 'agent') ? Order::query()->join('carts', 'orders.cart_uuid', '=',
                        'carts.cart_uuid', 'INNER')->where(['carts.vendor_id' => $request->user()->vendor_id,
                        'orders.payment_confirmed' => 1])->whereRaw(
                        "(orders.created_at between ? and ?)",
                        ["{$today} 00:00:00", "{$today} 23:59:59"]
                    )->when($locationID, function ($query, $locationID) {
                        $query->where('orders.location_id', $locationID);
                    })->distinct()->count('orders.id') : null,

                    'unpaid' => ($userType == 'admin' || $userType == 'agent') ? Order::query()->join('carts', 'orders.cart_uuid', '=',
                        'carts.cart_uuid', 'INNER')->where(['carts.vendor_id' => $request->user()->vendor_id,
                        'orders.payment_confirmed' => 0])->whereRaw(
                        "(orders.created_at between ? and ?)",
                        ["{$today} 00:00:00", "{$today} 23:59:59"]
                    )->when($locationID, function ($query, $locationID) {
                        $query->where('orders.location_id', $locationID);
                    })->distinct()->count('orders.id') : null

                ],
                'month' => [

                    'paid' => ($userType == 'admin' || $userType == 'agent') ? Order::query()->join('carts', 'orders.cart_uuid', '=',
                        'carts.cart_uuid', 'INNER')->where(['carts.vendor_id' => $request->user()->vendor_id,
                        'orders.payment_confirmed' => 1])->whereRaw(
                        "(orders.created_at between ? and ?)",
                        ["{$month}-01 00:00:00", "{$today} 23:59:59"]
                    )->when($locationID, function ($query, $locationID) {
                        $query->where('orders.location_id', $locationID);
                    })->distinct()->count('orders.id') : null,

                    'unpaid' => ($userType == 'admin' || $userType == 'agent') ? Order::query()->join('carts', 'orders.cart_uuid', '=',
                        'carts.cart_uuid', 'INNER')->where(['carts.vendor_id' => $request->user()->vendor_id,
                        'orders.payment_confirmed' => 0])->whereRaw(
                        "(orders.created_at between ? and ?)",
                        ["{$month}-01 00:00:00", "{$today} 23:59:59"]
                    )->when($locationID, function ($query, $locationID) {
                        $query->where('orders.location_id', $locationID);
                    })->distinct()->count('orders.id') : null

                ],
            ],
            'sales' => [
                'volume' => $orders->count(),
                'value' => number_format($orders->sum('amount'))
            ],
            'feedback' => [
                'day' => !$locationID ? Feedback::whereBetween('created_at', ["{$today} 00:00:00", "{$today} 23:59:59"])
                    ->where('vendor_id', $request->user()->vendor_id)->count('id') : 0,

                'month' => !$locationID ? Feedback::whereBetween('created_at', ["{$month}-01 00:00:00", "{$today} 23:59:59"])
                    ->where('vendor_id', $request->user()->vendor_id)->count('id') : 0
            ]
        ];

        $orders = Order::query()->join('carts', 'orders.cart_uuid', '=', 'carts.cart_uuid', 'INNER');

        $orders->when($locationID, function ($query, $locationID) {
            $query->where(['orders.location_id' => $locationID, 'orders.payment_confirmed' => 1]);
        });

        $orders = $orders->where('carts.vendor_id', $request->user()->vendor_id);

        $orders = $orders->select(['*', 'orders.created_at'])->selectRaw("ROUND(SUM(carts.price), 2) as amount");

        $orders = $orders->groupBy('carts.cart_uuid')->orderByDesc('orders.id')->limit(10)->get();

        $feedbacks = !$locationID ? Feedback::where('vendor_id', '=', $request->user()->vendor_id)->orderByDesc('id')->limit(10)->get() : null;

        $search = $request->search;
        $type = $request->type;

        $size = empty($request->size) ? 10 : $request->size;

        $appointments = Owcappointment::where( function ($query) use ($search) {

                $query->when($search, function ($query, $search) {

                    $query->whereRaw(
                        "(user_firstname like ? or user_lastname like ? or phone like ? or email like ?)",
                        [
                            "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
                        ]
                    );
                });

                // })->whereHas('center', function ($query) use ($search) {

                //     $query->when($search, function ($query, $search) {

                //         $query->whereRaw(
                //             "(name like ? or state like ? or phone like ? or email like ?)",
                //             [
                //                 "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
                //             ]
                //         );

                //     });

            })
            ->when($type, function ($query, $type) {

                $query->whereRaw(
                    "(caretype like ? )",
                    [
                        "%{$type}%"
                    ]
                );
            })
            ->orderBy('date', 'DESC')
            ->orderBy('time', 'DESC')
            ->paginate($size);


        return view('dashboard', compact('total', 'orders', 'feedbacks', 'userType','appointments','size','search','type'));
    }

    public function myaccount(){
        return view('myaccount');
    }
}
