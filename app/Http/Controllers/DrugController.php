<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DrugCategory;
use App\Models\Location;
use App\Models\Order;
use App\Models\PharmacyDrug;
use App\Traits\FileUpload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DrugController extends Controller
{

    use FileUpload;

    public function drugs(Request $request)
    {

        $size = empty($request->size) ? 10 : $request->size;
        $category = $request->category;
        $search = $request->search;

        $drugs = PharmacyDrug::when($search, function ($query, $search) {

            $query->whereRaw(
                "(name like ? or brand like ? or uuid = ?)",
                [
                    "%{$search}%", "%{$search}%", $search
                ]
            );

        })->when($category, function ($query, $category) {

            $query->where('category_id', $category);

        })->where('vendor_id', $request->user()->vendor_id)->orderBy('name')->paginate($size);

        $categories = DrugCategory::groupBy('name')->get();

        return view('drugs', compact('drugs', 'search', 'size', 'categories', 'category'));
    }

    public function drugView(Request $request)
    {

        if (empty($uuid = $request->uuid)) {
            return redirect('/drugs')->with('error', "Drug ID missing");
        }

        $drug = PharmacyDrug::where(['uuid' => $request->uuid, 'vendor_id' => $request->user()->vendor_id])->first();

        $categories = DrugCategory::groupBy('name')->get();

        if (empty($drug)) {
            return redirect('/drugs')->with('error', "Sorry, the ID '{$request->uuid}' is associated with any drug");
        }

        if (strtolower($request->method()) == "post") {

            Validator::make($data = $request->all(), [
                'name' => 'required|string|max:50',
                'brand' => 'required|string|max:50',
                'category' => 'required|numeric|exists:drug_categories,id',
                'description' => 'required|string|max:255',
                'dosage_type' => 'required|string|max:50',
                'price' => 'required|numeric',
                'image' => 'nullable|image|mimes:jpeg,jpg,png'
            ])->validate();

            if ($request->hasFile('image')) {

                $data['image'] = $this->uploadFile($request, 'image');
//            $data['image'] = 'http://www.famacare.com/img/famacare.png';

            }

            $data['require_prescription'] = $request->has('prescription') ? 1 : 0;

            $data['category_id'] = $data['category'];
            unset($data['category']);

            $drug->update($data);

            session()->put('success', "Drug has been updated successfully");

        }

        return view('drug-view', compact('drug', 'uuid', 'categories'));
    }

    public function drugAdd(Request $request)
    {

        if (strtolower($request->method()) == "post") {

            Validator::make($data = $request->all(), [
                'name' => 'required|string|max:50',
                'brand' => 'required|string|max:50',
                'category' => 'required|numeric|exists:drug_categories,id',
                'description' => 'required|string|max:255',
                'dosage_type' => 'required|string|max:50',
                'price' => 'required|numeric',
                'image' => 'nullable|image|mimes:jpeg,jpg,png'
            ])->validate();

            if ($request->hasFile('image')) {

                $data['image'] = $this->uploadFile($request, 'image');
//            $data['image'] = 'http://www.famacare.com/img/famacare.png';

            }

            $data['require_prescription'] = $request->has('prescription') ? 1 : 0;

            $data['uuid'] = Str::uuid()->toString();

            $data['vendor_id'] = $request->user()->vendor_id;

            $data['category_id'] = $data['category'];
            unset($data['category']);

            $drug = PharmacyDrug::create($data);

            return redirect("drug/{$drug->uuid}/view")->with('success', "Drug has been added successfully");

        }

        $categories = DrugCategory::groupBy('name')->get();

        return view('drug-add', compact('categories'));
    }

    public function drugDelete(Request $request)
    {

        if (empty($uuid = $request->uuid)) {
            return response([
                'status' => false,
                'message' => "Drug ID missing"
            ]);
        }

        $drug = PharmacyDrug::where(['uuid' => $request->uuid, 'vendor_id' => $request->user()->vendor_id])->first();

        if (empty($drug)) {
            return response([
                'status' => false,
                'message' => "Sorry, the ID '{$request->uuid}' is associated with any drug"
            ]);
        }

        $drug->delete();

        return response([
            'status' => true,
            'message' => "Drug deleted successfully"
        ]);

    }

    public function drugOrders(Request $request)
    {

        $size = empty($request->size) ? 10 : $request->size;

        $locationID = null;

        if (Auth::check() && Auth::user()->admin_type == "agent") {
            $locationID = Auth::user()->location_id;
        }

        $orders = Order::query()->join('carts', 'orders.cart_uuid', '=', 'carts.cart_uuid', 'INNER');

        $orders->when($locationID, function ($query, $locationID) {
            $query->where('orders.location_id', $locationID);
        });

        if (!empty($search = $request->search)) {

            $orders = $orders->whereRaw(
                "(orders.firstname like ? or orders.lastname like ? or
                orders.phone like ? or orders.email like ? or
                orders.company like ? or orders.city = ? or
                orders.state = ? or orders.order_ref = ? or
                orders.cart_uuid = ?)",
                [
                    "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%",
                    "%{$search}%", $search, $search, $search, $search
                ]
            );
        }

        if (!empty($payment = $request->payment) && ($payment == 'paid' || $payment == 'unpaid')) {
            $orders = $orders->where('orders.payment_confirmed', '=', ($payment == 'paid' ? 1 : 0));
        }

        $dateEnd = null;

        if (!empty($dateStart = $request->dateStart)) {

            $dateEnd = $request->dateEnd ?? date('Y-m-d');

            $orders = $orders->whereRaw(
                "(orders.created_at between ? and ?)",
                ["{$dateStart} 00:00:00", "{$dateEnd} 23:59:59"]
            );
        }

        $location = null;

        if (empty($locationID) && !empty($location = $request->location)) {

            $orders = $orders->where('orders.location_id', $location);
        }

        $orders = $orders->where('carts.vendor_id', $request->user()->vendor_id);

        $orders = $orders->groupBy('carts.cart_uuid')->orderByDesc('orders.id');

        $orders = $orders->paginate($size);

        $total = [

            'paid' => Order::query()->join('carts', 'orders.cart_uuid', '=',
                'carts.cart_uuid', 'INNER')->where(['carts.vendor_id' => $request->user()->vendor_id,
                'orders.payment_confirmed' => 1])->when($locationID, function ($query, $locationID) {
                $query->where('orders.location_id', $locationID);
            })->distinct()->count('orders.id'),

            'unpaid' => Order::query()->join('carts', 'orders.cart_uuid', '=',
                'carts.cart_uuid', 'INNER')->where(['carts.vendor_id' => $request->user()->vendor_id,
                'orders.payment_confirmed' => 0])->when($locationID, function ($query, $locationID) {
                $query->where('orders.location_id', $locationID);
            })->distinct()->count('orders.id')

        ];

        $locations = $locationID ? Location::where('id', $locationID)->get() : Location::all();

        return view('drugs-order', compact('orders', 'size', 'total', 'search', 'payment', 'dateStart', 'dateEnd', 'locations', 'location'));
    }

    public function drugOrderItems(Request $request)
    {
        if (empty($request->uuid)) {
            return redirect('/drugs-order')->with('error', "Cart ID is missing.");
        }

        $orderItems = Cart::with(['drug', 'order'])->where(['cart_uuid' => $request->uuid,
            'vendor_id' => $request->user()->vendor_id])->orderByDesc('id');

        if (empty($orderItems->first())) {
            return redirect('/drugs-order')->with('error', "Sorry, that Cart ID either does not exist or has been deleted");
        }

        if ($request->user()->admin_type == 'agent' && $orderItems->first()->order->location_id != $request->user()->location_id) {
            return redirect('/drugs-order')->with('warning', "Sorry, that order is not for your assigned location");
        }

        $size = empty($request->size) ? 10 : $request->size;

        $orderItems = $orderItems->paginate($size);

        return view('drug-order-items', compact('orderItems', 'size'));
    }

    public function drugOrderItemAction(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:carts,id',
            'status' => 'required|string|in:approved,disapproved,cancelled',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $item = Cart::where(['id' => $request->id, 'vendor_id' => $request->user()->vendor_id])->first();

        if (empty($item)) {
            return response([
                'status' => false,
                'message' => 'Sorry, that item was not found'
            ]);
        }

        $item->status = $request->status;
        $item->save();

        return response([
            'status' => true,
            'message' => "That order item has been {$request->status} successfully"
        ]);
    }

    public function addPrescription(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'uuid' => 'required|string',
            'id' => 'required|integer',
            'file' => 'required|image|mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $item = Cart::where(['cart_uuid' => $request->uuid, 'drug_id' => $request->id, 'vendor_id' => $request->user()->vendor_id])->first();

        if (empty($item)) {

            return response([
                'status' => false,
                'message' => "Failed to add prescription, item not found"
            ]);
        }

        if ($request->hasFile('file')) {

            $item->prescription = $prescription = $this->uploadFile($request, 'file');
//            $item->prescription = $prescription = 'http://nelloadmin.com/images/drug-placeholder.png';
            $item->prescribed_by = 'vendor';
            $item->save();

            return response([
                'status' => true,
                'message' => "Prescription uploaded and added successfully",
                'prescription' => $prescription
            ]);

        } else return response([
            'status' => false,
            'message' => "No prescription file uploaded"
        ]);
    }
}
