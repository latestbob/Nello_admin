<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LocationController extends Controller
{

    public function index(Request $request)
    {

        $size = empty($request->size) ? 10 : $request->size;

        //$locations = Locations::where('vendor_id', '=', $request->user()->vendor_id)->orderBy('name');
        $locations = Location::orderBy('name')->when($search = $request->search, function ($query, $search) {
            $query->whereRaw(
                "(name like ? or price = ?)",
                [
                    "%{$search}%", $search
                ]
            );
        });

        $locations = $locations->paginate($size);

        return view('locations', compact('locations', 'size', 'search'));
    }

    public function addLocation(Request $request)
    {

        if (strtolower($request->method()) == "post") {

            $validated = Validator::make($request->all(), [
                'name' => 'required|string|min:2|max:255|unique:locations,name',
                'standard_price' => 'required|numeric',
                'same_day_price' => 'required|numeric',
                'next_day_price' => 'required|numeric'
            ])->validate();

            $validated['uuid'] = Str::uuid()->toString();
            $validated['vendor_id'] = $request->user()->vendor_id;

            Location::create($validated);

            return redirect("/locations")->with('success', "Location has been added successfully");
        }

        return view('location-add');
    }

    public function viewLocation(Request $request)
    {

        if (empty($uuid = $request->uuid)) {
            return redirect('/locations')->with('error', "Location ID missing");
        }

        //$location = Location::where(['uuid' => $request->uuid, 'vendor_id' => $request->user()->vendor_id])->first();
        $location = Location::where(['uuid' => $request->uuid])->first();

        if (empty($location)) {
            return redirect('/locations')->with('error', "Sorry, the ID '{$request->uuid}' is associated with any location");
        }

        if (strtolower($request->method()) == "post") {

            $validated = Validator::make($request->all(), [
                'name' => [
                    'required', 'string', 'min:2', 'max:255',
                    Rule::unique('locations', 'id')->ignore($location->id)
                ],
                'standard_price' => 'required|numeric',
                'same_day_price' => 'required|numeric',
                'next_day_price' => 'required|numeric'
            ])->validate();

            $location->update($validated);

            return redirect('/locations')->with('success', "Location has been updated successfully");
        }

        return view('location-view', compact('location', 'uuid'));
    }

    public function deleteLocation(Request $request)
    {

        if (!$request->uuid) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid request, missing location id',
            ]);
        }

        $delete = Location::where(['uuid' => $request->uuid])->first();

        if (!$delete->delete()) {

            return response()->json([
                'status' => false,
                'message' => 'Sorry, we could not delete this location at this time, please try again later',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Location has been deleted successfully',
        ]);
    }
}
