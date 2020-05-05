@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Point Rule</li>
                    </ol>
                </div>
                <h4 class="page-title">Point Rule</h4>
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
                        Point Rule Info
                    </h5>
                    <form method="post" action="{{ route('point-rule') }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_point_per_day" data-toggle="tooltip" data-placement="right" title="Number of points customer can earn per day">Max Point Per Day</label>
                                    <input type="number"
                                           class="form-control @error('max_point_per_day') is-invalid @enderror"
                                           id="max_point_per_day" value="{{ old('max_point_per_day', $rules->max_point_per_day ?? '') }}"
                                           name="max_point_per_day" placeholder="Enter Max Point Per Day">

                                    @error('max_point_per_day')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="earn_point_amount" data-toggle="tooltip" data-placement="right" title="Amount customer has to spend to earn a point">Earn Point Amount</label>
                                    <input type="number"
                                           class="form-control @error('earn_point_amount') is-invalid @enderror"
                                           id="earn_point_amount" value="{{ old('earn_point_amount', $rules->earn_point_amount ?? '') }}"
                                           name="earn_point_amount" placeholder="Enter Earn Point Amount">

                                    @error('earn_point_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="point_value" data-toggle="tooltip" data-placement="right" title="Value for each point customer earns">Point Value</label>
                                    <input type="number" class="form-control @error('point_value') is-invalid @enderror"
                                           id="point_value" value="{{ old('point_value', $rules->point_value ?? '') }}" name="point_value"
                                           placeholder="Enter Point Value">

                                    @error('point_value')
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
