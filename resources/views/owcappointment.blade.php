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
                                <th>Type</th>
                                <th>Doctors</th>
                                 
                                <th>Payment_ref </th>


                                <th>CreatedAt</th>
                                <th>Links</th>
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
                                @if($list->type == "Online")

                                <td>{{$list->type}}</td>

                                @else 

                                <td>Visitation</td>


                                @endif

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

                                <td>{{$list->created_at->diffForHumans()}}</td>

                                @if($list->type == "Online")

                                <td> <a href="{{$list->link}}"class="btn btn-dark btn-sm text-light">Call Link</a></td>



                                @else 
                                    <td> <a href="{{route('bookingref',$list->ref)}}"class="btn btn-success btn-sm text-light">Download</a></td>
                                @endif
                              
                                <td>
                                    <form action="{{route('deleteowcappointment',$list->id)}}"method="POST">
                                        @csrf 

                                        {{method_field('DELETE')}}
                                        <button type="submit" class="btn btn-danger text-light">Delete</button>


                                    </form>
                                </td>
                               
                            </tr>

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
