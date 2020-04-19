<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{

    public function index(Request $request)
    {

        $search = $request->search;
        $gender = $request->gender;
        $size = empty($request->size) ? 10 : $request->size;

        $doctors = User::where('user_type', 'doctor')
            ->when($search, function ($query, $search) {

                $query->whereRaw(
                    "(firstname like ? or lastname like ? or phone like ? or email like ? or aos like ?)",
                    [
                        "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
                    ]
                );

            })->when($gender, function ($query, $gender) {

                $query->where('gender', '=', "{$gender}");

            })->paginate($size);

        return view('doctors', compact('doctors', 'search', 'gender', 'size'));
    }

    public function doctorView(Request $request) {

        if (empty($uuid = $request->uuid)) {
            return redirect('/doctors')->with('error', "Doctor ID missing");
        }

        $doctor = User::where(['user_type' => 'doctor', 'uuid' => $request->uuid])->first();

        if (empty($doctor)) {
            return redirect('/doctors')->with('error', "Sorry, the ID '{$request->uuid}' is not associated with any doctor account");
        }

        if (strtolower($request->method()) == "post") {

            Validator::make($data = $request->all(), [
                'firstname' => 'required|string|max:50',
                'lastname'  => 'required|string|max:50',
                'middlename' => 'nullable|string|max:50',
                'email' => ['required', 'string', 'email', 'max:255',
                    Rule::unique('users')->ignore($doctor->id)],
                'phone' => ['required', 'numeric',
                    Rule::unique('users')->ignore($doctor->id)],
                'dob' => 'required|date_format:Y-m-d|before_or_equal:today',
                'address' => 'nullable|string',
                'state' => 'nullable|string',
                'city'  => 'nullable|string',
                'religion' => 'nullable|string',
                'gender' => 'required|string|in:Male,Female',
                'height' => 'nullable|numeric',
                'weight' => 'nullable|numeric',
                'sponsor' => 'nullable|string',
            ])->validate();

            if (!empty($data['dob'])) {
                $data['dob'] = Carbon::parse($data['dob'])->toDateString();
            }

            $doctor->update($data);

            session()->put('success', "{$doctor->firstname}'s profile has been updated successfully");

        }

        return view('doctor-view', compact('doctor', 'uuid'));
    }
}
