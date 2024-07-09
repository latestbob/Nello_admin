@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Calendar</li>
                    </ol>
                </div>
                <h4 class="page-title">Calendar for {{$doctor->title}}. {{$doctor->firstname}}</h4>
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

                    <div class="row">

                        <div class="col-md-6 col-lg-6 mt-2">
                            <button class="btn btn-success"data-toggle="modal" data-target="#exampleModal">Create Calendar</button>
                        </div>

                        <div class="col-md-6 col-lg-6 mt-2 text-right">
                            <button class="btn btn-danger "data-toggle="modal" data-target="#exampleModal1">Delete Calendar</button>
                        </div>
                    </div>

                    
                    

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>

   

    <div class="mt-3 px-4 ">

<div class="" id="">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

   <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                   <div class="table-responsive">
                       <table class="table table-striped">
                           <thead>
                               <tr>
                                   <th>Specialization</th>
                                   <th>Date</th>
                                   <th>Date String </th>

                                   <th>Month</th>
                                   <th>Action</th>
                               </tr>
                           </thead>


                           <tbody>
                               @foreach($calender as $dates)

                               <tr>
                                   <td>{{$dates->specialization}}</td>
                                   <td>{{$dates->date}}</td>
                                   <td>{{date('F')}}</td>
                                   <td>{{$dates->month}}</td>
                                   <td>
                                       <form action="{{route('deletespecialistcalendar',$dates->id)}}"method="POST">
                                           @csrf
                                           {{method_field('DELETE')}}

                                            <button class="btn btn-danger text-light">Remove</button>||<a href="{{route('specialistcalendertime',$dates->id)}}"class="btn btn-info text-center text-light">Manage Time</a>
                                       </form>

                                    

                                   </td>
                               </tr>

                               @endforeach
                           </tbody>

                          
                       </table>
                   </div>

                    
                    

                </div>  
           </div> 
           </div> 
    </div> 
  </div>
  
  

    </div>
    
  


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create Calendar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      <form action="{{route('specialistcalendarpost')}}"method="POST">
          @csrf 

        
          <input type="hidden"name="doc_uuid"value="{{$doctor->uuid}}">
          <input type="hidden"name="center"value="{{$doctor->hospital}}">


          <div class="form-group">
              <label for="">Specialization</label>
              <select name="specialization" id=""class="form-control"required>
                  <option value="{{$doctor->aos}}">{{$doctor->aos}}</option>
                    
                 

                  
              </select>
          </div>

          <div class="form">
              <label for="">Select Available Dates</label>

              <input name="dates" class="calendar form-control" placeholder="Select Dates"required>

              <input type="hidden"id="mydates" name="mydates"  />
          </div>


          

         




      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Create Calendar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete calendar modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <h4 class="font-weight-bold">Are you sure you want to delete calendar ?</h4>
      <p style="font-size:17px;">Please note that this will delete the entire calendar and associated time.</p>
        
      <form action="{{route('deletespecificspecialistschedules',$doctor->uuid)}}"method="POST">
          @csrf 
          {{method_field('DELETE')}}

        
          

      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Delete Calendar</button>
      </div>
      </form>
    </div>
  </div>
</div>




@endsection



@section('css')
    <!-- Datatables css -->
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
       .flatpickr-day.prevMonthDay,
.flatpickr-day.nextMonthDay {
  height: 0;
  width: 0;
  visibility: hidden;
}
    </style>
@endsection


@section('js')

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
       
        const d = new Date();
let month = d.getMonth();
month = month + 1; //

let today = d.getDate() 
let year = d.getFullYear()
var lastDay = new Date(d.getFullYear(), d.getMonth() + 1, 0);
var last = lastDay.getDate();
console.log(month)
console.log(today)
console.log(year)
var minDate = today+"/"+month+"/"+year ;
var maxDate = last+"/"+month+"/"+year ;

console.log(minDate);
        flatpickr('.calendar', 
        { dateFormat: 'd/m/Y',
            mode: "multiple",
             minDate: minDate,
             //maxDate: maxDate,
            
           
             onChange: function(selectedDates, dateStr, instance) {
                    //console.log(selectedDates)
                    //console.log(dateStr)

                    const picked = [];

                    selectedDates.map(function(e){
                        const f = new Date(e);
                    let month = d.getMonth();
                    month = month + 1; //change back to 1
                    let today = f.getDate() 
                    let year = f.getFullYear()

                    var mydate = today+"/"+month+"/"+year ;

                    //console.log(mydate);

                    picked.push(mydate);


                    })

                    console.log(picked);
                    document.getElementById("mydates").value = picked;
                    
                    
         },
        }
        
        
        );
    </script>





@endsection

