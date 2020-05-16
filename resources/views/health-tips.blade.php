@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Health Tip</li>
                    </ol>
                </div>
                <h4 class="page-title">Health Tips</h4>
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

                                    <h4 class="header-title">Health Tips</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of health tips on the Nello platform
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
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Month</label>
                                            <select class="form-control" name="month">
                                                <option value="">Select month</option>
                                                @foreach($months as $k => $m)
                                                    <option value="{{ $k }}" {{ $k == $month ? 'selected' : '' }}>{{ $m }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label>Year</label>
                                            <select class="form-control" name="year">
                                                <option value="">Select year</option>
                                                @foreach($years as $y)
                                                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
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
                                <th>Title</th>
                                <th>Body</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($tips as $key => $tip)
                                <tr>
                                    <td>{{ ($key + 1) }}</td>
                                    <td>{{ $tip->title ?? 'Unavailable' }}</td>
                                    <td>{{ $tip->body ? (substr(strip_tags($tip->body), 0, 100) . (strlen($tip->body) > 100 ? '...' : '')) : 'Unavailable' }}</td>
                                    <td>{{ $tip->date ? \Carbon\Carbon::parse($tip->date)->format('F dS, Y') : 'Unavailable' }}</td>
                                    <td><a href="{{ route('health-tip-view', ['uuid' => $tip->uuid]) }}" class="btn btn-primary">View Tip</a></td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>

                    <div class="table-responsive mt-3">
                        {{ $tips->links() }}
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

        $("select[name='year']").change(function (e) {

            let year = $(this).val();
            if (year !== '') params.year = year;
            else delete params.year;
            delete params.page;
            window.location.href = (window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + serialize(params));

        });

        $("select[name='month']").change(function (e) {

            let month = $(this).val();
            if (month !== '') params.month = month;
            else delete params.month;
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
        {{--                            errorMsg(title + 'Failed', Array.isArray(data.message) ? serializeMessage(data.message) : data.message, 'Ok');--}}
        {{--                            return false;--}}
        {{--                        }--}}

        {{--                        successMsg(title + 'Successful', data.message);--}}

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

        {{--                        errorMsg(title + 'Failed', Array.isArray(data.message) ? serializeMessage(data.message) : data.message, 'Ok');--}}
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
