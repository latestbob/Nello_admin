@extends('layouts.dashboard')

@section('content')


    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    </ol>
                </div>
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-3 col-lg-12">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="uil uil-moneybag-alt float-right"></i>
                    <h6 class="text-uppercase mt-0">Paid Today</h6>
                    <h2 class="my-2" id="active-users-count">{{ $total['order']['day']['paid'] }}</h2>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap">Total Paid Orders for Today</span>
                    </p>
                </div> <!-- end card-body-->
            </div>
            <!--end card-->
        </div>
        <div class="col-xl-3 col-lg-12">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="uil uil-money-withdraw float-right"></i>
                    <h6 class="text-uppercase mt-0">Unpaid Today</h6>
                    <h2 class="my-2" id="active-users-count">{{ $total['order']['day']['unpaid'] }}</h2>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap">Total Unpaid Orders for Today</span>
                    </p>
                </div> <!-- end card-body-->
            </div>
            <!--end card-->
        </div>
        <div class="col-xl-3 col-lg-12">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="uil uil-moneybag-alt float-right"></i>
                    <h6 class="text-uppercase mt-0">Paid Month</h6>
                    <h2 class="my-2" id="active-users-count">{{ $total['order']['month']['paid'] }}</h2>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap">Total Paid Orders for this Month</span>
                    </p>
                </div> <!-- end card-body-->
            </div>
            <!--end card-->
        </div>
        <div class="col-xl-3 col-lg-12">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="uil uil-money-withdraw float-right"></i>
                    <h6 class="text-uppercase mt-0">Unpaid Month</h6>
                    <h2 class="my-2" id="active-users-count">{{ $total['order']['month']['unpaid'] }}</h2>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap">Total Unpaid Orders for this Month</span>
                    </p>
                </div> <!-- end card-body-->
            </div>
            <!--end card-->
        </div>
    </div>

    @if(\Illuminate\Support\Facades\Auth::check() &&
            \Illuminate\Support\Facades\Auth::user()->admin_type == "admin")
        <div class="row">
            <div class="col-xl-6 col-lg-12">
                <div class="card tilebox-one">
                    <div class="card-body">
                        <i class="mdi mdi-rss float-right"></i>
                        <h6 class="text-uppercase mt-0">Feedback Today</h6>
                        <h2 class="my-2" id="active-users-count">{{ $total['feedback']['day'] }}</h2>
                        <p class="mb-0 text-muted">
                            <span class="text-nowrap">Total Feedback for Today</span>
                        </p>
                    </div> <!-- end card-body-->
                </div>
                <!--end card-->
            </div>
            <div class="col-xl-6 col-lg-12">
                <div class="card tilebox-one">
                    <div class="card-body">
                        <i class="mdi mdi-rss float-right"></i>
                        <h6 class="text-uppercase mt-0">Feedback Month</h6>
                        <h2 class="my-2" id="active-users-count">{{ $total['feedback']['month'] }}</h2>
                        <p class="mb-0 text-muted">
                            <span class="text-nowrap">Total Feedback for this Month</span>
                        </p>
                    </div> <!-- end card-body-->
                </div>
                <!--end card-->
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-12 mt-2">
                            <div class="row">
                                <div class="col-md-4">

                                    <h4 class="header-title">Recent Drug Orders</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of recent drugs ordered by Nello users
                                    </p>

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
                                    <td><label
                                            class="badge {{ $order->payment_confirmed == 1 ? 'badge-success' : 'badge-warning' }}">{{ $order->payment_confirmed == 1 ? 'Paid' : 'Unpaid' }}</label>
                                    </td>
                                    <td>{{ $order->order_ref }}</td>
                                    <td>{{ $order->address1 ?? 'Unavailable' }}</td>
                                    <td>{{ $order->location->name ?? 'Unavailable' }}</td>
                                    <td>{{ $order->city ?? 'Unavailable' }}</td>
                                    <td>{{ $order->state ?? 'Unavailable' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('h:ia F dS, Y') }}</td>
                                    <td><a href="{{ url("/drugs-order/{$order->cart_uuid}/items") }}"
                                           class="btn btn-primary">View Items</a></td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>

                    <div class="col-md-2 offset-md-5 text-center">
                        <a href="{{ url('/drugs-order') }}" class="btn btn-primary btn-rounded btn-block">View All</a>
                    </div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>

    @if(\Illuminate\Support\Facades\Auth::check() &&
            \Illuminate\Support\Facades\Auth::user()->admin_type == "admin")
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-12 mt-2">
                                <div class="row">
                                    <div class="col-md-6">

                                        <h4 class="header-title">Recent Feedbacks</h4>
                                        <p class="text-muted font-14">
                                            Here's a list of recent feedbacks from Nello users
                                        </p>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">

                            <table class="table dataTable w-100">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Experience</th>
                                    <th>Feedback</th>
                                    <th>Phone</th>
                                    <th>Date Added</th>
                                </tr>
                                </thead>


                                <tbody>

                                @foreach($feedbacks as $key => $feedback)
                                    <tr>
                                        <td>{{ ($key + 1) }}</td>
                                        <td>{{ \Illuminate\Support\Str::ucfirst($feedback->experience) }}</td>
                                        <td>{{ $feedback->feedback }}</td>
                                        <td>{{ $feedback->phone }}</td>
                                        <td>{{ \Carbon\Carbon::parse($feedback->created_at)->format('h:ia F dS, Y') }}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>

                        </div>

                        <div class="col-md-2 offset-md-5 text-center">
                            <a href="{{ url('/feedbacks') }}" class="btn btn-primary btn-rounded btn-block">View All</a>
                        </div>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    @endif

@endsection
