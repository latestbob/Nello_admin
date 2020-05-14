<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Location;
use App\Traits\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    use FileUpload;

    public function viewAgents(Request $request)
    {
        $search = $request->search;
        $size = empty($request->size) ? 10 : $request->size;

        $agents = Admin::where('admin_type', 'agent')->when($search, function ($query, $search) {

            $query->whereRaw(
                "(name like ? or phone like ? or email like ?)",
                [
                    "%{$search}%", "%{$search}%", "%{$search}%"
                ]
            );

        })->paginate($size);

        return view('admin-agents', compact('agents', 'search', 'size'));
    }

    public function addAgent(Request $request)
    {

        if (strtolower($request->method()) == "post") {

            Validator::make($data = $request->all(), [
                'name' => 'required|string|max:50',
                'email' => 'required|string|email|max:255|unique:admins,email',
                'phone' => 'required|string|max:14|unique:admins,phone',
                'address' => 'required|string|max:255',
                'password' => 'required|string|min:6',
                'confirm_password' => 'required_with:password|string|same:password',
                'location' => 'required|numeric|exists:locations,id',
                'picture' => 'nullable|image|mimes:jpeg,jpg,png'
            ])->validate();

            if ($request->hasFile('picture')) {

                $data['picture'] = $this->uploadFile($request, 'picture');
            }

            $data['password'] = Hash::make($data['password']);
            unset($data['confirm_password']);

            $data['location_id'] = $data['location'];
            unset($data['location']);

            $data['uuid'] = Str::uuid()->toString();

            $data['vendor_id'] = $request->user()->vendor_id;

            $data['admin_type'] = 'agent';

            $admin = Admin::create($data);

            return redirect("admin/{$admin->uuid}/view")->with('success', "Agent has been added successfully");

        }

        $locations = Location::where('price', '>', 0)->get();

        return view('admin-agent-add', compact('locations'));
    }

    public function viewAgent(Request $request)
    {

        if (empty($uuid = $request->uuid)) {
            return redirect('/agents')->with('error', "Agent ID missing");
        }

        $agent = Admin::where(['admin_type' => 'agent', 'uuid' => $request->uuid])->first();

        if (strtolower($request->method()) == "post") {

            Validator::make($data = $request->all(), [
                'name' => 'required|string|max:50',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($agent->id)],
                'phone' => ['required', 'numeric', Rule::unique('admins')->ignore($agent->id)],
                'address' => 'required|string|max:255',
                'location' => 'required|numeric|exists:locations,id',
                'picture' => 'nullable|image|mimes:jpeg,jpg,png'
            ])->validate();

            if ($request->hasFile('picture')) {

                $data['picture'] = $this->uploadFile($request, 'picture');
            }

            $data['location_id'] = $data['location'];
            unset($data['location']);

            $agent->update($data);

            session()->put('success', "{$agent->name}'s profile has been updated successfully");
        }

        $locations = Location::where('price', '>', 0)->get();

        return view('admin-agent-view', compact('agent', 'locations'));
    }
}
