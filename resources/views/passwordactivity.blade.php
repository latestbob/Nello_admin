@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Famacare Customers Password Activities</a></li>
                        
                    </ol>
                </div>
                <h4 class="page-title">Password Activities</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    
        
    

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                   

                    <div class="table-responsive">

                        <table class="table dataTable w-100">
                            <thead>
                            <tr>
                                
                                <th>UPI</th>
                                <th>User Email</th>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Status</th>
                                <th>Activity Date</th>
                              
                                
                            </tr>
                            </thead>


                            <tbody>


                            @foreach($activity as $active)

                            <tr>
                                <td>{{$active->upi}}</td>
                                <td>{{$active->email}}</td>
                                <td>{{$active->firstname}}</td>
                                <td>{{$active->lastname}}</td>
                                <td><p class="badge badge-success rounded">Changed</p></td>
                                <td>{{$active->created_at->diffForHumans()}}</td>
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

@section('js')
    <script src="{{ asset('js/net-bridge/net-bridge.js') }}" type="application/javascript"></script>

    
@endsection

@section('css')
    <!-- Datatables css -->
    <link href="{{ asset('css/vendor/select.bootstrap4.css') }}" rel="stylesheet" type="text/css"/>
@endsection
