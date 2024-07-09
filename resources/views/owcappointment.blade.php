@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Appointments</li>
                    </ol>
                </div>
                <h4 class="page-title">Appointments</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->



    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-12 mt-2">
                            <form method="get" class="row" id="filter-form">
                                <div class="col-md-4 mb-3">

                                    
                                    <p class="text-muted font-14">
                                        Here's a list of all appointments on the OWC platform
                                    </p>

                                </div>

                                <div class="col-md-4 mb-3">
                                    <label>Show entries</label>
                                    <select name="size" class="form-control">
                                        <option value="5" @if($size == '5') selected @endif>5 records</option>
                                        <option value="10" @if($size == '10') selected @endif>10 records</option>
                                        <option value="25" @if($size == '25') selected @endif>25 records</option>
                                        <option value="50" @if($size == '50') selected @endif>50 records</option>
                                        <option value="100" @if($size == '100') selected @endif>100 records</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                <label>Select Care Type</label>
                                <select name="type" class="form-control">
                                <option value="" @if($type == '') selected @endif>Select Care Type</option>
                                        <option value="General Practitioner" @if($type == 'General Practitioner') selected @endif>General Practitioner</option>
                                        <option value="Gynaecologist" @if($type== 'Gynaecologist') selected @endif>Gynaecologist</option>
                                        <option value="Aesthetician" @if($type == 'Aesthetician') selected @endif>Aesthetician</option>
                                       
                                    </select>
                                </div>

                               
                            </form>
                        </div>
                    </div>

                    @if(session("msg"))

                    <div class="alert alert-success text-center">
                        <p>{{session('msg')}}</p>
                    </div>



                    @endif

                    <div class="table-responsive">

                        <table class="table dataTable w-100">
                            <thead>
                            <tr>
                                
                                <th>Title</th>
                                <th>Firstname</th>
                                <th>Last Name</th>
                                <th>Care Type</th>
                                <th>Ref</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>DOB</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Booked At</th>
                                <!-- <th>Type</th> -->
                                <!-- <th>Doctors</th>
                                 
                                <th>Payment_ref </th> -->


                                <!-- <th>CreatedAt</th> -->
                                <!-- <th>Links</th> -->
                                <th>Action</th>
                               
                            </tr>
                            </thead>


                            <tbody>

                          @foreach($appointments as $list)
                            <tr>
                            <td>{{$list->title}}</td>
                                <td>{{$list->user_firstname}}</td>
                                <td>{{$list->user_lastname}}</td>
                                <td>{{$list->caretype}}</td>
                                <td>{{$list->ref}}</td>
                                <td>{{$list->email}}</td>
                                <td>{{$list->phone}}</td>
                                <td>{{$list->dob}}</td>
                                <td>{{$list->date}}</td>
                                <td>{{$list->time}}</td>
                                
                                <td>{{$list->created_at->diffForHumans()}}</td>
                               

                                @if($list->type == "Online")


                                <td>{{$list->doctor}}</td>

                                @else

                                <td> </td>



                                @endif


                                @if($list->type == "Online")


                                        <td> <p class="badge badge-primary font-weight-bold text-light">{{$list->payment_ref}}</p> </td>

                                        @else

                                        <td> </td>



                                        @endif

                                <!-- <td>{{$list->created_at->diffForHumans()}}</td> -->

                                @if($list->type == "Online")

                                <td> <a href="{{$list->link}}"class="btn btn-dark btn-sm text-light">Call Link</a></td>



                                @else 
                                    <td> <a href="{{route('bookingref',$list->ref)}}"class="btn btn-success btn-sm text-light">Download</a></td>
                                @endif
                              
                                <td>
                                    <a href=""data-id="{{$list->caretype}}" class="btn btn-info text-center my-button"data-toggle="modal" data-target="#userModal{{$list->id}}">Reschedule</a>
                                  
                                    

                                </td>

                                <td>
                                <a href=""data-toggle="modal" data-target="#updateModal{{$list->id}}" class="btn btn-warning text-center">Edit</a>

                                </td>


                                <td>
                                <a href=""data-toggle="modal" data-target="#deleteModal{{$list->id}}" class="btn btn-danger text-center">Delete</a>

                                </td>
                               
                            </tr>

        <div class="modal fade" id="userModal{{$list->id}}" tabindex="-1" role="dialog" aria-labelledby="userModal{{$list->id}}" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reschedule Appointment Ref - {{$list->ref}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <div class="card bg-info px-3 py-3 text-center text-light rounded">
          Previous Appointment Date : {{$list->date}} 
          
          <br>
          

          Previous Appointment Time : {{$list->time}} 

        <br>

        Care type : {{$list->caretype}}
    



      </div>
        
      <form class="form"action="{{route('owcreschedule')}}"method="POST">
                          @csrf
                          {{method_field("PUT")}}

                          <input type="hidden"name="ref"value="{{$list->ref}}">
                          

                          

                         <div class="form-group">
                             <label for="">New Date</label>
                             
                             <!-- <input type="date"name="date" class="form-control"required> -->

                             <div class="form">
              <label for="">Select Available Dates</label>

                        <select id="myselect" name="date"class="form-control date-select" required>
                            <option value="">Select Date</option>
                        </select>
          </div>
                             
                        
                
                         </div>

                         <div class="form-group">
                         <label for="">New Time</label>
                             
                            <select name="time" id="" class="form-control time-select"required>
                                
                              



                            </select>
                             
                         </div>


                         <br>
                         
                         <button type="submit" class="btn btn-info text-center text-light">Reschedule Appointment</button>
                


                </form>
          
      </div>
     
    </div>
  </div>
</div>

<!-- delete  modal -->

<div class="modal fade" id="deleteModal{{$list->id}}" tabindex="-1" role="dialog" aria-labelledby="deleteModal{{$list->id}}" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Appointment Ref - {{$list->ref}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      
        
      <form class="form"action="{{route('deleteowcappointment',$list->id)}}"method="POST">
                          @csrf
                          {{method_field("DELETE")}}

                          <!-- <input type="hidden"name="ref"value="{{$list->ref}}"> -->
                          <p class="text-center">Are you sure you want to delete this appointment ? <br> Note details will not be retrievable after delete.</p>

                          

                         
                         
                        <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-danger text-center text-light">Delete Appointment</button>
                        </div>
                


                </form>
          
      </div>
     
    </div>
  </div>
</div>



<!-- end of second modal -->


<!-- edit customer details modal -->

<div class="modal fade" id="updateModal{{$list->id}}" tabindex="-1" role="dialog" aria-labelledby="updateModal{{$list->id}}" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Customer Details Ref - {{$list->ref}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

     
        
      <form class="form"action="{{route('owcedituserdetails')}}"method="POST">
                          @csrf
                          {{method_field("PUT")}}

                          <input type="hidden"name="ref"value="{{$list->ref}}">

                          <div class="form-group">
                              <label for="">Title</label>

                              <select name="title" class="form-control"id=""required>
                                  <option value="">Select Preferred Title</option>

                                  <option value="Mr"{{ $list->title == "Mr" ? 'selected' : '' }}>Mr</option>
                                  <option value="Mrs"{{ $list->title == "Mrs" ? 'selected' : '' }}>Mrs</option>
                                  <option value="Miss"{{ $list->title == "Miss" ? 'selected' : '' }}>Miss</option>
                                  <option value="Ms"{{ $list->title == "Ms" ? 'selected' : '' }}>Ms</option>
                                  <option value="Dr"{{ $list->title == "Dr" ? 'selected' : '' }}>Dr</option>
                                  <option value="Prof"{{ $list->title == "Prof" ? 'selected' : '' }}>Prof</option>
                                  <option value="Hon"{{ $list->title == "Hon" ? 'selected' : '' }}>Hon</option>
                              </select>
                          </div>


                          <div class="form-group">
                              <label for="">First Name</label>

                              <input type="text"name="firstname"class="form-control"value="{{$list->user_firstname}}"required>
                          </div>

                          <div class="form-group">
                              <label for="">Last Name</label>

                              <input type="text"name="lastname"class="form-control"value="{{$list->user_lastname}}"required>
                          </div>


                          <div class="form-group">
                              <label for="">Email Address</label>

                              <input type="email"name="email"class="form-control"value="{{$list->email}}"required>
                          </div>


                          <div class="form-group">
                              <label for="">Phone Number</label>

                              <input type="text"name="phone"class="form-control"value="{{$list->phone}}"required>
                          </div>


                          

                         

                        


                         <br>
                         
                         <button type="submit" class="btn btn-info text-center text-light">Edit Details</button>
                


                </form>
          
      </div>
     
    </div>
  </div>
</div>




<!-- end of edit customer details modal -->

                          @endforeach

                            </tbody>
                        </table>

                    </div>

                    <div class="table-responsive mt-3">
                        {{ $appointments->links() }}
                    </div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>

$(document).ready(function() {
    // Function to handle the button click
    $('.my-button').on('click', function() {
        // Get the data from the custom attribute
        var dataId = $(this).data('id');



        $("select[name='date']").change(function (e) {

        let selected_date = $(this).val();




   //Make the GET request using the data
$.ajax({
    url: 'https://admin.asknello.com/api/owcadmin/checkavailabletime?date=' + selected_date + '&caretype=' + dataId,
    method: 'GET',
    dataType: 'json',
    success: function(response) {
        // Handle the response data here
    
        console.log(response)

        if(response.length > 0){
            const times = response;
            const selectTime = $('.time-select');

            selectTime.empty();

            times.forEach(time => {
                         
   
                        const optionTime = $('<option></option>').attr('value', time).text(time);
                        selectTime.append(optionTime);
                    
            });
                

        }
    },
    error: function(error) {
        // Handle any errors that occur during the request
        console.error(error);
    }
    });
    //end of ajax
    

    });

        //console.log('https://admin.asknello.com/api/owcgetmostdate?specialization=' + dataId);

        //Make the GET request using the data
        $.ajax({
            url: 'https://admin.asknello.com/api/owcgetmostdate?specialization=' + dataId, // Replace '/api/data/' with the appropriate API endpoint URL
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                // Handle the response data here
                console.log(response[2]);
                

                if(response.length > 0){
                   

                    const dates = response;
                const selectElement = $('.date-select');
                const currentDate = new Date();

                selectElement.empty();
                selectElement.append($('<option></option>').attr('value', '').text('Choose Date'));

    
                    dates.forEach(date => {
                    //   const optionElement = $('<option></option>').attr('value', date).text(date);
                    //   selectElement.append(optionElement);

                    const dateParts = date.split('/');
                    const optionDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);


                            
                    if (optionDate >= currentDate || optionDate.toDateString() === currentDate.toDateString()) {
                        const optionElement = $('<option></option>').attr('value', date).text(date);
                        selectElement.append(optionElement);
                    }
                    });


                }

              

   
               

             
            },
            error: function(error) {
                // Handle any errors that occur during the request
                console.error(error);
            }
        });
    });
});

        
    </script>

    






    <script src="{{ asset('js/net-bridge/net-bridge.js') }}" type="application/javascript"></script>

    <script type="application/javascript">

        $('.pagination').find('a.page-link').map((index, item) => {
            let link = $(item).attr('href'), linkParams = link.substring((link.indexOf('?') + 1), link.length);
            const params = getSearchParameters();
            linkParams = linkParams.split('=');
            params[linkParams[0]] = linkParams[1];
            $(item).attr('href', (window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + serialize(params)));
        });

        const params = getSearchParameters();

        $("select[name='size']").change(function (e) {

            let size = $(this).val();
            if (size !== '') params.size = size;
            else delete params.size;
            delete params.page;
            window.location.href = (window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + serialize(params));

        });

        $("select[name='type']").change(function (e) {

        let type = $(this).val();
        if (type !== '') params.type = type;
        else delete params.type;
        delete params.page;
        window.location.href = (window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + serialize(params));

        });

        $("form[id='filter-form']").submit(function (e) {
            e.preventDefault();

            let search = $("input[name='search']").val();

            if (search !== '') params.search = search;
            else delete params.search;
            delete params.page;
            window.location.href = (window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + serialize(params));

        });

        // function getDateRange(event) {

        // let dateStart = event.start.toLocaleDateString("en-US", options);
        // let dateEnd = event.end.toLocaleDateString("en-US", options);

        // if (dateStart !== '') params.dateStart = dateStart;
        // else delete params.dateStart;

        // if (dateEnd !== '') params.dateEnd = dateEnd;
        // else delete params.dateEnd;
        // delete params.page;
        // window.location.href = (window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + serialize(params));
        // }


        {{--const instance = NetBridge.getInstance();--}}

        {{--$('.status-toggle').click(function (e) {--}}

        {{--    let self = $(this), status = self.data('status'), timeout;--}}

        {{--    let title = status === 'approved' ? 'Approve ' : (status === 'disapproved' ? 'Disapprove ' : 'Cancel ');--}}

        {{--    successMsg(title + 'Order', "This order will be " + status + ", do you want proceed?",--}}
        {{--        'Yes, proceed', 'No, cancel', function ({value}) {--}}

        {{--            if (!value) return;--}}

        {{--            timeout = setTimeout(() => {--}}

        {{--                instance.addToRequestQueue({--}}
        {{--                    url: "{{ url('/drugs-order/item/action') }}",--}}
        {{--                    method: 'post',--}}
        {{--                    timeout: 10000,--}}
        {{--                    dataType: 'json',--}}
        {{--                    data: {--}}
        {{--                        id: parseInt(self.data('id')),--}}
        {{--                        status: status,--}}
        {{--                        '_token': "{{ csrf_token() }}"--}}
        {{--                    },--}}
        {{--                    beforeSend: () => {--}}
        {{--                        swal.showLoading();--}}
        {{--                    },--}}
        {{--                    success: (data, status, xhr) => {--}}

        {{--                        swal.hideLoading();--}}

        {{--                        if (data.status !== true) {--}}
        {{--                            errorMsg(title + 'Failed', typeof data.appointment !== 'string' ? serializeMessage(data.appointment) : data.appointment, 'Ok');--}}
        {{--                            return false;--}}
        {{--                        }--}}

        {{--                        successMsg(title + 'Successful', data.appointment);--}}

        {{--                        timeout = setTimeout(() => {--}}
        {{--                            window.location.reload();--}}
        {{--                            clearTimeout(timeout);--}}
        {{--                        }, 2000);--}}

        {{--                    },--}}
        {{--                    ontimeout: () => {--}}
        {{--                        swal.hideLoading();--}}
        {{--                        errorMsg(title + 'Failed', 'Failed to ' + type + ' this order at this time as the request timed out', 'Ok');--}}
        {{--                    },--}}
        {{--                    error: (data, xhr, status, statusText) => {--}}

        {{--                        swal.hideLoading();--}}

        {{--                        errorMsg(title + 'Failed', typeof data.appointment !== 'string' ? serializeMessage(data.appointment) : data.appointment, 'Ok');--}}
        {{--                    }--}}
        {{--                });--}}

        {{--                clearTimeout(timeout);--}}
        {{--            }, 500);--}}
        {{--        })--}}
        {{--});--}}

    </script>
@endsection

@section('css')
    <!-- Datatables css -->
    <link href="{{ asset('css/vendor/select.bootstrap4.css') }}" rel="stylesheet" type="text/css"/>
@endsection
