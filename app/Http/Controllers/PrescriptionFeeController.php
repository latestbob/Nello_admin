<?php

namespace App\Http\Controllers;

use App\Models\PrescriptionFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PrescriptionFeeController extends Controller
{
    public function index(Request $request)
    {
        $fee = PrescriptionFee::orderByDesc('id')->limit(1)->first();

        if (strtolower($request->method()) == 'post') {

            $validated = Validator::make($request->all(), [
                'fee' => 'required|numeric'
            ])->validate();

            if (!empty($fee)) $fee->update($validated);
            else $fee = PrescriptionFee::create($validated);

            session()->put('success', "Fee has been updated successfully");
        }

        return view('prescription-fee', compact('fee'));
    }
}
