<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\DrugExport;
use Maatwebsite\Excel\Facades\Excel;


class ExportController extends Controller
{
    //

    public function exportdrug(){
        return Excel::download(new DrugExport, 'drugs.xlsx');
    }
}
