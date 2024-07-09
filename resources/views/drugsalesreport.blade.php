@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Drug Sales Report</li>
                    </ol>
                </div>
                <h4 class="page-title">Drug Sales Report</h4>
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

                                    <h4 class="header-title">Drugs Report</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of drugs ordered by Nello users
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
                                
                                <th>Name</th>
                                <th>Prouduct Name</th>
                                <th>Unit Price(N)</th>
                                <th>Vendor</th>
                                <th>Initial quantity</th>
                                <th>Purchased quantity</th>
                                <th>Total Amount</th>
                               
                                <th>Cart_Uuid</th>
                                
                                <th>Date Purchased</th>
                                <th>Status</th>
                               
                                <!-- <th>Action</th> -->
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($drugsalesreport as $drug)

                         
       

                            <tr>
                                <td>{{$drug->customer}}</td>
                                <td>{{$drug->product_name}}</td>
                                <td>{{$drug->unit_price}}</td>
                                <td>{{$drug->vendor}}</td>
                                <td>{{$drug->initial_quantity}}</td>
                                <td>{{$drug->purchased_quantity}}</td>
                                <td>{{$drug->total_amount}}</td>
                                <td>{{$drug->cart_uuid}}</td>
                                
                                <td>{{$drug->created_at}}</td>
                                <td>

                                    @if($drug->status == "approved")

                                    <p class="badge badge-success text-light">{{$drug->status}}</p>

                                    @elseif($drug->status == "disapproved")
                                    <p class="badge badge-warning text-dark">{{$drug->status}}</p>
                                    @elseif($drug->status == "cancelled")
                                    <p class="badge badge-danger text-light">{{$drug->status}}</p>

                                    @elseif($drug->status == "refunded")
                                    <p class="badge badge-info text-light">{{$drug->status}}</p>



                                    @endif
                                </td>
                                <!-- <td>
                                    <form action="{{route('deletesalesreport',$drug->id)}}"method="POST">
                                        @csrf 
                                       {{method_field('DELETE')}}

                                       <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td> -->


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
