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
                <h4 class="page-title">Calendar</h4>
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
    <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active " id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">General Practitioner</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Gynaecologist</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="contact-tab" data-toggle="tab" data-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Urologist</button>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
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
                                   <th>Month</th>
                                   
                                   <th>Action</th>
                               </tr>
                           </thead>

                           <tbody>
                               @foreach($general as $dates)

                               <tr>
                                   <td>{{$dates->specialization}}</td>
                                   <td>{{$dates->date}}</td>
                                   <td>{{$dates->monthstring}}</td>
                                   
                                   <td>
                                       <form action="{{route('deletecalendardate',$dates->id)}}"method="POST">
                                           @csrf
                                           {{method_field('DELETE')}}

                                            <button class="btn btn-danger text-light">Remove</button>||<a href="{{route('owcascheduletime',$dates->id)}}"class="btn btn-info text-center text-light">Manage Time</a>
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
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
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
                                   <th>Month</th>
                                   
                                  
                                

                                   <th>Action</th>
                               </tr>
                           </thead>

                           <tbody>
                               @foreach($gynecology as $dates2)

                               <tr>
                                   <td>{{$dates2->specialization}}</td>
                                   <td>{{$dates2->date}}</td>
                                   <td>{{$dates2->monthstring}}</td>
                                 
                                   <td>
                                       <form action="{{route('deletecalendardate',$dates2->id)}}"method="POST">
                                           @csrf
                                           {{method_field('DELETE')}}

                                            <button class="btn btn-danger text-light">Remove</button>||<a href="{{route('owcascheduletime',$dates2->id)}}"class="btn btn-info text-center text-light">Manage Time</a>
                                            
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
  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

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
                                   <th>Month</th>
                                   


                                   <th>Action</th>
                               </tr>
                           </thead>

                           <tbody>
                               @foreach($urologist as $dates3)

                               <tr>
                                   <td>{{$dates3->specialization}}</td>
                                   <td>{{$dates3->date}}</td>
                                   <td>{{$dates3->monthstring}}</td>
                                   
                                   

                                   <td>
                                       <form action="{{route('deletecalendardate',$dates3->id)}}"method="POST">
                                           @csrf
                                           {{method_field('DELETE')}}

                                           <button class="btn btn-danger text-light">Remove</button>||<a href="{{route('owcascheduletime',$dates3->id)}}"class="btn btn-info text-center text-light">Manage Time</a>
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
        
      <form action="{{route('owcmedicalcalendarpost')}}"method="POST">
          @csrf 

          <div class="form-group">
              <label for="">Specialization</label>
              <select name="specialization" id=""class="form-control"required>
                  <option value="">Select Specialization</option>
                    
                  <option value="General Practitioner">General Practitioner</option>
                  <option value="Gynaecologist">Gynaecologist</option>
                  <option value="Urologist">Urologist</option>
                  

                  
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
      <p style="font-size:17px;">Please note that this will delete the entire calendar created.</p>
        
      <form action="{{route('calendardelete')}}"method="POST">
          @csrf 

        
          

      
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
var lastDay = new Date(d.getFullYear(), d.getMonth() + 2, 0);
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
                    let month = f.getMonth() + 1 ;
                   // month = month + 1; //change back to 1
                   // month = f.getMonth(); //change back to 1
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

