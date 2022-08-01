<?php

namespace App\Http\Controllers;

use App\Models\HealthCenter;
use App\Traits\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;


class HealthCenterController extends Controller
{

    use FileUpload;

    public function index(Request $request)
    {
        $search = $request->search;
        $size = empty($request->size) ? 10 : $request->size;

        $healthCenters = HealthCenter::when($search, function ($query, $search) {

                $query->whereRaw(
                    "(name like ? or phone like ? or email like ? or city like ? or state like ?)",
                    [
                        "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"
                    ]
                );

            })->paginate($size);

        return view('health-centers', compact('healthCenters', 'search', 'size'));
    }

    public function viewHealthCenter(Request $request) {

        if (empty($uuid = $request->uuid)) {
            return redirect('/health-centers')->with('error', "Health Center ID missing");
        }

        $healthCenter = HealthCenter::where('uuid', $request->uuid)->first();

        //dd($healthCenter);

        if (empty($healthCenter)) {
            return redirect('/health-centers')->with('error', "Sorry, the ID '{$request->uuid}' is not associated with any health center");
        }

        if (strtolower($request->method()) == "post") {

            Validator::make($data = $request->all(), [
                'name' => 'required|string|max:50',
                'email' => ['required', 'string', 'email', 'max:255',
                    Rule::unique('health_centers', 'email')->ignore($healthCenter->id)],
                'phone' => 'required',
                'address1' => 'nullable|string',
           
                'state' => 'nullable|string',
                'city'  => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,jpg,png',
                'fee' => 'nullable'
            ])->validate();

            if ($request->hasFile('logo')) {
                $data['logo'] = $this->uploadFile($request, 'logo');
            }

            // $healthCenter->update($data);
            $healthCenterr = HealthCenter::where('uuid', $request->uuid)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address1' => $request->address1,
                'state' => $request->state,
                'city' => $request->city,
                'fee' => $request->fee
            ]);

            return redirect("/health-centers")->with('success', "Health center has been updated successfully");

        }

        // $response = Http::get('http://locationsng-api.herokuapp.com/api/v1/states');

        // $states =  $response->json();
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            
        ])->get('https://api.facts.ng/v1/states');
         $states =  $response->json();
         
        return view('health-center-view', compact('healthCenter', 'uuid','states'));
    }

    public function addHealthCenter(Request $request) {

        if (strtolower($request->method()) == "post") {

            $data = Validator::make($request->all(), [
                'name' => 'required|string|max:50',
                'email' => 'required|string|email|max:255|unique:health_centers,email',
                'phone' => 'required|digits_between:11,16|unique:health_centers,phone',
                'address1' => 'required|string',
                'address2' => 'nullable|string',
                'state' => 'required|string',
                'city'  => 'required|string',
                'center_type'  => 'required|alpha|min:3',
                'logo' => 'nullable|image|mimes:jpeg,jpg,png',
                'fee' => 'required|numeric'
            ])->validate();

            if ($request->hasFile('logo')) {
                $data['logo'] = $this->uploadFile($request, 'logo');
            }

            $data['vendor_id'] = $request->user()->vendor_id;
            $data['uuid'] = Str::uuid()->toString();
            HealthCenter::create($data);

            return redirect("/health-centers")->with('success', "Health Center has been added successfully");

        }

        // $response = Http::get('https://api.facts.ng/v1/states');

        // $states =  $response->json();

        // return view('health-center-add',compact('states'));
        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            
        ])->get('https://api.facts.ng/v1/states');
         $states =  $response->json();

        return view('health-center-add',compact('states'));
    }

    public function changeStatus(Request $request) {

        if (empty($request->uuid)) {
            return response([
                'status' => false,
                'message' => "Health Center ID missing"
            ]);
        }

        $healthCenter = HealthCenter::where('uuid', $request->uuid)->first();

        if (empty($healthCenter)) {
            return response([
                'status' => false,
                'message' => "Sorry, the ID '{$request->uuid}' is associated with any health center"
            ]);
        }

        if (!$healthCenter->update(['is_active' => !$healthCenter->is_active])) {
            return response([
                'status' => false,
                'message' => "Sorry, we could not " . ($healthCenter->is_active == true ? 'activate' : 'deactivate') . " this health center at this time."
            ]);
        }

        return response([
            'status' => true,
            'message' => "This health center is now " . ($healthCenter->is_active == true ? 'active' : 'inactive')
        ]);
    }
}
