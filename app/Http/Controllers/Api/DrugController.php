<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\DrugsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Cart;

class DrugController extends Controller
{
    //

    public function import(Request $request)
    {
        Excel::import(new DrugsImport, request()->file('drugs_file'));
        return ['message' => 'Drugs imported successfully', 'status' => true ];
    }

  

    public function getdrugcart(){
        $cart = Cart::where("id",633)->first();

        return $cart;
    }
}
