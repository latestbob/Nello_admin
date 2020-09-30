<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;
        $size = empty($request->size) ? 10 : $request->size;

        $appointments = Appointment::whereHas('user', function ($query) use ($search) {

            $query->when($search, function ($query, $search) {

                $query->whereRaw(
                    "(firstname like ? or lastname like ? or phone like ? or email like ?)",
                    [
                        "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
                    ]
                );

            });

        })->whereHas('center', function ($query) use ($search) {

            $query->when($search, function ($query, $search) {

                $query->whereRaw(
                    "(name like ? or state like ? or phone like ? or email like ?)",
                    [
                        "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
                    ]
                );

            });

        })->when($search, function ($query, $search) {

            $query->whereRaw(
                "(reason like ? or description like ? or center_uuid like ?)",
                [
                    "%{$search}%", "%{$search}%", "%{$search}%"
                ]
            );

        })->paginate($size);

        return view('appointments', compact('appointments', 'search', 'size'));
    }
}
