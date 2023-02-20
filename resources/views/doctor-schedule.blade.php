@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Doctor</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
                <h4 class="page-title">{{$doctor->title}}. {{$doctor->firstname}} {{$doctor->lastname}} Appointment Schedule</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
  
    <div class="row card py-3">
       



        <div class="col-md-8 col-lg-8 m-auto">
            <div class="card-heading text-center bg-secondary py-2 font-weight-bold my-3"style="color:white;">Create Calendar Schedule for {{$doctor->title}}. {{$doctor->firstname}}</div>

                <form class="form-inline"action="{{route('doctor-schedule-add',$doctor->uuid)}}"method="POST">
                    @csrf
                    <div class="form-group mb-2">
                        <select name="day"class="form-control" id=""required>
                            <option value="">Day of the Week</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                        <select name="specialization"class="form-control" id=""required>
                            <option value="">Register Specialization</option>

                            <option value="{{$doctor->aos}}">{{$doctor->aos}}</option>
                        </select>
                    </div>


                    <div class="form-group mx-sm-3 mb-2">
                        <select name="time"class="form-control" id=""required>
                            <option value="">Time Available</option>
                            
                            
                            <option value="8:00:00">8:00 am</option>
                            <option value="9:00:00">9:00 am</option>
                            <option value="10:00:00">10:00 am</option>
                            <option value="11:00:00">11:00 am</option>
                            <option value="12:00:00">12:00 pm</option>
                            <option value="13:00:00">1:00 pm</option>
                            <option value="14:00:00">2:00 pm</option>
                            <option value="15:00:00">3:00 pm</option>
                            <option value="16:00:00">4:00 pm</option>
                            <option value="17:00:00">5:00 pm</option>
                            <option value="18:00:00">6:00 pm</option>
                            <option value="19:00:00">7:00 pm</option>
                            <option value="20:00:00">8:00 pm</option>
                        </select>
                    </div>


                    <button type="submit" class="btn btn-success mb-2">Add Schedule</button>
                 </form>
            

                 <hr>
                 <br>

                <h4 class="text-center py-2">Registered Schedule for {{$doctor->title}}. {{$doctor->firstname}} {{$doctor->lastname}}</h4>


                @if($schedule->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead class="bg-secondary"style="color:white;">
                            <tr>
                                <th>Specialization</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                      

                            @foreach($schedule as $calendar)

                            <tr>
                                <td>{{$calendar->specialization}}</td>
                                <td>{{$calendar->day}}</td>
                                <td>{{$calendar->time}}</td>
                                <td>
                                    <form action="{{route('doctor-schedule-delete',$calendar->id)}}"method="POST">
                                        @csrf 
                                    
                                        {{method_field('DELETE')}}

                                        <button class="btn"style="background:none;color:red;font-weight:bold;">Remove</button>
                                
                                    </form>
                                </td>
                            </tr>

                            @endforeach
                            
                            @else <div class="text-center py-2">
                                <h5 class="text-center">Not Available</h5>
                            </div>

                           @endif
                        </tbody>
                    </table>
                </div>


            

        </div> <!-- end col -->
    </div>

@endsection





