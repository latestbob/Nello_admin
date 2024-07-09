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
                <h4 class="page-title">Manage time on {{$schedule->date}}  for {{ $schedule->specialization}}</h4>
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
                <div class="text-right">
                    <input type="checkbox" name="allcheckbox" id="allcheckbox"><b> Check All</b>
                </div>


                   
                <form action="{{route('healthcenterspecdatettimepost')}}"method="POST" class="col-md-6 m-auto">
                    @csrf 

                    <input type="hidden"name="date"value="{{$schedule->date}}">
                    
                    <input type="hidden"name="specialization"value="{{$schedule->specialization}}">

                    <input type="hidden"name="center_uuid"value="{{$schedule->center_uuid}}">

        
     



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


                    <div class="row mt-4">

                 

                        <div class="col-md-4">
                            <label for="">5:30 pm</label>
                            <input type="checkbox"name="time[]"value="5:30 pm">
                        </div>

                        <div class="col-md-4">
                            <label for="">6:00 pm</label>
                            <input type="checkbox"name="time[]"value="6:00 pm">
                        </div>

                        <div class="col-md-4">
                            <label for="">6:30 pm</label>
                            <input type="checkbox"name="time[]"value="6:30 pm">
                        </div>
                    </div>

                    <div class="row mt-4">

                 

                    <div class="col-md-4">
                        <label for="">7:00 pm</label>
                        <input type="checkbox"name="time[]"value="7:00 pm">
                    </div>

                    <div class="col-md-4">
                        <label for="">7:30 pm</label>
                        <input type="checkbox"name="time[]"value="7:30 pm">
                    </div>

                    <div class="col-md-4">
                        <label for="">8:00 pm</label>
                        <input type="checkbox"name="time[]"value="8:00 pm">
                    </div>
                    </div>

                    <br>
                    <br>

                  
                    <button type="submit" class="btn btn-success  text-center text-light">Add Time</button>
                    



                </form>



  
                <div class="table-responsive col-md-6 m-auto">
                    <h4 class="text-center py-3">Lists of available time for {{$schedule->date}}</h4>
                    <table class="table table-striped mt-4">
                        <thead>
                            <th>Date</th>
                            <th>Day of the Month</th>
                            <th>Spec</th>
                            <th>Time</th>
                            
                            <th>Action</th>


                        </thead>

                        <tbody>

                        @foreach($available as $time)

                            <tr>
                                <td>{{$time->date}}</td>
                                <td>{{$time->date_word}}</td>
                                <td>{{$time->specialization}}</td>
                                <td>{{$time->time}}</td>

                                <td>
                                    <form action="{{route('healthcenterspecdatetimedelete',$time->id)}}"method="POST">
                                        @csrf
                                        {{method_field('DELETE')}}

                                        <button type="submit"class="btn text-danger"style="">Remove</button>
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

   

  
    
  








    <script>
  // Get the "checkall" checkbox element
  const checkAllCheckbox = document.getElementById("allcheckbox");
  
  // Get all the time checkboxes
  const timeCheckboxes = document.querySelectorAll('input[name="time[]"]');
  
  // Add event listener to the "checkall" checkbox
  checkAllCheckbox.addEventListener("change", function() {
    const isChecked = checkAllCheckbox.checked;
    
    // Set the checked property of all time checkboxes based on the "checkall" checkbox
    timeCheckboxes.forEach(function(checkbox) {
      checkbox.checked = isChecked;
    });
  });
</script>

@endsection








