@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item">Drugs</li>
                        <li class="breadcrumb-item active">Import</li>
                    </ol>
                </div>
                <h4 class="page-title">Drugs Import</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-6 offset-lg-3 col-sm-12">
            <div class="card">
                <div class="card-body">

                    <form method="post" class="row" id="drug-import-form">

                        <div class="custom-file">
                            <input type="file" accept=".xlsx" class="custom-file-input form-control" name="drugs_file">
                            <label class="custom-file-label">Choose file (excel)...</label>
                        </div>

                        <button class="btn btn-primary btn-md mt-3" type="submit">Upload</button>
                    </form>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>

@endsection

@section('js')
    <script src="{{ asset('js/net-bridge/net-bridge.js') }}" type="application/javascript"></script>

    <script type="application/javascript">

        const instance = NetBridge.getInstance();

        $('#drug-import-form').submit(function (e) {

            e.preventDefault();

            let formData = new FormData($(this)[0]);
            formData.append('_token', "{{ csrf_token() }}");

            instance.addToRequestQueue({
                url: "{{ route('drug-import') }}",
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
                        errorMsg('Import Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                        return false;
                    }

                    successMsg('Import Successful', data.message);
                    $(this)[0].reset();
                },
                ontimeout: () => {
                    swal.hideLoading();
                    errorMsg('Import Failed', 'Sorry, the request failed at this time as it timed out', 'Ok');
                },
                error: (data, xhr, status, statusText) => {

                    swal.hideLoading();

                    errorMsg('Import Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                }
            });

        });

    </script>
@endsection

@section('css')
    <!-- Datatables css -->
    <link href="{{ asset('css/vendor/select.bootstrap4.css') }}" rel="stylesheet" type="text/css"/>
@endsection
