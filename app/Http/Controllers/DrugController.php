<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\PharmacyDrug;
use App\Traits\FileUpload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DrugController extends Controller
{

    use FileUpload;

    public function drugs(Request $request) {

        $size = empty($request->size) ? 10 : $request->size;
        $search = $request->search;

        $drugs = PharmacyDrug::when($search, function ($query, $search) {

            $query->whereRaw(
                "(name like ? or brand like ? or category like ? or uuid = ?)",
                [
                    "%{$search}%", "%{$search}%", "%{$search}%", $search
                ]
            );

        })->where('vendor_id', $request->user()->vendor_id)->orderBy('name')->paginate($size);

        return view('drugs', compact('drugs', 'search', 'size'));
    }

    public function drugView(Request $request) {

        if (empty($uuid = $request->uuid)) {
            return redirect('/drugs')->with('error', "Drug ID missing");
        }

        $drug = PharmacyDrug::where(['uuid' => $request->uuid, 'vendor_id' => $request->user()->vendor_id])->first();

        if (empty($drug)) {
            return redirect('/drugs')->with('error', "Sorry, the ID '{$request->uuid}' is associated with any drug");
        }

        if (strtolower($request->method()) == "post") {

            Validator::make($data = $request->all(), [
                'name' => 'required|string|max:50',
                'brand'  => 'required|string|max:50',
                'category' => 'required|string|max:50',
                'price' => 'required|numeric',
                'image' => 'nullable|image|mimes:jpeg,jpg,png'
            ])->validate();

            if ($request->hasFile('image')) {

                $data['image'] = $this->uploadFile($request, 'image');
//            $data['image'] = 'http://www.famacare.com/img/famacare.png';

            }

            $data['require_prescription'] = $request->has('prescription') ? 1 : 0;

            $drug->update($data);

            session()->put('success', "Drug has been updated successfully");

        }

        return view('drug-view', compact('drug', 'uuid'));
    }

    public function drugAdd(Request $request) {

        if (strtolower($request->method()) == "post") {

            Validator::make($data = $request->all(), [
                'name' => 'required|string|max:50',
                'brand'  => 'required|string|max:50',
                'category' => 'required|string|max:50',
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

            $drug = PharmacyDrug::create($data);

            return redirect("drug/{$drug->uuid}/view")->with('success', "Drug has been added successfully");

        }

        return view('drug-add');
    }

    public function drugDelete(Request $request) {

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

        $orders = Order::query()->join('carts', 'orders.cart_uuid', '=', 'carts.cart_uuid', 'INNER');

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

        $orders = $orders->where('carts.vendor_id', $request->user()->vendor_id);

        $orders = $orders->groupBy('carts.cart_uuid')->orderByDesc('orders.id');

        $orders = $orders->paginate($size);

        $total = [

            'paid' => Order::query()->join('carts', 'orders.cart_uuid', '=',
                'carts.cart_uuid', 'INNER')->where(['carts.vendor_id' => $request->user()->vendor_id,
                'orders.payment_confirmed' => 1])->distinct()->count('orders.id'),

            'unpaid' => Order::query()->join('carts', 'orders.cart_uuid', '=',
                'carts.cart_uuid', 'INNER')->where(['carts.vendor_id' => $request->user()->vendor_id,
                'orders.payment_confirmed' => 0])->distinct()->count('orders.id')

        ];

        return view('drugs-order', compact('orders', 'size', 'total', 'search', 'payment', 'dateStart', 'dateEnd'));
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

        $size = empty($request->size) ? 10 : $request->size;

        $orderItems = $orderItems->paginate($size);

        return view('drug-order-items', compact('orderItems', 'size'));
    }

    public function drugOrderItemAction(Request $request) {

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

    public function addPrescription(Request $request) {

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
