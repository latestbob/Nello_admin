@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Paid Transactions</li>
                    </ol>
                </div>
                <h4 class="page-title">Transactions</h4>
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
                                <div class="col-md-4">

                                    <h4 class="header-title">Completed Transactions</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of completed transactions by Nello users
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

                                               

                                              
                                            </div>

                                        </div>

                                        <div class="col-md-12 mb-3">
                                          
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
                                <th>Amount</th>
                                <th>User Email</th>
                                <th>Gateway Reference</th>
                                <th>Payment Status</th>
                                <th>System Reference</th>
                                <th>Type</th>
                                <th>Created_At</th>
                                
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($transactions as $key => $tranx)
                                <tr>
                                    <td>{{ ($key + 1) }}</td>
                                    <td>{{ $tranx->amount }} </td>
                                    <td>{{ $tranx-> email }}</td>
                                    <td>{{ $tranx->gateway_reference }}</td>
                                    <td><label
                                            class="badge badge-success">Paid</label>
                                    </td>
                                    <td>{{ $tranx->system_reference }}</td>
                                    <td>{{ $tranx->reason }}</td>
    
                                    <td>{{ \Carbon\Carbon::parse($tranx->created_at)->format('h:ia F dS, Y') }}</td>
                                   
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>

                    <div class="table-responsive mt-3">
                        {{ $transactions->links() }}
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

      

    

        function getDateRange(event) {

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
