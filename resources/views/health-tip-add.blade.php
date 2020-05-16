@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Health Tip</a></li>
                        <li class="breadcrumb-item active">Add</li>
                    </ol>
                </div>
                <h4 class="page-title">Health Tip Add</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">

        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('health-tip-add') }}" enctype="multipart/form-data">
                        <h5 class="mb-4 text-uppercase"><i class="uil-book-medical mr-1"></i> Health Tip</h5>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title"
                                           value="{{ old('title') }}" name="title" placeholder="Enter title"/>

                                    @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="summernote-basic">Body</label>
                                    <textarea id="summernote-basic" class="form-control @error('body') is-invalid @enderror"
                                              name="body" placeholder="Enter body">{{ old('body') }}</textarea>

                                    @error('body')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div> <!-- end col -->

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="text" class="form-control @error('date') is-invalid @enderror"
                                           id="date" data-toggle="date-picker" data-format="dd-mm-yyyy" value="{{ old('date') }}" name="date" placeholder="Enter date"/>

                                    @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div> <!-- end col -->
                        </div>

                        @csrf

                        <div class="col-md-2 offset-md-5 text-center mt-2">
                            <button type="submit" class="btn btn-success btn-block btn-rounded"><i
                                    class="mdi mdi-content-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div> <!-- end card body -->
            </div> <!-- end card -->
        </div> <!-- end col -->
    </div>

@endsection

@section('css')
    <link href="{{ asset('css/vendor/summernote-bs4.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js')
    <script src="{{ asset('js/vendor/summernote-bs4.min.js') }}"></script>
    <!-- Summernote demo -->
    <script src="{{ asset('js/pages/demo.summernote.js') }}"></script>
@endsection
