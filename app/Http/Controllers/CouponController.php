<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $size = empty($request->size) ? 10 : $request->size;
        $search = $request->search;
        $coupons = Coupon::when($search, function($query, $search){
            $query->where('name', 'LIKE', "%{$search}%")
            ->orWhere('code', 'LIKE', "%{$search}%")
            ->orWhere('type', 'LIKE', "%{$search}%")
            ->orWhere('value', 'LIKE', "%{$search}%")
            ;
            ;
        })->paginate($size);

        return view('drug-coupons', compact('size', 'search', 'coupons'));
    }

    public function create(Request $request) 
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'name' => 'required|unique:coupons',
                'code' => 'required|unique:coupons|min:6|max:12',
                'type' => 'required|in:amount,percentage',
                'value' => 'required|numeric'
            ]);

            Coupon::create($data);

            return redirect('/drug/coupons')
                ->with('success', "Coupon has been created successfully");
        }

        return view('drug-coupons-add');
    }

    public function update(Coupon $coupon, Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'name' => [
                    'required',
                    Rule::unique('coupons')->ignore($coupon->id)
                ],
                'code' => [
                    'required',
                    Rule::unique('coupons')->ignore($coupon->id)
                ],
                'type' => 'required|in:amount,percentage',
                'value' => 'required|numeric'
            ]);

            $coupon->update($data);

            return redirect('/drug/coupons')
                ->with('success', "Coupon has been updated successfully");
        }

        return view('drug-coupons-edit', compact('coupon'));
    }

    public function delete(Request $request)
    {
        Coupon::destroy($request->id);

        return response([
            'status' => true,
            'message' => "Coupon deleted successfully"
        ]);
    }
}
