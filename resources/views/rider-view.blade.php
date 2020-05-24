@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Rider</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
                <h4 class="page-title">Rider View</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card text-center">
                <div class="card-body">

                    <img
                        src="{{ $rider->image ?: asset('images/rider.png') }}"
                        class="rounded-circle avatar-lg img-thumbnail" alt="profile-image"/>

                    <h4 class="mb-0 mt-2">{{ $rider->lastname }} {{ $rider->firstname }}</h4>

                    <div class="text-left mt-3">
                        <h4 class="font-13 text-uppercase">About {{ $rider->firstname }}:</h4>

                        <p class="text-muted mb-2 font-13">
                            <strong>Full Name :</strong> <span class="ml-2">{{ $rider->lastname }} {{ $rider->firstname }} {{ $rider->middlename }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Specialization :</strong><span class="ml-2">{{ $rider->aos ?: 'Unavailable' }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Mobile :</strong><span class="ml-2">{{ $rider->phone }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ml-2 ">{{ $rider->email }}</span></p>

                        <p class="text-muted mb-1 font-13"><strong>Address :</strong> <span class="ml-2">{{ $rider->address ?: 'Unavailable' }}</span></p>

                        <p class="text-muted mb-1 font-13"><strong>Assigned Location :</strong> <span class="ml-2">{{ $rider->location->name ?? 'Unavailable' }}</span></p>
                    </div>

                </div> <!-- end card-body -->
            </div> <!-- end card -->

        </div> <!-- end col-->

        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('rider-view', ['uuid' => $uuid]) }}">
                        <h5 class="mb-4 text-uppercase"><i class="mdi mdi-car mr-1"></i> Personal Info</h5>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="firstname">First Name</label>
                                    <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname"
                                           value="{{ old('firstname', $rider->firstname) }}" name="firstname" placeholder="Enter first name">

                                    @error('firstname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="middlename">Middle Name</label>
                                    <input type="text" class="form-control @error('middlename') is-invalid @enderror" id="middlename"
                                           value="{{ old('middlename', $rider->middlename) }}" name="middlename" placeholder="Enter middle name">

                                    @error('middlename')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname"
                                           value="{{ old('lastname', $rider->lastname) }}" name="lastname" placeholder="Enter last name">

                                    @error('lastname')
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
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                           value="{{ old('phone', $rider->phone)  }}" name="phone" placeholder="Enter phone">

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
                                           value="{{ old('email', $rider->email) }}" name="email" placeholder="Enter email">

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
                                    <label for="gender">Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" name="gender" id="gender">
                                        <option value="Male" @if(old('gender', $rider->gender) == "Male") selected @endif>Male</option>
                                        <option value="Female" @if(old('gender', $rider->gender) == "Female") selected @endif>Female</option>
                                    </select>

                                    @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dob">Date Of Birth</label>
                                    <input type="text" class="form-control @error('dob') is-invalid @enderror date" id="dob"
                                           value="{{ old('dob', $rider->dob) }}" data-toggle="date-picker" data-format="yyyy-mm-dd"
                                           data-single-date-picker="true" name="dob" placeholder="Enter dob">

                                    @error('dob')
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
                                           value="{{ old('state', $rider->state) }}" name="state" placeholder="Enter state">

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
                                           value="{{ old('city', $rider->city) }}" name="city" placeholder="Enter city">

                                    @error('city')
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
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                                           value="{{ old('address', $rider->address) }}" name="address" placeholder="Enter address">

                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location">Assign Location</label>

                                    <select class="form-control @error('location') is-invalid @enderror" name="location">
                                        <option value="">Select location</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('location', $rider->location_id) == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('location')
                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                    @enderror

                                </div>
                            </div><!-- end col -->
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="sponsor">Sponsor</label>
                                    <input type="text" class="form-control @error('sponsor') is-invalid @enderror" id="sponsor"
                                           value="{{ old('sponsor', $rider->sponsor) }}" name="sponsor" placeholder="Enter sponsor">

                                    @error('sponsor')
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
                                    <label for="religion">Religion</label>
                                    <input type="text" class="form-control @error('religion') is-invalid @enderror" id="religion"
                                           value="{{ old('religion', $rider->religion) }}" name="religion" placeholder="Enter religion">

                                    @error('religion')
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
                                    <label for="height">Height</label>
                                    <input type="text" class="form-control @error('height') is-invalid @enderror" id="height"
                                           value="{{ old('height', $rider->height) }}" name="height" placeholder="Enter height">

                                    @error('height')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="weight">Weight</label>
                                    <input type="text" class="form-control @error('weight') is-invalid @enderror" id="weight"
                                           value="{{ old('weight', $rider->weight) }}" name="weight" placeholder="Enter weight">

                                    @error('weight')
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
                                    <label for="aos">Specialization</label>
                                    <input type="text" class="form-control @error('aos') is-invalid @enderror" id="aos"
                                           value="{{ old('aos', $rider->aos) }}" name="aos" placeholder="Enter specialization">

                                    @error('aos')
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
