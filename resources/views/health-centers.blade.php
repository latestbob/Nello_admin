@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Health Centers</li>
                    </ol>
                </div>
                <h4 class="page-title">Health Centers</h4>
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

                                    <h4 class="header-title">Doctors</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of all doctors on the Nello platform
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

                    <div class="table-responsive">

                        <table class="table dataTable w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Center Type</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address One</th>
                            
                                <th>State</th>
                                <th>City</th>
                                <th>Consultation Fee</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($healthCenters as $key => $healthCenter)
                                <tr>
                                    <td>{{ ($key + 1) }}</td>
                                    <td>{{ $healthCenter->name ?: 'Unavailable' }}</td>
                                    <td>{{ $healthCenter->center_type ?: 'Unavailable' }}</td>
                                    <td>{{ $healthCenter->phone ?: 'Unavailable' }}</td>
                                    <td>{{ $healthCenter->email ?: 'Unavailable' }}</td>
                                    <td>{{ $healthCenter->address1 ?: 'Unavailable' }}</td>
                                   
                                    <td>{{ $healthCenter->state ?: 'Unavailable' }}</td>
                                    <td>{{ $healthCenter->city ?: 'Unavailable' }}</td>
                                    <td>{{ $healthCenter->fee ?: 'Unavailable' }}</td>
                                    <td>
                                        <label class="badge {{ $healthCenter->is_active == true ? 'badge-success' : 'badge-warning' }}">{{ $healthCenter->is_active ? 'active' : 'inactive' }}</label>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="{{ route('health-center-view', ['uuid' => $healthCenter->uuid]) }}">Edit Center</a>
                                                @if(!empty($healthCenter->is_active == true))
                                                    <button class="dropdown-item status-toggle" data-uuid="{{ $healthCenter->uuid }}"
                                                            data-status="deactivate">Deactivate Center
                                                    </button>
                                                @else
                                                    <button class="dropdown-item status-toggle" data-uuid="{{ $healthCenter->uuid }}"
                                                            data-status="activate">Activate Center
                                                    </button>
                                                @endif

                                                <a class="dropdown-item"href="{{route('health-center-specschedule',$healthCenter->uuid)}}">Spec & Schedule</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>

                    <div class="table-responsive mt-3">
                        {{ $healthCenters->links() }}
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

        const instance = NetBridge.getInstance();

        $('.status-toggle').click(function (e) {

            let self = $(this), status = self.data('status'), timeout;

            let title = (status === 'deactivate' ? 'Deactivate ' : 'Activate');

            successMsg(title + ' Health Center', `This health center will be ${status}d, do you want proceed?`,
                'Yes, proceed', 'No, cancel', function ({value}) {

                    if (!value) return;

                    timeout = setTimeout(() => {

                        instance.addToRequestQueue({
                            url: "{{ route('health-center-status') }}",
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
                                errorMsg(title + 'Failed', 'Failed to ' + status + ' this health center at this time as the request timed out', 'Ok');
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
