@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Locations</li>
                    </ol>
                </div>
                <h4 class="page-title">Locations</h4>
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

                                    <h4 class="header-title">Locations</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of locations from which Nello users can place an order
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

                    <div class="table-responsive">

                        <table class="table dataTable w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Location</th>
                                <th>Standard Price</th>
                                <th>Same Day Price</th>
                                <th>Next Day Price</th>
                                <th>Date Added</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($locations as $key => $location)
                                <tr>
                                    <td>{{ ($key + 1) }}</td>
                                    <td>{{ $location->name }}</td>
                                    <td>{{ $location->standard_price }}</td>
                                    <td>{{ $location->same_day_price }}</td>
                                    <td>{{ $location->next_day_price }}</td>
                                    <td>{{ \Carbon\Carbon::parse($location->created_at)->format('h:ia F dS, Y') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="{{ route("location-view", ['uuid' => $location->uuid]) }}">Edit Location</a>
                                                <button class="dropdown-item status-toggle" data-id="{{ $location->uuid }}"
                                                        data-status="cancelled">Delete Location
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>

                    <div class="table-responsive mt-3">
                        {{ $locations->links() }}
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

            successMsg('Delete Location', "This location will be deleted, once done it cannot be undone, do you want proceed?",
                'Yes, proceed', 'No, cancel', function ({value}) {

                    if (!value) return;

                    timeout = setTimeout(() => {

                        instance.addToRequestQueue({
                            url: "{{ route('location-delete') }}",
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
                                    errorMsg('Location Delete Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                                    return false;
                                }

                                successMsg('Location Delete Successful', data.message);

                                self.closest('tr').fadeOut(600, function () {
                                    $(this).detact();
                                });

                            },
                            ontimeout: () => {
                                swal.hideLoading();
                                errorMsg('Location Delete Failed', 'Failed to delete this location at this time as the request timed out', 'Ok');
                            },
                            error: (data, xhr, status, statusText) => {

                                swal.hideLoading();

                                errorMsg('Location Delete Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                            }
                        });

                        clearTimeout(timeout);
                    }, 500);
                })
        });
    </script>
@endsection
