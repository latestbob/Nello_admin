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
                                                <option value="Issues" @if($experience == 'Issues') selected @endif>
                                                    Issues
                                                </option>
                                                <option value="Complaints"
                                                        @if($experience == 'Complaints') selected @endif>Complaints
                                                </option>
                                                <option value="Enquiry" @if($experience == 'Enquiry') selected @endif>Enquiry
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
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
                                
                                <th>Feedback Type</th>
                                <th>Priority_Level</th>
                                <th>Resolution Time</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Dependencies</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                            <tbody>

                                @foreach($feedbacks as $feed)

                            <tr>
                                <td>{{$feed->type}}</td>
                                <td>{{$feed->priority}}</td>
                                <td>{{$feed->resolution_time}}</td>
                                <td>{{$feed->name}}</td>
                                <td>{{$feed->email}}</td>
                                <td>{{$feed->message}}</td>
                                <td>{{$feed->dependencies}}</td>
                                <td>

                                @if($feed->resolved == "false")

                                <p class="badge badge-warning text-dark text-center">Pending</p>

                                @else
                                <p class="badge badge-success text-light text-center">Resolved</p>



                                @endif

                                </td>
                                <td>
                                    <form action="{{route('updatefeedback',$feed->id)}}"method="POST">
                                        @csrf 

                                        {{method_field("PUT")}}

                                        <button class="btn  btn-sm btn-success text-center">Mark as Resolved</button>
                                    </form>
                                </td>

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
            let search = $("input[name='search']").val();
            if (search !== '') params.search = search;
            else delete params.search;
            delete params.page;
            window.location.href = (window.location.protocol + "//" + window.location.host + window.location.pathname + "?" + serialize(params));

        });
    </script>
@endsection
