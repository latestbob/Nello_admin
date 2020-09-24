<?php

namespace App\Http\Controllers;

use App\Models\DoctorContact;
use Illuminate\Http\Request;

class DoctorMessageController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;
        $size = empty($request->size) ? 10 : $request->size;

        $messages = DoctorContact::whereHas('user', function ($query) use ($search) {

            $query->when($search, function ($query, $search) {

                $query->whereRaw(
                    "(firstname like ? or lastname like ? or phone like ? or email like ?)",
                    [
                        "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
                    ]
                );

            });

        })->when($search, function ($query, $search) {

            $query->whereRaw(
                "(name like ? or email like ? or subject like ?)",
                [
                    "%{$search}%", "%{$search}%", "%{$search}%"
                ]
            );

        });

        if ($request->user()->user_type != 'admin') $messages = $messages->where('doctor_id', $request->user()->id);
        else {
            $messages = $messages->whereHas('doctor', function ($query) use ($search) {

                $query->when($search, function ($query, $search) {

                    $query->whereRaw(
                        "(firstname like ? or lastname like ? or phone like ? or email like ?)",
                        [
                            "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
                        ]
                    );

                });

            });
        }

        $messages = $messages->paginate($size);

        return view('doctor-contacts', compact('messages', 'search', 'size'));
    }
}
