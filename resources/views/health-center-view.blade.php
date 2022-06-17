@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Health Center</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
                <h4 class="page-title">Health Center View</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card text-center">
                <div class="card-body">

                    <img
                        src="{{ $healthCenter->logo ?: asset('images/health-center.jpg') }}"
                        class="rounded-circle avatar-lg img-thumbnail" alt="profile-image"/>

                    <h4 class="mb-0 mt-2">{{ $healthCenter->name }}</h4>

                    <div class="text-left mt-3">
                        <h4 class="font-13 text-uppercase">About Center:</h4>

                        <p class="text-muted mb-2 font-13"><strong>Center Type :</strong><span class="ml-2">{{ $healthCenter->center_type ?: 'Unavailable' }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Mobile :</strong><span class="ml-2">{{ $healthCenter->phone }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ml-2 ">{{ $healthCenter->email }}</span></p>

                        <p class="text-muted mb-1 font-13"><strong>First Address:</strong> <span class="ml-2">{{ $healthCenter->address1 ?: 'Unavailable' }}</span></p>

                        <p class="text-muted mb-1 font-13"><strong>Second Address:</strong> <span class="ml-2">{{ $healthCenter->address2 ?: 'Unavailable' }}</span></p>
                    </div>

                </div> <!-- end card-body -->
            </div> <!-- end card -->

        </div> <!-- end col-->

        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('health-center-view', ['uuid' => $uuid]) }}" enctype="multipart/form-data">
                        <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Center Info</h5>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                           value="{{ old('name', $healthCenter->name) }}" name="name" placeholder="Enter name">

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                           value="{{ old('phone', $healthCenter->phone)  }}" name="phone" placeholder="Enter phone">

                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                           value="{{ old('email', $healthCenter->email) }}" name="email" placeholder="Enter email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state">State</label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror" id="state"
                                           value="{{ old('state', $healthCenter->state) }}" name="state" placeholder="Enter state">

                                    @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                                           value="{{ old('city', $healthCenter->city) }}" name="city" placeholder="Enter city">

                                    @error('city')
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
                                    <label for="address1">First Address</label>
                                    <input type="text" class="form-control @error('address1') is-invalid @enderror" id="address1"
                                           value="{{ old('address1', $healthCenter->address1) }}" name="address1" placeholder="Enter first address">

                                    @error('address1')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div> <!-- end col -->

                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address2">Second Address</label>
                                    <input type="text" class="form-control @error('address2') is-invalid @enderror" id="address2"
                                           value="{{ old('address2', $healthCenter->address2) }}" name="address2" placeholder="Enter second address">

                                    @error('address2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div> end col -->
                        </div> 

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo">Logo <small>(*optional)</small></label>

                                    <div class="custom-file">
                                        <input type="file"
                                               class="custom-file-input @error('logo') is-invalid @enderror"
                                               name="logo" id="logo-input">
                                        <label class="custom-file-label" for="logo-input">Choose file</label>

                                        @error('logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="aos">Consulting Fee</label>
                                    <input type="text" class="form-control " id="consultingfee"
                                           value="{{ old('fee', $healthCenter->fee) }}" name="fee" placeholder="Enter Consulting">

                                    

                                </div>
                            </div>
                        </div> <!-- end row -->

                        @csrf

                        <div class="col-md-4  m-auto text-center mt-2">
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
