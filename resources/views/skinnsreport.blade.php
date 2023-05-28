@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Skinns Inventory Report</li>
                    </ol>
                </div>
                <h4 class="page-title">Skinns Product Inventory Report</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-12 mt-2">
                            <div class="row">
                                <div class="col-md-4">

                                    <h4 class="header-title">Skinns Inventory Report - Total {{$countall}}</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of Skinns product inventory Report
                                    </p>

                                </div>
                                <div class="col-md-8">

                                 

                                </div>
                            </div>
                        </div>
                    </div>

                   

                    <div class="table-responsive">

                        <table class="table dataTable w-100">
                            <thead>
                            <tr>
                               
                                <th>Prouduct Name</th>
                                <th>Unit Price(N)</th>
                                <th>Brand</th>
                                <th>Opening Quantity</th>
                                <th>Purchased Quantity</th>
                                <th>Closing Quantity</th>
                                <th>Month</th>

                                <th>Status</th>
                                <th>Action</th>
                               
                                <!-- <th>Action</th> -->
                            </tr>
                            </thead>


                            <tbody>


                                @foreach($skinnsproduct as $product)


                                <tr>
                                    <td>{{$product->name}}</td>
                                    <td>{{$product->price}}</td>
                                    <td>{{$product->brand}}</td>
                                    <td>{{$product->quantity - \DB::table("sales_reports")->where("product_name",$product->name)->where("month",$currentMonth)->sum("purchased_quantity")}}</td>
                               
                                    <td>{{ \DB::table("sales_reports")->where("product_name",$product->name)->where("month",$currentMonth)->sum("purchased_quantity")}}</td>
                                    <td>{{$product->quantity}}</td>
                                    <td>{{$currentMonth}}</td>
                                    <td>
                                        @if($product->quantity == 0)
                                            
                                            <p class="badge badge-danger text">Out of Stock</p>

                                        @elseif($product->quantity < 5)

                                        <p class="badge badge-warning text">Low Stock</p>

                                        @elseif($product->quantity >= 5)

                                        <p class="badge badge-success text">High Stock</p>


                                        @endif
                                    </td>
                                    <td></td>

                                </tr>


                                @endforeach

                         

                            

                            </tbody>
                        </table>

                    </div>

                   

                    

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->

@endsection



@section('css')
    <!-- Datatables css -->
    <link href="{{ asset('css/vendor/select.bootstrap4.css') }}" rel="stylesheet" type="text/css"/>
@endsection
