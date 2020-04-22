@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Drug Order Items</li>
                    </ol>
                </div>
                <h4 class="page-title">Drug Order Items</h4>
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

                                    <h4 class="header-title">Drug Order Items</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of drugs ordered by a Nello user
                                    </p>

                                </div>

                                <div class="col-md-4 offset-md-4 mb-3">
                                    <label>Show entries</label>
                                    <select name="size" class="form-control">
                                        <option value="5" @if($size == '5') selected @endif>5 records</option>
                                        <option value="10" @if($size == '10') selected @endif>10 records</option>
                                        <option value="25" @if($size == '25') selected @endif>25 records</option>
                                        <option value="50" @if($size == '50') selected @endif>50 records</option>
                                        <option value="100" @if($size == '100') selected @endif>100 records</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">

                        <table class="table dataTable w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Drug Name</th>
                                <th>Drug Category</th>
                                <th>Drug Brand</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Date Ordered</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($orderItems as $key => $items)
                                <tr>
                                    <td>{{ ($key + 1) }}</td>
                                    <td><img src="{{ $items->drug->image ?? asset('images/drug-placeholder.png') }}" class="img-thumbnail" width="80"/></td>
                                    <td>{{ $items->drug->name }}</td>
                                    <td>{{ $items->drug->category }}</td>
                                    <td>{{ $items->drug->brand }}</td>
                                    <td>{{ $items->quantity }}</td>
                                    <td>{{ $items->drug->price }}</td>
                                    <td>{{ $items->price }}</td>
                                    <td><label
                                            class="badge {{ $items->status == 'approved' ? 'badge-success' : ($items->status == 'cancelled' ? 'badge-danger' : 'badge-warning') }}">{{ $items->status }}</label>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($items->created_at)->format('h:ia F dS, Y') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <button class="dropdown-item status-toggle" data-id="{{ $items->id }}"
                                                        data-status="approved">Approve
                                                </button>
                                                <button class="dropdown-item status-toggle" data-id="{{ $items->id }}"
                                                        data-status="disapproved">Disapprove
                                                </button>
                                                <button class="dropdown-item status-toggle" data-id="{{ $items->id }}"
                                                        data-status="cancelled">Cancel
                                                </button>
                                                @if(!empty($items->prescription))
                                                    <a class="dropdown-item" href="{{ $items->prescription }}"
                                                       target="_blank">View Prescription</a>
                                                @else
                                                    <button class="dropdown-item add-prescription">Add Prescription</button>
                                                    <input type="file" onchange="uploadPrescription(event.target.files[0], '{{ $items->drug_id }}', '{{ $items->cart_uuid }}')" hidden/>
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
                        {{ $orderItems->links() }}
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

        const instance = NetBridge.getInstance();

        $('.status-toggle').click(function (e) {

            let self = $(this), status = self.data('status'), timeout;

            let title = status === 'approved' ? 'Approve ' : (status === 'disapproved' ? 'Disapprove ' : 'Cancel ');

            successMsg(title + 'Order', "This order will be " + status + ", do you want proceed?",
                'Yes, proceed', 'No, cancel', function ({value}) {

                    if (!value) return;

                    timeout = setTimeout(() => {

                        instance.addToRequestQueue({
                            url: "{{ url('/drugs-order/item/action') }}",
                            method: 'post',
                            timeout: 10000,
                            dataType: 'json',
                            data: {
                                id: parseInt(self.data('id')),
                                status: status,
                                '_token': "{{ csrf_token() }}"
                            },
                            beforeSend: () => {
                                swal.showLoading();
                            },
                            success: (data, status, xhr) => {

                                swal.hideLoading();

                                if (data.status !== true) {
                                    errorMsg(title + 'Failed', Array.isArray(data.message) ? serializeMessage(data.message) : data.message, 'Ok');
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
                                errorMsg(title + 'Failed', 'Failed to ' + type + ' this order at this time as the request timed out', 'Ok');
                            },
                            error: (data, xhr, status, statusText) => {

                                swal.hideLoading();

                                errorMsg(title + 'Failed', Array.isArray(data.message) ? serializeMessage(data.message) : data.message, 'Ok');
                            }
                        });

                        clearTimeout(timeout);
                    }, 500);
                })
        });

        $('.add-prescription').click(function () {
            $(this).siblings("input[type='file']").click();
        });

        const uploadPrescription = (file, id, uuid) => {

            let formData = new FormData(), timeout;
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('file', file);
            formData.append('uuid', uuid);
            formData.append('id', id);

            instance.addToRequestQueue({
                url: "{{ url('/drugs-order/item/add-prescription') }}",
                method: 'POST',
                timeout: 20000,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                data: formData,
                beforeSend: function () {
                    swal.showLoading();
                },
                success: (data, status, xhr) => {

                    swal.hideLoading();

                    if (data.status !== true) {
                        errorMsg('Prescription Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                        return false;
                    }

                    successMsg('Prescription Successful', data.message);

                    timeout = setTimeout(() => {
                        window.location.reload();
                        clearTimeout(timeout);
                    }, 2000);

                },
                ontimeout: () => {
                    swal.hideLoading();
                    errorMsg('Prescription Failed', 'Failed to ' + type + ' this order at this time as the request timed out', 'Ok');
                },
                error: (data, xhr, status, statusText) => {

                    swal.hideLoading();

                    errorMsg('Prescription Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                }
            });
        }

    </script>
@endsection

@section('css')
    <!-- Datatables css -->
    <link href="{{ asset('css/vendor/select.bootstrap4.css') }}" rel="stylesheet" type="text/css"/>
@endsection
