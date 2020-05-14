@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Drugs Order</li>
                    </ol>
                </div>
                <h4 class="page-title">Drugs Order</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-6 col-lg-12">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="uil uil-moneybag-alt float-right"></i>
                    <h6 class="text-uppercase mt-0">Paid</h6>
                    <h2 class="my-2" id="active-users-count">{{ $total['paid'] }}</h2>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap">Total Paid Orders</span>
                    </p>
                </div> <!-- end card-body-->
            </div>
            <!--end card-->
        </div>
        <div class="col-xl-6 col-lg-12">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="uil uil-money-withdraw float-right"></i>
                    <h6 class="text-uppercase mt-0">Unpaid</h6>
                    <h2 class="my-2" id="active-users-count">{{ $total['unpaid'] }}</h2>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap">Total Unpaid Orders</span>
                    </p>
                </div> <!-- end card-body-->
            </div>
            <!--end card-->
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-12 mt-2">
                            <div class="row">
                                <div class="col-md-4">

                                    <h4 class="header-title">Drugs Order</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of drugs ordered by Nello users
                                    </p>

                                </div>
                                <div class="col-md-8">

                                    <form method="get" id="order-filter" class="row">

                                        <div class="col-md-12">

                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label>Show entries</label>
                                                    <select name="size" class="form-control">
                                                        <option value="5" @if($size == '5') selected @endif>5 records
                                                        </option>
                                                        <option value="10" @if($size == '10') selected @endif>10
                                                            records
                                                        </option>
                                                        <option value="25" @if($size == '25') selected @endif>25
                                                            records
                                                        </option>
                                                        <option value="50" @if($size == '50') selected @endif>50
                                                            records
                                                        </option>
                                                        <option value="100" @if($size == '100') selected @endif>100
                                                            records
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label>Filter by Payment Status</label>
                                                    <select name="payment" class="form-control">
                                                        <option value="">Select status</option>
                                                        <option value="paid" @if($payment == 'paid') selected @endif>
                                                            Paid Orders
                                                        </option>
                                                        <option value="unpaid"
                                                                @if($payment == 'unpaid') selected @endif>Unpaid Orders
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label>Filter by Keyword</label>
                                                    <input class="form-control" name="search" value="{{ $search }}"
                                                           placeholder="Enter Keyword"/>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Filter by Location</label>
                                                    <select class="form-control" name="location">
                                                        <option value="">Select location</option>
                                                        @foreach($locations as $loc)
                                                            <option value="{{ $loc->id }}" {{ $loc->id == $location ? 'selected' : '' }}>{{ $loc->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Filter by Date: (Start - End)</label>
                                                    <div class="form-control" data-toggle="date-picker-range" data-date-start="{{ $dateStart }}" data-date-end="{{ $dateEnd }}"
                                                         data-target-display="#selectedValue" onchange="getDateRange(event)" data-cancel-class="btn-light">
                                                        <i class="mdi mdi-calendar"></i>&nbsp;
                                                        <span id="selectedValue"></span> <i class="mdi mdi-menu-down"></i>
                                                    </div>
                                                </div>
                                            </div>
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
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Order Ref</th>
                                <th>Address</th>
                                <th>Location</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Date Ordered</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($orders as $key => $order)
                                <tr>
                                    <td>{{ ($key + 1) }}</td>
                                    <td>{{ $order->firstname }} {{ $order->lastname }}</td>
                                    <td>{{ $order->phone }}, {{ $order->email }}</td>
                                    <td>{{ $order->amount }}</td>
                                    <td><label class="badge {{ $order->payment_confirmed == 1 ? 'badge-success' : 'badge-warning' }}">{{ $order->payment_confirmed == 1 ? 'Paid' : 'Unpaid' }}</label></td>
                                    <td>{{ $order->order_ref }}</td>
                                    <td>{{ $order->address1 ?? 'Unavailable' }}</td>
                                    <td>{{ $order->location->name ?? 'Unavailable' }}</td>
                                    <td>{{ $order->city ?? 'Unavailable' }}</td>
                                    <td>{{ $order->state ?? 'Unavailable' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('h:ia F dS, Y') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                <a href="{{ url("/drugs-order/{$order->cart_uuid}/items") }}" class="dropdown-item">View Items</a>

                                                @if(($order->location->id ?? 0) == \Illuminate\Support\Facades\Auth::user()->location_id)
                                                    <button class="dropdown-item status-toggle" data-id="{{ $order->id }}"
                                                            data-status="cancelled">Notify Order Ready
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
                        {{ $orders->links() }}
                    </div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->

@endsection

@section('js')

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

        $("select[name='payment']").change(function (e) {

            let payment = $(this).val();
            if (payment !== '') params.payment = payment;
            else delete params.payment;
            delete params.page;
            window.location.href = (window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + serialize(params));

        });

        $("select[name='location']").change(function (e) {

            let location = $(this).val();
            if (location !== '') params.location = location;
            else delete params.location;
            delete params.page;
            window.location.href = (window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + serialize(params));

        });

        function getDateRange (event) {

            let dateStart = event.start.format("YYYY-MM-DD");
            let dateEnd = event.end.format("YYYY-MM-DD");

            if (dateStart !== '') params.dateStart = dateStart;
            else delete params.dateStart;

            if (dateEnd !== '') params.dateEnd = dateEnd;
            else delete params.dateEnd;
            delete params.page;
            window.location.href = (window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + serialize(params));
        }

        $("form[id='order-filter']").submit(function (e) {
            e.preventDefault();

            let search = $("input[name='search']").val();

            if (search !== '') params.search = search;
            else delete params.search;
            delete params.page;
            window.location.href = (window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + serialize(params));
        });
    </script>
@endsection

@section('css')
    <!-- Datatables css -->
    <link href="{{ asset('css/vendor/select.bootstrap4.css') }}" rel="stylesheet" type="text/css"/>
@endsection
