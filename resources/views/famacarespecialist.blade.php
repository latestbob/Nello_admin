@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Doctors/Nurses</li>
                    </ol>
                </div>
                <h4 class="page-title">Registered Specialist -  {{$count}}</h4>
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
                                <div class="col-md-3 mb-3">

                                    <h4 class="header-title">Doctors</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of all doctors on the Nello platform
                                    </p>

                                </div>

                                <div class="col-md-3 mb-3">
                                    
                                </div>

                                <div class="col-md-3 mb-3">
                                    
                                </div>

                                <div class="col-md-3 mb-3">
                                  
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">

                        <table class="table dataTable w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                               
                                <th>Name</th>
                                <th>Hospital</th>
                                <th>Specialization</th>
                               
                                <th>Email</th>
                             
                                <th>Gender</th>
                                
                               
                                <th>Consulting Fee</th>
                               
                                <th>Status</th>
                                <th>Action</th>
                               
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($specialist as $key => $doctor)
                                <tr>
                                    <td>{{ ($key + 1) }}</td>
                                    
                                    <td>{{ $doctor->firstname }} {{ $doctor->lastname }}</td>
                                    <td>{{ $doctor->hospital ?: 'Unavailable' }}</td>
                                    <td>{{ $doctor->aos ?: 'Unavailable' }}</td>
                                    
                                    <td>{{ $doctor->email }}</td>
                                    
                                    <td>{{ $doctor->gender ?: 'Unavailable' }}</td>
                                   
                                   
                                    <td>₦{{ $doctor->fee ?: 'Unavailable'}}</td>
                                   
                                    <td>
                                        <label class="badge {{ $doctor->active == 1 ? 'badge-success' : 'badge-warning' }}">{{ $doctor->active ? 'active' : 'inactive' }}</label>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                               

                                                <a class="dropdown-item"href="{{route('specialistcalendar',$doctor->id)}}">Calendar</a>
                                            </div>
                                        </div>
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

        $("select[name='gender']").change(function (e) {

            let gender = $(this).val();
            if (gender !== '') params.gender = gender;
            else delete params.gender;
            delete params.page;
            window.location.href = (window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + serialize(params));

        });

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

        const instance = NetBridge.getInstance();

        $('.status-toggle').click(function (e) {

            let self = $(this), status = self.data('status'), timeout;

            let title = (status === 'deactivate' ? 'Deactivate ' : 'Activate');

            successMsg(title + ' Doctor', `This doctor will be ${status}d, do you want proceed?`,
                'Yes, proceed', 'No, cancel', function ({value}) {

                    if (!value) return;

                    timeout = setTimeout(() => {

                        instance.addToRequestQueue({
                            url: "{{ route('doctor-status') }}",
                            method: 'post',
                            timeout: 10000,
                            dataType: 'json',
                            data: {
                                uuid: self.data('uuid'),
                                '_token': "{{ csrf_token() }}"
                            },
                            beforeSend: () => {
                                swal.showLoading();
                            },
                            success: (data, status, xhr) => {

                                swal.hideLoading();

                                if (data.status !== true) {
                                    errorMsg(title + 'Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                                    return false;
                                }

                                successMsg(title + 'Successful', data.message);

                                timeout = setTimeout(() => {
                                    window.location.reload();
                                    clearTimeout(timeout);
                                }, 2000);

                            },
                            ontimeout: () => {
                                swal.hideLoading();
                                errorMsg(title + 'Failed', 'Failed to ' + status + ' this doctor at this time as the request timed out', 'Ok');
                            },
                            error: (data, xhr, status, statusText) => {

                                swal.hideLoading();

                                errorMsg(title + 'Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                            }
                        });

                        clearTimeout(timeout);
                    }, 500);
                })
        });

    </script>
@endsection

@section('css')
    <!-- Datatables css -->
    <link href="{{ asset('css/vendor/select.bootstrap4.css') }}" rel="stylesheet" type="text/css"/>
@endsection
