@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Medical Calendar</li>
                    </ol>
                </div>
                <h4 class="page-title">Manage {{$schedule->date}} for a {{$schedule->specialization}}</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    @if(session("msg"))

        <div class="alert alert-success text-center">
            <p>{{session('msg')}}</p>
        </div>

    @endif

   

  

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                   
                <form action="{{route('owcscheduletimepost')}}"method="POST" class="col-md-6 m-auto">
                    @csrf 

                    <input type="hidden"name="date"value="{{$schedule->date}}">
                    
                    <input type="hidden"name="specialization"value="{{$schedule->specialization}}">



                    <div class="row">
                        <div class="col-md-4">
                            <label for="">9:00 am</label>
                            <input type="checkbox"name="time[]"value="9:00 am">
                        </div>

                        <div class="col-md-4">
                            <label for="">9:30 am</label>
                            <input type="checkbox"name="time[]"value="9:30 am">
                        </div>

                        <div class="col-md-4">
                            <label for="">10:00 am</label>
                            <input type="checkbox"name="time[]"value="10:00 am">
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <label for="">10:30 am</label>
                            <input type="checkbox"name="time[]"value="10:30 am">
                        </div>

                        <div class="col-md-4">
                            <label for="">11:00 am</label>
                            <input type="checkbox"name="time[]"value="11:00 am">
                        </div>

                        <div class="col-md-4">
                            <label for="">11:30 am</label>
                            <input type="checkbox"name="time[]"value="11:30 am">
                        </div>

                       
                    </div>


                    <div class="row mt-4">

                    <div class="col-md-4">
                            <label for="">12:00 pm</label>
                            <input type="checkbox"name="time[]"value="12:00 pm">
                        </div>

                        <div class="col-md-4">
                            <label for="">12:30 pm</label>
                            <input type="checkbox"name="time[]"value="12:30 pm">
                        </div>

                        <div class="col-md-4">
                            <label for="">2:00 pm</label>
                            <input type="checkbox"name="time[]"value="2:00 pm">
                        </div>

                        
                    </div>

                    <div class="row mt-4">

                    <div class="col-md-4">
                            <label for="">2:30 pm</label>
                            <input type="checkbox"name="time[]"value="2:30 pm">
                        </div>
                        <div class="col-md-4">
                            <label for="">3:00 pm</label>
                            <input type="checkbox"name="time[]"value="3:00 pm">
                        </div>

                        <div class="col-md-4">
                            <label for="">3:30 pm</label>
                            <input type="checkbox"name="time[]"value="3:30 pm">
                        </div>

                       
                    </div>


                    <div class="row mt-4">

                 

                        <div class="col-md-4">
                            <label for="">4:00 pm</label>
                            <input type="checkbox"name="time[]"value="4:00 pm">
                        </div>

                        <div class="col-md-4">
                            <label for="">4:30 pm</label>
                            <input type="checkbox"name="time[]"value="4:30 pm">
                        </div>

                        <div class="col-md-4">
                            <label for="">5:00 pm</label>
                            <input type="checkbox"name="time[]"value="5:00 pm">
                        </div>
                    </div>

                    <br>
                    <br>

                  
                    <button type="submit" class="btn btn-success  text-center text-light">Block Time</button>
                    



                </form>



  
                <div class="table-responsive col-md-6 m-auto">
                    <h4 class="text-center py-3">Lists of Blocked time for {{$schedule->date}}</h4>
                    <table class="table table-striped mt-4">
                        <thead>
                            <th>Date</th>
                            <th>Day of the Month</th>
                            <th>Spec</th>
                            <th>Time</th>
                            
                            <th>Action</th>


                        </thead>

                        <tbody>

                        @foreach($blocktimes as $time)

                        <tr>
                            <td>{{$time->date}}</td>
                            <td>{{$time->date_word}}</td>
                            <td>{{$time->specialization}}</td>
                            <td>{{$time->time}}</td>

                            <td>
                                <form action="{{route('owcscheduletimedelete',$time->id)}}"method="POST">
                                    @csrf
                                    {{method_field('DELETE')}}

                                    <button type="submit"class="btn btn-success"style="">Enable</button>
                                </form>
                            </td>
                        </tr>


                        @endforeach
                        </tbody>
                    </table>
                </div>

                    
                    

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>

   

  
    
  










@endsection








