<?php

namespace App\Http\Controllers;

use App\Models\DoctorsPrescription;
use Illuminate\Http\Request;

class DoctorsPrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $error = "";

        if (empty($uuid = $request->uuid)) {
            $error = "Prescription ID not found";
        }

        $prescriptions = DoctorsPrescription::where('cart_uuid', $uuid);

        if (empty($prescriptions->first())) {
            $error = "Prescription not found";
        }

        $prescriptions = $prescriptions->get();

        return view('doctors-drug-prescription', compact('error', 'prescriptions'));
    }
}
