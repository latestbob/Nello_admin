<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DoctorsPrescription;
use App\Models\DrugCategory;
use App\Models\Location;
use App\Models\Order;
use App\Models\PharmacyDrug;
use App\Traits\FileUpload;
use App\Traits\FirebaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use DB;
use Carbon\Carbon;


class DrugController extends Controller
{

    use FileUpload, FirebaseNotification;

    public function drugs(Request $request)
    {

        $size = empty($request->size) ? 10 : $request->size;
        $category = $request->category;
        $search = $request->search;


        if($search == "Unavailable"){
            $drugs = PharmacyDrug::where("status",false)->paginate($size);
        }

        elseif($search == "Available"){
            $drugs = PharmacyDrug::where("status",true)->paginate($size);
        }

        elseif($search == "Prescription"){
            $drugs = PharmacyDrug::where("require_prescription",true)->paginate($size);
        }

        elseif($search == "Not required"){
            $drugs = PharmacyDrug::where("require_prescription",false)->paginate($size);
        }

        else {

            $drugs = PharmacyDrug::when($search, function ($query, $search) {

                $query->whereRaw(
                    "(name like ? or brand like ? or id like ? or dosage_type like ? )",
                    [
                        "%{$search}%", "%{$search}%", $search, "%{$search}%"
                    ]
                );
    
            })->when($category, function ($query, $category) {
    
                $query->where('category_id', $category);
    
            })->where('vendor_id', $request->user()->vendor_id)->orderBy('name')->paginate($size);
        }

        

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
                'description' => 'required|string',
                'dosage_type' => 'required|string|max:50',
                'price' => 'required|numeric',
                'quantity' => 'required|numeric',
                'image' => 'nullable|image|mimes:jpeg,jpg,png',
                'indications' => 'required|string',
                'side_effects' => 'required|string',
                'contraindications' => 'required|string'
            ])->validate();

            if ($request->hasFile('image')) {

                $data['image'] = $this->uploadFile($request, 'image');
            //            $data['image'] = 'http://www.famacare.com/img/famacare.png';

            }

            $data['require_prescription'] = $request->has('prescription') ? 1 : 0;

            $data['category_id'] = $data['category'];
            unset($data['category']);

            if($request->vendor){
                $data['vendor'] = $request->vendor;
            }

            $drug->update($data);

            return redirect(route('drugs'))->with('success', "Drug has been updated successfully");

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
                'description' => 'required|string',
                'dosage_type' => 'required|string|max:50',
                'price' => 'required|numeric',
                'quantity' => 'required|numeric',
                'image' => 'nullable|image|mimes:jpeg,jpg,png',
                'indications' => 'required|string',
                'side_effects' => 'required|string',
                'contraindications' => 'required|string',
                
            ])->validate();

            if ($request->hasFile('image')) {

                $data['image'] = $this->uploadFile($request, 'image');
                //$data['image'] = 'http://www.famacare.com/img/famacare.png';
            }

            $data['require_prescription'] = $request->has('prescription') ? 1 : 0;

            $drugId = "AN" .  random_int(100000, 999999);
            $data['uuid'] = Str::uuid()->toString();
            $data['drug_id'] = $drugId;

            $data['vendor_id'] = $request->user()->vendor_id;

            $data['category_id'] = $data['category'];
            unset($data['category']);

            if($request->vendor){
                $data['vendor'] = $request->vendor;
            }

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

        //$drug = PharmacyDrug::where(['uuid' => $request->uuid, 'vendor_id' => $request->user()->vendor_id])->first();
        $drug = PharmacyDrug::where(['uuid' => $request->uuid])->first();

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

    public function drugStatus(Request $request)
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

        $drug->update(['status' => !$drug->status]);

        return response([
            'status' => true,
            'message' => "This drug is now " . ($drug->status == true ? 'available' : 'unavailable')
        ]);

    }

    public function drugOrders(Request $request)
    {

        $size = empty($request->size) ? 10 : $request->size;

        $locationID = null;
        $userType = '';

        if (Auth::check() && ($userType = Auth::user()->user_type) == "agent") {
            $locationID = Auth::user()->pharmacy->location_id;
        }

        $orders = Order::query()->join('carts', 'orders.cart_uuid', '=', 'carts.cart_uuid', 'INNER');

        $orders->when($locationID, function ($query, $locationID) {
            $query->where(['orders.location_id' => $locationID, 'orders.payment_confirmed' => 1]);
        });

        if (!empty($search = $request->search)) {


            if($search == "Delivered"){
                $orders = $orders->where('delivery_status',true);
            }
            elseif($search == "Not Delivered"){
                $orders = $orders->where('delivery_status',false);
            }
            else {

                $orders = $orders->whereRaw(
                    "(orders.firstname like ? or orders.lastname like ? or
                    orders.phone like ? or orders.email like ? or
                    orders.company like ? or orders.city = ? or
                    orders.order_ref = ? or orders.cart_uuid = ? or orders.address1 like ? or orders.delivery_method like ?  or orders.delivery_status = ?)",
                    [
                        "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%",
                        "%{$search}%", $search, $search, $search, "%{$search}%", "%{$search}%", $search
                    ]
                );
            }
          
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

        $orders = $orders->where('carts.vendor_id', $request->user()->vendor_id); //->select(['*', 'orders.id as id']);

        $orders = $orders->groupBy('carts.cart_uuid')->orderByDesc('orders.id');

        $orders = $orders->paginate($size);

        $total = [

            'paid' => ($userType == 'admin' || $userType == 'agent') ? Order::query()->join('carts', 'orders.cart_uuid', '=',
                'carts.cart_uuid', 'INNER')->where(['carts.vendor_id' => $request->user()->vendor_id,
                'orders.payment_confirmed' => 1])->when($locationID, function ($query, $locationID) {
                $query->where('orders.location_id', $locationID);
            })->distinct()->count('orders.id') : null,

            'unpaid' => ($userType == 'admin' || $userType == 'agent') ? Order::query()->join('carts', 'orders.cart_uuid', '=',
                'carts.cart_uuid', 'INNER')->where(['carts.vendor_id' => $request->user()->vendor_id,
                'orders.payment_confirmed' => 0])->when($locationID, function ($query, $locationID) {
                $query->where('orders.location_id', $locationID);
            })->distinct()->count('orders.id') : null

        ];

        $locations = $locationID ? Location::where('id', $locationID)->get() : Location::all();

        return view('drugs-order', compact('orders', 'size', 'total', 'search', 'payment', 'dateStart', 'dateEnd', 'locations', 'location', 'userType'));
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

        if (($userType = $request->user()->user_type) == 'agent') {

            if ($orderItems->first()->order->location_id != $request->user()->pharmacy->location_id) {
                return redirect('/drugs-order')->with('warning', "Sorry, that order is not for your pharmacy's assigned location");
            }
            $orderItems = $orderItems->where('status', 'approved');
        }

        $size = empty($request->size) ? 10 : $request->size;

        $orderItems = $orderItems->paginate($size);

        return view('drug-order-items', compact('orderItems', 'size', 'userType'));
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

        $item = Cart::where(['id' => $request->id, 'vendor_id' => $request->user()->vendor_id])
            ->with('drug')->first();

        if (empty($item)) {
            return response([
                'status' => false,
                'message' => 'Sorry, that item was not found'
            ]);
        }

        if ($request->status == 'approved' && $item->drug->require_prescription && !$item->has_prescription) {
            return response([
                'status' => false,
                'message' => "Sorry, you can't approve an item with no prescription"
            ]);
        }

        $salesreport = DB::table("sales_reports")->where("cart_uuid",$item->cart_uuid)->where("product_name",$item->drug->name)->update([
            'status' => $request->status
        ]);
//i dey

//$user = User::wjere

        if ($request->status == 'approved') {
            
            DB::table('sales_reports')->insert([
                'customer' =>"Zainab",
                'product_name' => $item->drug->name,
                'unit_price' => $item->drug->price,
                'vendor' => $item->drug->vendor,
                'initial_quantity' => $item->drug->quantity + $item->quantity,
                'purchased_quantity' => $item->quantity,
                'total_amount' => $item->price,
                'cart_uuid' => $item->cart_uuid,
                'month' => 'March',
                'status' => 'approved',
                'created_at' => $item->created_at,
            ]);
        
            
        }
       

        $item->status = $request->status;
        $item->save();







        $isAllApproved = true; $items = [];

        $drugIds = [];

        foreach ($item->order->items as $it) {
            if ($it->status != 'approved') {
                $isAllApproved = false;
                break;
            }

            $items[] = [
                'id' => $it->id,
                'name' => $it->drug->name,
                'brand' => $it->drug->brand,
                'image' => $it->drug->image,
                'quantity' => $it->quantity,
                'price' => $it->price
            ];
        }

        if ($isAllApproved) {


            $agents = $item->order->location->agents()
                ->whereNotNull('device_token')
                ->pluck('device_token')->toArray();
            // print_r($agents);
            // $agents = [];
            // foreach (($item->order->location->pharmacies ?? []) as $pharmacy) {
            //     foreach ($pharmacy->agents as $agent) $agents[] = $agent->device_token;
            // }

            if (!empty($agents)) {

                $this->sendNotification($agents, "New Order",
                    "Hello there! there's been a newly approved order for your location with Order REF: {$item->order->order_ref}",
                    'high', ['cart_uuid' => $item->order->cart_uuid, 'items' => $items]);
            }
        }

        return response([
            'status' => true,
            'message' => "That order item has been {$request->status} successfully"
        ]);
    }

    public function drugOrderItemReady(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:carts,id',
            'is_ready' => 'required|boolean',
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

        if (!$item->has_prescription) {
            return response([
                'status' => false,
                'message' => "Sorry, you can't mark ready an item without a prescription"
            ]);
        }

        if ($item->is_ready == true) {
            return response([
                'status' => false,
                'message' => 'Sorry, that item has already been marked as ready by a pharmacy'
            ]);
        }

        $item->is_ready = $request->is_ready;
        $item->is_ready_by = $request->user()->pharmacy_id;
        $item->save();

        $isAllReady = true;

        $items = []; $pickup_addresses = [];

        foreach ($item->order->items as $it) {
            if ($it->is_ready != true) {
                $isAllReady = false;
                break;
            }

            $items[$item->is_ready_by][] = [
                'name' => $item->drug->name,
                'quantity' => $item->quantity
            ];

            $pickup_addresses[$item->is_ready_by] = [
                'name' => $item->accepted_by->name,
                'address' => $item->accepted_by->address
            ];
        }

        if ($isAllReady) {

            if ($item->order->delivery_method == 'shipping') {

                $riders = [];
                foreach ($item->order->location->riders as $rider) {
                    $riders[] = $rider->device_token;
                }

                if (!empty($riders)) {

                    $this->sendNotification($riders,
                        "New Order",
                        "Hello there! an order has been processed and is ready for pick up",
                        'high',
                        [
                            'orderId' => $item->order->id,
                            'items' => $items,
                            'customer_name' => "{$item->order->firstname} {$item->order->lastname}",
                            'delivery_address' => $item->address1,
                            'pickup_address' => $pickup_addresses
                        ]
                    );
                }
            }

        }

        return response([
            'status' => true,
            'message' => "That order has been successfully marked as ready"
        ]);
    }

    public function drugOrderPickedUp(Request $request,$ref)
    {
        $deliver = [];
        $order = Order::where("order_ref", $ref)->first();

     

    $deliver = [
        'firstname' => $order->firstname,
        'ref' => $ref,
        'id' => $order->id,
        
        
    ];
    $email = $order->email;

    // 



       $responsed = Http::post('https://mw.asknello.com/api/confirmme',[
        "deliver" => $deliver,
        "email" => $email
        
    ]);

    

        if ($order->delivery_status == true) {
            
            return back()->with("error", "Sorry, order has already been marked as delivered");
        }

        $order->update([
            'delivery_status' => true,
            'delivered_by' => Auth::user()->id
        ]);

        return back()->with("msg",'Ordered was updated to delivered');
    }

    public function addPrescription(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'uuid' => 'required|exists:carts,cart_uuid',
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

    public function addDoctorsPrescription(Request $request)
    {
        if ($request->user()->user_type != 'doctor') {
            return response([
                'status' => false,
                'message' => "Sorry only doctors can add this prescription"
            ]);
        }

        $validator = Validator::make($request->all(), [
            'uuid' => 'required|uuid|exists:carts,cart_uuid|unique:doctors_prescriptions,cart_uuid',
            'id' => 'required|integer',
            'dosage' => 'required|string',
            'note' => 'required|string',
        ], [
            'uuid.exists' => "Sorry, that item seems not to exist.",
            'uuid.unique' => "Sorry, you've already added a prescription for that item."
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $data = $validator->validated();
        $data['cart_uuid'] = $data['uuid'];
        $data['uuid'] = Str::uuid()->toString();
        $data['drug_id'] = $data['id'];
        $data['doctor_id'] = $request->user()->id;
        $data['vendor_id'] = $request->user()->vendor_id;

        DoctorsPrescription::create($data);

        $cart = Cart::where(['cart_uuid' => $data['cart_uuid'], 'drug_id' => $data['drug_id']])->first();
        $cart->update([
            'prescription' => route('doctors-prescription', ['uuid' => $data['cart_uuid']])
        ]);

        return response([
            'status' => true,
            'message' => "Prescription added successfully"
        ]);

        

    }

    public function drugImport(Request $request) {
        return view('drug-import');
    }

    public function drugCategories(Request $request)
    {

        $size = empty($request->size) ? 10 : $request->size;
        $search = $request->search;
        $categories = DrugCategory::withCount(['drugs'])
            
            ->where( function($query) use ($search){
                $query->where('name', 'LIKE', '%'.$search.'%')


                 ->having('drugs_count', 'LIKE', $search)
                ;
                
                     
            })->orderBy('name')->paginate($size);

        return view('drug-categories', compact('categories', 'search', 'size'));
    }

    public function drugCategoryUpdate(Request $request, $id = null)
    {
        $category = DrugCategory::find($id ?: $request->id);
        if (!$category) {
            return redirect("/drug/categories")->with('error', "Drug category not found");
        }

        if ($request->isMethod('post')) {
            $data = $request->validate([
                'name' => ['required', Rule::unique('drug_categories', 'name')->ignore($category->id)]
            ]);
            $category->update($data);

            return redirect("/drug/categories")->with('success', "Drug category has been updated successfully");
        }

        return view('drug-categories-edit', compact('category'));
    }

    public function drugCategoryAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'name' => 'required|unique:drug_categories'
            ]);
            DrugCategory::create($data);

            return redirect("/drug/categories")->with('success', "Drug category has been added successfully");
        }

        return view('drug-categories-add');
    }


    public function drugCategoryDelete(Request $request)
    {

        if (empty($request->id)) {
            return response([
                'status' => false,
                'message' => "Drug category ID missing"
            ]);
        }

        $category = DrugCategory::where(['id' => $request->id])->first();

        if (empty($category)) {
            return response([
                'status' => false,
                'message' => "Sorry, the ID '{$request->id}' is associated with any drug category"
            ]);
        }

        if ($category->drugs()->count() > 0) {
            return response([
                'status' => false,
                'message' => "Sorry, this category cannot be deleted because it has drugs attached to it."
            ]);
        }

        $category->delete();

        return response([
            'status' => true,
            'message' => "Drug category deleted successfully"
        ]);
    }



    //delivered status update

    public function delivered($id){
        $orders = Order::find($id);

        dd($orders);
    }

    //drug controller update beauty product to vendor of skinns

    // public function updatebeauty(Request $request){

       
        
    //     //$orders = Order::query()->join('carts', 'orders.cart_uuid', '=', 'carts.cart_uuid', 'INNER');

    //     // $orders->when($locationID, function ($query, $locationID) {
    //     //     $query->where(['orders.location_id' => $locationID, 'orders.payment_confirmed' => 1]);
    //     // });

    //     $orders = Order::where("payment_confirmed", 1)->first();

       
     

   


    //     return $orders;
    // }

    public function skinns(){
        $order = Order::first();

        return $order;
    }


    //drug sales report page

    public function drugsalesreport(){

        $drugsalesreport = DB::table("sales_reports")->get();

       //dd($drugsalesreport);

        
        return view("drugsalesreport",compact("drugsalesreport"));
    }

    //delete sales report

    public function deletesalesreport(Request $request,$id){
        $salesreport = DB::table("sales_reports")->where("id",$id)->delete();

        return back()->with("msg","Report Deleted Successfully");
    }

    //get cancelleed invoices

    public function cancelledinvoices(){
        $cancelled = DB::table("sales_reports")->where("status","cancelled")->get();
        return view("cancelledinvoice",compact("cancelled"));
    }

    //mark cancellled sales as refunded

    public function drefundedreport(Request $request, $id){
        $salesreport = DB::table("sales_reports")->where("id",$id)->update([
            'status' => "refunded"
        ]);

        return back()->with("msg","Report updated Successfully");

    }


    //skins report


    public function skinnsreport(){

       $skinnsproduct = PharmacyDrug::where("vendor","Skinns")->where('quantity','<',80)->get();
       $countall = PharmacyDrug::where("vendor","Skinns")->where('quantity','<',80)->sum("quantity");
      // $skinnsproduct = PharmacyDrug::where("name","PURITANS PRIDE L-GLUTATHIONE")->get();

        $currentMonth = Carbon::now()->format('F');

        //dd($skinnsproduct);
        return view("skinnsreport",compact('skinnsproduct','currentMonth','countall'));
    }


    //delete my order

    public function deletemyorder(Request $request,$ref){

        $order = Order::where("order_ref",$ref)->delete();

      return back();


    }


    /// mark as test order

    public function myordermark(Request $request,$ref){
        $myorder = Order::where("order_ref",$ref)->update([
            'live' => "text"
        ]);
       
        return back();
    }


    //nello medical report get available drugs

    public function getavailabledrugs(){
      $availabledrugs =   PharmacyDrug::where('quantity','>',0)->pluck("name");


      return $availabledrugs;
    }
}
