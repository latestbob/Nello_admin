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
                <h4 class="page-title">Switch Appointment {{$appointment->ref_no}}</h4>
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
                                <td>Specialist</td>
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

                      <form class="form"action="{{route('switchappointmentput')}}"method="POST">
                          @csrf
                          {{method_field("PUT")}}

                         
                          <label for="">Choose Specialist below</label>

                          <select name="email" class="form-control"required>
                              <option value="">Select from list of {{\App\Models\User::where('id',$appointment->doctor_id)->value('aos')}}</option>

                              @foreach($specialist as $person)

                              <option value="{{$person->email}}">{{$person->title}}. {{$person->firstname}} {{$person->email}}</option>



                              @endforeach


                        </select>

                        <input type="hidden"name="ref_no"value="{{$appointment->ref_no}}"required>



                         <br>
                         
                         <button type="submit" class="btn btn-info text-center text-light">Switch Specialist</button>
                


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
