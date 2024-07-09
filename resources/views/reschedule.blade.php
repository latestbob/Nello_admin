@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Appointment - </li>
                    </ol>
                </div>
                <h4 class="page-title">Appointment Reschedule {{$appointment->ref_no}}</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    @if(session('msg'))

    <div class="alert alert-success text">
        <p>{{session('msg')}}</p>
    </div>


    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">



              <div class="row">
                  <div class="col-md-5">

                  <h4 class="text-center">Previous Details</h4>


                <div class="table responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>Ref No</td>
                                <td>{{$appointment->ref_no}}</td>
                            </tr>

                           

                            <tr>
                                <td>Center/Doctor</td>
                                <td>
                                @if($appointment->center)
                                        {{ $appointment->center->name }}
                                        @endif
                                        @if($appointment->doctor)
                                        {{$appointment->doctor->title}}. {{ $appointment->doctor->firstname }} {{ $appointment->doctor->lastname }}
                                        @endif
                                </td>
                            </tr>

                            <tr>
                                <td>Date</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->date)->format('F dS, Y') }}</td>
                            </tr>

                            <tr>
                                <td>Time</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->time)->format('h:ia') }}</td>
                            </tr>



                        </tbody>
                    </table>
                </div>

           


                  </div>

                  <div class="col-md-7">

                      <form class="form"action="{{route('rescheduleadmin')}}"method="POST">
                          @csrf
                          {{method_field("PUT")}}

                          <input type="hidden"name="ref_no"value="{{$appointment->ref_no}}">

                          @if($appointment->center)
                          <input type="hidden"name="type"value="center">
                                        @endif
                                        @if($appointment->doctor)
                                        <input type="hidden"name="type"value="doctor">
                                        @endif

                         <div class="form-group">
                             <label for="">New Date</label>
                             
                             <input type="date"name="date"id="inputdate" class="form-control"required>
                        
                
                         </div>

                         <div class="form-group">
                         <label for="">New Time</label>
                             
                            <select name="time" id="" class="form-control"required>
                                <option value="">Select time</option>
                                <option value="9:00:00">9:00 am</option>
                                <option value="9:30:00">9:30 am</option>
                                <option value="10:00:00">10:00 am</option>
                                <option value="10:30:00">10:00 am</option>
                                <option value="11:00:00">11:00 am</option>
                                <option value="11:30:00">11:30 am</option>
                                <option value="12:00:00">12:00 pm</option>
                                <option value="12:30:00">12:30 pm</option>
                                <option value="13:00:00">1:00 pm</option>
                                <option value="13:30:00">1:30 pm</option>
                                <option value="14:00:00">2:00 pm</option>
                                <option value="14:30:00">2:30 pm</option>
                                <option value="15:00:00">3:00 pm</option>
                                <option value="15:30:00">3:30 pm</option>
                                <option value="16:00:00">4:00 pm</option>
                                <option value="16:30:00">4:30 pm</option>
                                <option value="17:00:00">5:00 pm</option>
                                <option value="17:30:00">5:30 pm</option>
                                <option value="18:00:00">6:00 pm</option>
                                <option value="18:30:00">6:30 pm</option>
                                <option value="19:00:00">7:00 pm</option>



                            </select>
                             
                         </div>


                         <br>
                         
                         <button type="submit" class="btn btn-info text-center text-light">Reschedule Appointment</button>
                


                </form>

                  </div>
              </div>



                    




                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>

@endsection




@section('css')
    <!-- Datatables css -->
    <link href="{{ asset('css/vendor/select.bootstrap4.css') }}" rel="stylesheet" type="text/css"/>
@endsection
