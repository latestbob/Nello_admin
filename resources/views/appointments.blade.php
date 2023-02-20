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

                                    <h4 class="header-title">Messages</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of all appointments on the Nello platform
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
                                    <label>Filter by Keyword</label>
                                    <input class="form-control" name="search" value="{{ $search }}"
                                           placeholder="Enter Keyword"/>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($appointments->count() > 0 )
                    <div class="table-responsive">

                        <table class="table dataTable w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Customer Email</th>
                                <th>Reason for Visit</th>
                                <th>Center/Specialist</th>
                                <th>Payment Ref </th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>

                                <th>CreatedAt</th>
                                <th>Link</th>
                                <th>Actions</th>
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($appointments as $key => $appointment)

                            @if($appointment->user)
                                <tr>
                                    <td>{{ ($key + 1) }}</td>

                                    @if($appointment->user->firstname)
                                    <td>{{ $appointment->user->firstname }} {{ $appointment->user->lastname }}</td>

                                    @else
                                    <td>Unavailable</td>
                                    @endif
                                    
                                    <td>{{ $appointment->user->email }}</td>
                                    <td>{{ $appointment->reason }}</td>
                                    <td>
                                        @if($appointment->center)
                                        {{ $appointment->center->name }}
                                        @endif
                                        @if($appointment->doctor)
                                        {{$appointment->doctor->title}}. {{ $appointment->doctor->firstname }} {{ $appointment->doctor->lastname }}
                                        @endif
                                    </td>
                                    <td>{{$appointment->ref_no}} </td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->date)->format('F dS, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->time)->format('h:ia') }}</td>
                                    <td> @if($appointment->status == "completed") 

                                        <p class="badge badge-success text-light">{{$appointment->status}}</p>

                                        @else 
                                        <p class="badge badge-secondary text-light">{{$appointment->status}}</p>

                                    @endif

                                    </td>
                                    <td>{{ $appointment->created_at->diffForHumans() }}</td>
                                    <td>
                                    @if($appointment->doctor)
                                        {{ $appointment->link }}

                                        @else
                                        Unavailable
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <!-- <a class="dropdown-item" href="">View More</a> -->
                                                
                                                @if(!empty($appointment->status == "pending"))
                                                
                                                <form action="{{route('appointmentupdate',$appointment->id)}}"method="POST">
                                                    @csrf
                                                    {{method_field('PUT')}}

                                                    <button  class="dropdown-item status-toggle" 
                                                            data-status="cancelled">Mark As Complete
                                                    </button>
                                                </form>
                                         
                                                @else
                                                   <form action="{{route('appointmentpending',$appointment->id)}}"method="POST">
                                                    @csrf 

                                                    {{method_field('PUT')}}

                                                    <button class="dropdown-item status-toggle" 
                                                            data-status="cancelled">Mark as pending
                                                    </button>
                                                   </form>
                                                @endif

                                                <a href="{{route('appointmentreschedule',$appointment->id)}}" class="dropdown-item " 
                                                            >Re-Schedule
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                @endif
                            @endforeach

                            </tbody>
                        </table>

                    </div>

                    @else 

                    <div class="alert alert-warning text-center py-3 mt-3">No record was found</div>

                    @endif



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

        $("form[id='filter-form']").submit(function (e) {
            e.preventDefault();

            let search = $("input[name='search']").val();

            if (search !== '') params.search = search;
            else delete params.search;
            delete params.page;
            window.location.href = (window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + serialize(params));

        });

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
