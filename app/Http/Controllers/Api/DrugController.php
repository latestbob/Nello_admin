<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\DrugsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DrugController extends Controller
{
    //

    public function import(Request $request)
    {
        Excel::import(new DrugsImport, request()->file('drugs_file'));
        return ['message' => 'Drugs imported successfully', 'status' => true ];
    }
}
