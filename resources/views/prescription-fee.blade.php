@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Prescription Fee</li>
                    </ol>
                </div>
                <h4 class="page-title">Prescription Fee</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">

        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4 text-uppercase">
                        <i class="uil-location mr-1"></i>
                        Prescription Fee Info
                    </h5>
                    <form method="post" action="{{ route('prescription-fee') }}">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="fee">Fee</label>
                                    <input type="number" class="form-control @error('fee') is-invalid @enderror"
                                           id="fee" value="{{ old('fee', $fee->fee ?? '') }}" name="fee" placeholder="Enter Fee">

                                    @error('fee')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
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

@section('js')
    <script type="application/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endsection
