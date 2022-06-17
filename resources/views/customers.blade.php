@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Customers</li>
                    </ol>
                </div>
                <h4 class="page-title">Total Customers {{ \DB::table('users')->where(['user_type' => 'customer'])->count() }} </h4>
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

                                    <h4 class="header-title">Customers</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of all customers on the Nello platform
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
                                        <option value="Male" @if($gender == 'Male') selected @endif>Male Customers</option>
                                        <option value="Female" @if($gender == 'Female') selected @endif>Female Customers</option>
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
                             
                                <th>Name</th>
                               
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Gender</th>
                                <th>Dob</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Religion</th>
                                <th>Height</th>
                                <th>Weight</th>
                                <th>Sponsor</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($customers as $key => $customer)
                                <tr>
                                    <td>{{ ($key + 1) }}</td>
                                  
                                    <td class="name">{{ $customer->firstname }} {{ $customer->lastname }}</td>
                                   
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->address ?: 'Unavailable' }}</td>
                                    <td>{{ $customer->gender ?: 'Unavailable' }}</td>
                                    <td>{{ $customer->dob ? \Carbon\Carbon::parse($customer->dob)->format('F dS, Y') : 'Unavailable' }}</td>
                                    <td>{{ $customer->state ?: 'Unavailable' }}</td>
                                    <td>{{ $customer->city ?: 'Unavailable' }}</td>
                                    <td>{{ $customer->religion ?: 'Unavailable' }}</td>
                                    <td>{{ $customer->height ?: 'Unavailable' }}</td>
                                    <td>{{ $customer->weight ?: 'Unavailable' }}</td>
                                    <td>{{ $customer->sponsor ?: 'Unavailable' }}</td>
                                    <td>
                                        <label class="badge {{ $customer->active == 1 ? 'badge-success' : 'badge-warning' }}">{{ $customer->active ? 'active' : 'inactive' }}</label>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="{{ url("/customer/{$customer->uuid}/view") }}">Edit Account</a>
                                                <button class="dropdown-item customer-make-agent" data-uuid="{{ $customer->uuid }}">
                                                    Make Pharmacy Agent
                                                </button>
                                                @if(!empty($customer->active == 1))
                                                    <button class="dropdown-item status-toggle" data-id="{{ $customer->id }}"
                                                            data-status="cancelled">Deactivate Account
                                                    </button>
                                                @else
                                                    <button class="dropdown-item status-toggle" data-id="{{ $customer->id }}"
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
                        {{ $customers->links() }}
                    </div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>

    <input id="pharmacies" value="{{ $pharmacies }}" hidden>

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

        const pharmacies = JSON.parse($('#pharmacies').val());
        const instance = NetBridge.getInstance();

        $('.customer-make-agent').click(function (e) {

            let self = $(this), customerName = self.closest('td').siblings('.name').html();

            successMsg('Add Agent', "<div class='row text-left'><div class='col-md-12'>" +
                "<div class='form-group'><label>Select Pharmacy</label>" +
                "<select class='form-control pharmacy'>" + pharmacies.map(pharmacy => "<option value='" + pharmacy.id + "'>" + pharmacy.name + "</option>") + "</select>" +
                "</div></div></div><small>Adding '" + customerName + "' as an agent</small>",
                'Add', 'Cancel', function ({value}) {

                console.log('value', value);
                    if (!value) return;

                    let id = $('.pharmacy').val(),
                        uuid = self.data('uuid');

                    let dispatch = () => {

                        instance.addToRequestQueue({
                            url: "{{ route('customer-make-agent') }}",
                            method: 'POST',
                            timeout: 20000,
                            dataType: 'json',
                            data: {
                                uuid,
                                id,
                                _token: "{{ csrf_token() }}"
                            },
                            beforeSend: function () {
                                swal.showLoading();
                            },
                            success: (data, status, xhr) => {

                                swal.hideLoading();

                                if (data.status !== true) {
                                    errorMsg('Agent Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                                    return false;
                                }

                                successMsg('Agent Successful', data.message);

                                timeout = setTimeout(() => {
                                    window.location.reload();
                                    clearTimeout(timeout);
                                }, 2000);

                            },
                            ontimeout: () => {
                                swal.hideLoading();
                                errorMsg('Agent Failed', 'Failed to add prescription to this order at this time as the request timed out', 'Ok');
                            },
                            error: (data, xhr, status, statusText) => {

                                swal.hideLoading();

                                errorMsg('Agent Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                            }
                        });

                    };

                    dispatch();
                }, true)
        });

    </script>
@endsection

@section('css')
    <!-- Datatables css -->
    <link href="{{ asset('css/vendor/select.bootstrap4.css') }}" rel="stylesheet" type="text/css"/>
@endsection
