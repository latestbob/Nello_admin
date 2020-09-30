<?php

namespace App\Http\Controllers;

use App\Models\CustomerPointRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerPointController extends Controller
{
    public function index(Request $request)
    {
        $rules = CustomerPointRule::orderByDesc('id')->limit(1)->first();

        if (strtolower($request->method()) == 'post') {

            $validated = Validator::make($request->all(), [
                'max_point_per_day' => 'required|numeric',
                'point_value'  => 'required|numeric',
                'earn_point_amount' => 'required|numeric'
            ])->validate();

            if (!empty($rules)) $rules->update($validated);
            else $rules = CustomerPointRule::create($validated);

            session()->put('success', "Rule has been updated successfully");
        }

        return view('point-rule', compact('rules'));
    }
}
