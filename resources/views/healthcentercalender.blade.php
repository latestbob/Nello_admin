@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Health Center</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
                <h4 class="page-title">{{$center->name}} Specialization and Calendar</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
  
    <div class="row card py-3">
        <div class="col-md-6 col-lg-6 m-auto">

            <div class="card">
     


            <h4 class="text-center">Available Specializations</h4>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>

                        @foreach($spec as $special)

                                <tr>
                                    <td>{{$special->specialization}}</td>
                                    <td>

                                    


                                        <form action="{{route('health-center-delete-spec',$special->id)}}"method="POST">

                                            @csrf 
                                            {{method_field('DELETE')}}
                                            <a href="{{route('healthcenterspecalender',$special->id)}}"class="btn btn-info text-light">Manage Calendar </a> ||

                                            <button class="btn btn-danger text-light"style="font-weight:bold;">Remove</button>
                                        </form>
                                    </td>
                                </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>


        </div> <!-- end col-->



      
    </div>

@endsection





