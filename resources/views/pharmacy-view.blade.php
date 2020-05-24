@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pharmacy</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
                <h4 class="page-title">Pharmacy View</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card text-center">
                <div class="card-body">

                    <img
                        src="{{ $pharmacy->picture ?: asset('images/pharmacy.png') }}"
                        class="rounded-circle avatar-lg img-thumbnail" alt="profile-image"/>

                    <h4 class="mb-0 mt-2">{{ $pharmacy->name }}</h4>

                    <div class="text-left mt-3">
                        <h4 class="font-13 text-uppercase">About {{ $pharmacy->name }}:</h4>

                        <p class="text-muted mb-2 font-13"><strong>Mobile :</strong><span class="ml-2">{{ $pharmacy->phone }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ml-2 ">{{ $pharmacy->email }}</span></p>

                        <p class="text-muted mb-1 font-13"><strong>Address :</strong> <span class="ml-2">{{ $pharmacy->address ?: 'Unavailable' }}</span></p>
                    </div>

                </div> <!-- end card-body -->
            </div> <!-- end card -->

        </div> <!-- end col-->

        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4 text-uppercase"><i class="uil-store mr-1"></i> Pharmacy Info</h5>

                    <form method="post" action="{{ route('pharmacy-view', ['uuid' => $uuid]) }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" value="{{ old('name', $pharmacy->name) }}" name="name" placeholder="Enter pharmacy name">

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                                           value="{{ old('address', $pharmacy->address) }}" name="address" placeholder="Enter pharmacy address">

                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" value="{{ old('email', $pharmacy->email) }}" name="email" placeholder="Enter pharmacy email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                           value="{{ old('phone', $pharmacy->phone) }}" name="phone" placeholder="Enter pharmacy phone">

                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location">Location</label>

                                    <select class="form-control @error('location') is-invalid @enderror" name="location">
                                        <option value="">Select location</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('location', $pharmacy->location_id) == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('location')
                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="picture">Picture <small>(*optional)</small></label>

                                    <div class="custom-file">
                                        <input type="file"
                                               class="custom-file-input @error('picture') is-invalid @enderror"
                                               name="picture" id="picture-input">
                                        <label class="custom-file-label" for="picture-input">Choose file</label>

                                        @error('picture')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">

                                <div class="form-group mb-3">
                                    <div class="form-check custom-control custom-checkbox">
                                        <input type="checkbox" class="form-check-input custom-control-input @error('is_pick_up_location') is-invalid @enderror"
                                               id="is_pick_up_location" value="1" name="is_pick_up_location"
                                               @if(old('is_pick_up_location', $pharmacy->is_pick_up_location) == 1) checked @endif>
                                        <label class="form-check-label custom-control-label" for="is_pick_up_location">Is Pick up location?</label>

                                        @error('is_pick_up_location')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div> <!-- end row -->

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
