@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Drugs</li>
                    </ol>
                </div>
                <h4 class="page-title">Drugs</h4>
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

                                    <h4 class="header-title">Drugs</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of all drugs on the Nello platform
                                    </p>

                                </div>

                                <div class="col-md-6 offset-md-2">
                                    <div class="row">
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
                                            <label>Category</label>
                                            <select class="form-control" name="category">
                                                <option value="">Select category</option>
                                                @foreach($categories as $cate)
                                                    <option value="{{ $cate->id }}" {{ $cate->id == $category ? 'selected' : '' }}>{{ $cate->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label>Filter by Keyword</label>
                                            <input class="form-control" name="search" value="{{ $search }}"
                                                   placeholder="Enter Keyword"/>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table dataTable w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Dosage Type</th>
                                <th>Prescription</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($drugs as $key => $drug)
                                <tr>
                                    <td>{{ ($key + 1) }}</td>
                                    <td><img src="{{ $drug->image ?? asset('images/drug-placeholder.png') }}" class="img-thumbnail" width="80"/></td>
                                    <td>{{ $drug->name }}</td>
                                    <td>{{ $drug->brand ?: 'Unavailable' }}</td>
                                    <td>{{ $drug->category->name ?: 'Unavailable' }}</td>
                                    <td>{{ $drug->description ?: 'Unavailable' }}</td>
                                    <td>{{ $drug->dosage_type ?: 'Unavailable' }}</td>
                                    <td>{{ $drug->require_prescription == 1 ? 'Required' : 'Not required' }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="{{ route("drug-view", ['uuid' => $drug->uuid]) }}">Edit drug</a>
                                                <button class="dropdown-item status-toggle" data-id="{{ $drug->uuid }}"
                                                        data-status="cancelled">
                                                    Delete drug
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
                        {{ $drugs->links() }}
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

        $("select[name='category']").change(function (e) {

            let category = $(this).val();
            if (category !== '') params.category = category;
            else delete params.category;
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

            successMsg('Delete Drug', "This drug will be deleted, once done it cannot be undone, do you want proceed?",
                'Yes, proceed', 'No, cancel', function ({value}) {

                    if (!value) return;

                    timeout = setTimeout(() => {

                        instance.addToRequestQueue({
                            url: "{{ route('drug-delete') }}",
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
                                    errorMsg('Drug Delete Failed', Array.isArray(data.message) ? serializeMessage(data.message) : data.message, 'Ok');
                                    return false;
                                }

                                successMsg('Drug Delete Successful', data.message);

                                self.closest('tr').fadeOut(600, function () {
                                    $(this).detact();
                                });

                            },
                            ontimeout: () => {
                                swal.hideLoading();
                                errorMsg('Drug Delete Failed', 'Failed to delete this drug at this time as the request timed out', 'Ok');
                            },
                            error: (data, xhr, status, statusText) => {

                                swal.hideLoading();

                                errorMsg('Drug Delete Failed', Array.isArray(data.message) ? serializeMessage(data.message) : data.message, 'Ok');
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
