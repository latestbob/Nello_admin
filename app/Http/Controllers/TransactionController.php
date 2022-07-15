<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class TransactionController extends Controller
{
    //

    public function  index(Request $request){

        $size = empty($request->size) ? 10 : $request->size;
        $transactions = DB::table('transaction_logs')->paginate($size);        
        // if (!empty($search = $request->search)) {

        //     $transactions = DB::table('transaction_logs')->whereRaw(
        //         "(transactions.reason like ?  or transactions.gateway_reference like ? or transactions.email like ?)",
        //         [
        //             "%{$search}%", "%{$search}%", "%{$search}%", 
        //         ]
        //     );
        // }

        // $dateEnd = null;

        // if (!empty($dateStart = $request->dateStart)) {

        //     $dateEnd = $request->dateEnd ?? date('Y-m-d');

        //     $transactions = DB::table('transaction_logs')->whereRaw(
        //         "(transactions.created_at between ? and ?)",
        //         ["{$dateStart} 00:00:00", "{$dateEnd} 23:59:59"]
        //     );
        // }
        
        // $transactions = $transactions->paginate($size);
       
       return view('transactions',compact('transactions','size'));
    }
}
