<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\DoctorImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DoctorController extends Controller
{

    public function import(Request $request)
    {
        Excel::import(new DoctorImport, request()->file('doctors_file'));
        return ['msg' => 'Doctors imported successfully'];
    }
}
