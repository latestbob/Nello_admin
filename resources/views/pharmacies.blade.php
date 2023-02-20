@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pharmacies</li>
                    </ol>
                </div>
                <h4 class="page-title">Pharmacies</h4>
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
                            <div class="row">
                                <div class="col-md-6">

                                    <h4 class="header-title">Pharmacies</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of pharmacies on the Nello platform
                                    </p>

                                </div>
                                <div class="col-md-6">

                                    <form method="get" id="form-filter" class="row">

                                        <div class="col-md-6 mb-3">
                                            <label>Show entries</label>
                                            <select name="size" class="form-control">
                                                <option value="5" @if($size == '5') selected @endif>5 records</option>
                                                <option value="10" @if($size == '10') selected @endif>10 records
                                                </option>
                                                <option value="25" @if($size == '25') selected @endif>25 records
                                                </option>
                                                <option value="50" @if($size == '50') selected @endif>50 records
                                                </option>
                                                <option value="100" @if($size == '100') selected @endif>100 records
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>Filter by Keyword</label>
                                            <input class="form-control" name="search" value="{{ $search }}"
                                                   placeholder="Enter keyword"/>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                    @if($pharmacies->count() > 0)

                    <div class="table-responsive">

                        <table class="table dataTable w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Picture</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Assigned Location</th>
                                <th>Is Pick up Location</th>
                                <th>Parent Pharmacy</th>
                                <th>Date Added</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($pharmacies as $key => $pharmacy)
                                <tr>
                                    <td>{{ ($key + 1) }}</td>
                                    <td>
                                        @if($pharmacy->picture)
                                        <img src="{{ $pharmacy->picture }}"
                                             class="img-thumbnail" width="60"/>


                                        @elseif($pharmacy->email == "Famacare Limited")
                                        <img src="https://famacare.com/img/famacare.png"
                                             class="img-thumbnail" width="60"/>

                                        @else
                                        <img src="https://famacare.com/img/famacare.png"
                                             class="img-thumbnail" width="60"/>

                                        @endif
                                    </td>
                                    <td>{{ $pharmacy->name }}</td>
                                    <td>{{ $pharmacy->address }}</td>
                                    <td>{{ $pharmacy->email }}</td>
                                    <td>{{ $pharmacy->phone }}</td>
                                    <td>{{ $pharmacy->location->name ?? 'Unassigned' }}</td>
                                    <td>{{ $pharmacy->is_pick_up_location == 1 ? "Yes" : "No" }}</td>
                                    <td>{{ $pharmacy->parent->name ?? 'None' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pharmacy->created_at)->format('h:ia F dS, Y') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="{{ route("pharmacy-view", ['uuid' => $pharmacy->uuid]) }}">View Pharmacy</a>
                                                <button class="dropdown-item status-toggle" data-id="{{ $pharmacy->uuid }}"
                                                        data-status="cancelled">Delete Pharmacy
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>

                    @else 

                    <div class="text-center py-3">
                            <h3 class="font-weight-bold">Search Result not found</h3>
                    </div>
                    @endif

                    <div class="table-responsive mt-3">
                        {{ $pharmacies->links() }}
                    </div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->

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

        $("form[id='form-filter']").submit(function (e) {
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

            successMsg('Delete Pharmacy', "This pharmacy will be deleted, once done it cannot be undone, do you want proceed?",
                'Yes, proceed', 'No, cancel', function ({value}) {

                    if (!value) return;

                    timeout = setTimeout(() => {

                        instance.addToRequestQueue({
                            url: "{{ route('pharmacy-delete') }}",
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
                                    errorMsg('Pharmacy Delete Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                                    return false;
                                }

                                successMsg('Pharmacy Delete Successful', data.message);

                                self.closest('tr').fadeOut(600, function () {
                                    $(this).detact();
                                });

                            },
                            ontimeout: () => {
                                swal.hideLoading();
                                errorMsg('Pharmacy Delete Failed', 'Failed to delete this pharmacy at this time as the request timed out', 'Ok');
                            },
                            error: (data, xhr, status, statusText) => {

                                swal.hideLoading();

                                errorMsg('Pharmacy Delete Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                            }
                        });

                        clearTimeout(timeout);
                    }, 500);
                })
        });
    </script>
@endsection
