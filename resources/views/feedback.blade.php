@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Feedbacks</li>
                    </ol>
                </div>
                <h4 class="page-title">Feedbacks</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-4 col-lg-12">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="mdi mdi-emoticon-happy float-right"></i>
                    <h6 class="text-uppercase mt-0">Happy</h6>
                    <h2 class="my-2" id="active-users-count">{{ $total['happy'] }}</h2>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap">Total Happy Experience</span>
                    </p>
                </div> <!-- end card-body-->
            </div>
            <!--end card-->
        </div>
        <div class="col-xl-4 col-lg-12">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="mdi mdi-emoticon-neutral float-right"></i>
                    <h6 class="text-uppercase mt-0">Neutral</h6>
                    <h2 class="my-2" id="active-users-count">{{ $total['neutral'] }}</h2>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap">Total Neutral Experience</span>
                    </p>
                </div> <!-- end card-body-->
            </div>
            <!--end card-->
        </div>
        <div class="col-xl-4 col-lg-12">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="mdi mdi-emoticon-sad float-right"></i>
                    <h6 class="text-uppercase mt-0">Sad</h6>
                    <h2 class="my-2" id="active-users-count">{{ $total['sad'] }}</h2>
                    <p class="mb-0 text-muted">
                        <span class="text-nowrap">Total Sad Experience</span>
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
                                <div class="col-md-6">

                                    <h4 class="header-title">Feedbacks</h4>
                                    <p class="text-muted font-14">
                                        Here's a list of feedbacks from Nello users
                                    </p>

                                </div>
                                <div class="col-md-6">

                                    <form method="get" id="phone-filter" class="row">

                                        <div class="col-md-4 mb-3">
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

                                        <div class="col-md-4 mb-3">
                                            <label>Filter by Experience</label>
                                            <select class="form-control" name="experience">
                                                <option value="">Select experience</option>
                                                <option value="happy" @if($experience == 'happy') selected @endif>
                                                    Happy
                                                </option>
                                                <option value="neutral"
                                                        @if($experience == 'neutral') selected @endif>Neutral
                                                </option>
                                                <option value="sad" @if($experience == 'sad') selected @endif>Sad
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label>Filter by Phone</label>
                                            <input class="form-control" name="phone" value="{{ $phone }}"
                                                   placeholder="Enter Phone"/>
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
                                <th>Experience</th>
                                <th>Feedback</th>
                                <th>Phone</th>
                                <th>Date Added</th>
                            </tr>
                            </thead>


                            <tbody>

                            @foreach($feedbacks as $feedback)
                                <tr>
                                    <td>{{ \Illuminate\Support\Str::ucfirst($feedback->experience) }}</td>
                                    <td>{{ $feedback->feedback }}</td>
                                    <td>{{ $feedback->phone }}</td>
                                    <td>{{ \Carbon\Carbon::parse($feedback->created_at)->format('h:ia F dS, Y') }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>

                    <div class="table-responsive mt-3">
                        {{ $feedbacks->links() }}
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

        $("select[name='experience']").change(function (e) {

            let experience = $(this).val();
            if (experience !== '') params.experience = experience;
            else delete params.experience;
            delete params.page;
            window.location.href = (window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + serialize(params));

        });

        $("form[id='phone-filter']").submit(function (e) {
            e.preventDefault();
            let phone = $("input[name='phone']").val();
            if (phone !== '') params.phone = phone;
            else delete params.phone;
            delete params.page;
            window.location.href = (window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + serialize(params));

        });
    </script>
@endsection

@section('css')
    <!-- Datatables css -->
    <link href="{{ asset('css/vendor/select.bootstrap4.css') }}" rel="stylesheet" type="text/css"/>
@endsection
