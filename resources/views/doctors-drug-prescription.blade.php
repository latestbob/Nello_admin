@extends('layouts.app-prescription')

@section('content')

    <div class="row">
        <div class="col-md-8 offset-md-2">
            @if(empty($error))
            <div class="card  text-center">
                <div class="card-body">

                    <img src="{{ asset('images/logo.png') }}" class="rounded-circle avatar-lg img-thumbnail" alt="drug-image"/>

                    <h4 class="mb-0 mt-3">Nello Doctors Prescription <small>Powered by Famacare</small></h4>

                    <div class="table-responsive mt-5">

                        <table class="table dataTable w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Picture</th>
                                <th>Drug</th>
                                <th>Dosage</th>
                                <th>Doctors Note</th>
                                <th>Prescribed By</th>
                                <th>Date Prescribed</th>
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($prescriptions as $key => $prescription)
                                <tr>
                                    <td>{{ ($key + 1) }}</td>
                                    <td><img src="{{ $prescription->drug->image ?: asset('images/drug-placeholder.png') }}" class="img-thumbnail" width="80"/></td>
                                    <td>{{ $prescription->drug->name }}</td>
                                    <td>{{ $prescription->dosage ?: 'Unavailable' }}</td>
                                    <td>{{ $prescription->note ?: 'Unavailable' }}</td>
                                    <td>Dr. {{ $prescription->doctor->firstname ?: 'Unavailable' }} {{ $prescription->doctor->lastname ?: 'Unavailable' }}</td>
                                    <td>{{ $prescription->created_at ? \Carbon\Carbon::parse($prescription->created_at)->format('F dS, Y') : 'Unavailable' }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
            @else
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Prescription Error!</h4>
                    <p>{{ $error }}</p>
                </div>
            @endif
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



        const instance = NetBridge.getInstance();

        $('.status-toggle').click(function (e) {

            let self = $(this), uuid = self.data('id'), timeout;

            successMsg('Delete Rider', "This rider will be deleted, once done it cannot be undone, do you want proceed?",
                'Yes, proceed', 'No, cancel', function ({value}) {

                    if (!value) return;

                    timeout = setTimeout(() => {

                        instance.addToRequestQueue({
                            url: "{{ route('rider-delete') }}",
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
                                    errorMsg('Rider Delete Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                                    return false;
                                }

                                successMsg('Rider Delete Successful', data.message);

                                self.closest('tr').fadeOut(600, function () {
                                    $(this).detact();
                                });

                            },
                            ontimeout: () => {
                                swal.hideLoading();
                                errorMsg('Rider Delete Failed', 'Failed to delete this rider at this time as the request timed out', 'Ok');
                            },
                            error: (data, xhr, status, statusText) => {

                                swal.hideLoading();

                                errorMsg('Rider Delete Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                            }
                        });

                        clearTimeout(timeout);
                    }, 500);
                })
        });

    </script>
@endsection

@section('css')
    <!-- Datatables css -->
    <link href="{{ asset('css/vendor/select.bootstrap4.css') }}" rel="stylesheet" type="text/css"/>
@endsection
