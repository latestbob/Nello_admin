@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Location</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
                <h4 class="page-title">Location View</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">

        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('location-view', ['uuid' => $uuid]) }}">
                        <h5 class="mb-4 text-uppercase"><i class="uil-location mr-1"></i> Location Info</h5>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Location</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" value="{{ old('name', $location->name) }}" name="name" placeholder="Enter location">

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="standard_price">Standard Price</label>
                                    <input type="number"
                                           class="form-control @error('standard_price') is-invalid @enderror" id="standard_price"
                                           value="{{ old('standard_price', $location->standard_price)  }}" name="standard_price" placeholder="Enter standard price">

                                    @error('standard_price')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="same_day_price">Same Day Price</label>
                                    <input type="number"
                                           class="form-control @error('same_day_price') is-invalid @enderror" id="same_day_price"
                                           value="{{ old('same_day_price', $location->same_day_price)  }}" name="same_day_price" placeholder="Enter same day price">

                                    @error('same_day_price')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="next_day_price">Next Day Price</label>
                                    <input type="number"
                                           class="form-control @error('next_day_price') is-invalid @enderror" id="next_day_price"
                                           value="{{ old('next_day_price', $location->next_day_price)  }}" name="next_day_price" placeholder="Enter next day price">

                                    @error('next_day_price')
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
