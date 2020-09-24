@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Riders</li>
                    </ol>
                </div>
                <h4 class="page-title">Riders</h4>
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

                                    <h4 class="header-title">Riders</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of all riders on the Nello platform
                                    </p>

                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Show entries</label>
                                    <select name="size" class="form-control">
                                        <option value="5" @if($size == '5') selected @endif>5 records</option>
                                        <option value="10" @if($size == '10') selected @endif>10 records</option>
                                        <option value="25" @if($size == '25') selected @endif>25 records</option>
                                        <option value="50" @if($size == '50') selected @endif>50 records</option>
                                        <option value="100" @if($size == '100') selected @endif>100 records</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Filter by Gender</label>
                                    <select name="gender" class="form-control">
                                        <option value="">Select gender</option>
                                        <option value="Male" @if($gender == 'Male') selected @endif>Male Riders</option>
                                        <option value="Female" @if($gender == 'Female') selected @endif>Female Riders</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
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
                                <th>Picture</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Specialization</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Assigned Location</th>
                                <th>Gender</th>
                                <th>Dob</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Religion</th>
                                <th>Height</th>
                                <th>Weight</th>
                                <th>Sponsor</th>
                                <th>Total Delivery</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($riders as $key => $rider)
                                <tr>
                                    <td>{{ ($key + 1) }}</td>
                                    <td><img src="{{ $rider->image ?: asset('images/rider.png') }}"
                                             class="img-thumbnail" width="80"/></td>
                                    <td>{{ $rider->firstname }} {{ $rider->lastname }}</td>
                                    <td>{{ $rider->username ?: 'Unavailable' }}</td>
                                    <td>{{ $rider->aos ?: 'Unavailable' }}</td>
                                    <td>{{ $rider->phone }}</td>
                                    <td>{{ $rider->email }}</td>
                                    <td>{{ $rider->address ?: 'Unavailable' }}</td>
                                    <td>{{ $rider->location->name ?? 'Unavailable' }}</td>
                                    <td>{{ $rider->gender ?: 'Unavailable' }}</td>
                                    <td>{{ $rider->dob ? \Carbon\Carbon::parse($rider->dob)->format('F dS, Y') : 'Unavailable' }}</td>
                                    <td>{{ $rider->state ?: 'Unavailable' }}</td>
                                    <td>{{ $rider->city ?: 'Unavailable' }}</td>
                                    <td>{{ $rider->religion ?: 'Unavailable' }}</td>
                                    <td>{{ $rider->height ?: 'Unavailable' }}</td>
                                    <td>{{ $rider->weight ?: 'Unavailable' }}</td>
                                    <td>{{ $rider->sponsor ?: 'Unavailable' }}</td>
                                    <td>{{ $rider->delivered->count() ?: 0 }}</td>
                                    <td>
                                        <label class="badge {{ $rider->active == 1 ? 'badge-success' : 'badge-warning' }}">{{ $rider->active ? 'active' : 'inactive' }}</label>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="{{ url("/rider/{$rider->uuid}/view") }}">Edit Account</a>
                                                @if(!empty($rider->active == 1))
                                                    <button class="dropdown-item status-toggle" data-id="{{ $rider->id }}"
                                                            data-status="cancelled">Deactivate Account
                                                    </button>
                                                @else
                                                    <button class="dropdown-item status-toggle" data-id="{{ $rider->id }}"
                                                            data-status="cancelled">Activate Account
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>

                    <div class="table-responsive mt-3">
                        {{ $riders->links() }}
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

            let self = $(this), uuid = self.data('id'), timeout;

            successMsg('Delete Rider', "This rider will be deleted, once done it cannot be undone, do you want proceed?",
                'Yes, proceed', 'No, cancel', function ({value}) {

                    if (!value) return;

                    timeout = setTimeout(() => {

                        instance.addToRequestQueue({
                            url: "{{ route('rider-delete') }}",
                            method: 'post',
                            timeout: 10000,
                            dataType: 'json',
                            data: {
                                uuid,
                                '_token': "{{ csrf_token() }}"
                            },
                            beforeSend: () => {
                                swal.showLoading();
                            },
                            success: (data, status, xhr) => {

                                swal.hideLoading();

                                if (data.status !== true) {
                                    errorMsg('Rider Delete Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                                    return false;
                                }

                                successMsg('Rider Delete Successful', data.message);

                                self.closest('tr').fadeOut(600, function () {
                                    $(this).detact();
                                });

                            },
                            ontimeout: () => {
                                swal.hideLoading();
                                errorMsg('Rider Delete Failed', 'Failed to delete this rider at this time as the request timed out', 'Ok');
                            },
                            error: (data, xhr, status, statusText) => {

                                swal.hideLoading();

                                errorMsg('Rider Delete Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
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
