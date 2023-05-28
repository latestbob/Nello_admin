@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Nello For Business Interest list</a></li>
                        
                    </ol>
                </div>
                <h4 class="page-title">Interest List</h4>
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
                                
                                <th>Type</th>
                                <th>Business Name</th>
                                <th>Fullname</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Intested In</th>
                                <th>Others</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>

                              
                                
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($interest as $int)

                                <tr>
                                    <td>{{$int->business_type}}</td>
                                    <td>{{$int->business_name}}</td>
                                    <td>{{$int->fullname}}</td>
                                    <td>{{$int->email}}</td>
                                    <td>{{$int->phone}}</td>
                                    <td>{{$int->interest}}</td>
                                    <td>{{$int->others}}</td>
                                    <td>{{$int->status}}</td>
                                    <td>{{$int->created_at->diffForHumans()}}</td>
                                    <td>Action</td>
                                

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
