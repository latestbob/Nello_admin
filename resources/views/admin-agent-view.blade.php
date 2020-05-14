@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Agent</li>
                    </ol>
                </div>
                <h4 class="page-title">Agent</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card text-center">
                <div class="card-body">

                    <img
                        src="{{ $agent->picture }}"
                        class="rounded-circle avatar-lg img-thumbnail" alt="profile-image"/>

                    <h4 class="mb-0 mt-2">{{ $agent->name }}</h4>

                    <div class="text-left mt-3">
                        <h4 class="font-13 text-uppercase">About Agent:</h4>

                        <p class="text-muted mb-2 font-13"><strong>Mobile :</strong><span class="ml-2">{{ $agent->phone ?: 'Unknown' }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ml-2 ">{{ $agent->email ?: 'Unknown' }}</span></p>

                        <p class="text-muted mb-1 font-13"><strong>Address :</strong> <span class="ml-2">{{ $agent->address ?: 'Unknown' }}</span></p>

                        <p class="text-muted mb-1 font-13"><strong>Assigned Location :</strong> <span class="ml-2">{{ $agent->location->name ?: 'Unassigned' }}</span></p>
                    </div>

                </div> <!-- end card-body -->
            </div> <!-- end card -->

        </div> <!-- end col-->

        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('agent-view', ['uuid' => $agent->uuid]) }}" enctype="multipart/form-data">
                        <h5 class="mb-4 text-uppercase"><i class="uil-user mr-1"></i> Agent Info</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           value="{{ old('name', $agent->name) }}" name="name" placeholder="Enter name"/>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           value="{{ old('email', $agent->email) }}" name="email" placeholder="Enter email"/>

                                    @error('email')
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
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           value="{{ old('phone', $agent->phone) }}" name="phone" placeholder="Enter phone"/>

                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div> <!-- end col -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <select class="form-control @error('location') is-invalid @enderror" id="location" name="location">
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}"
                                                {{ $location->id == old('location', $agent->location->id) ? 'selected' : '' }}>{{ $location->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('location')
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
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                           id="address"
                                           value="{{ old('address', $agent->address) }}" name="address" placeholder="Enter address"/>

                                    @error('address')
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
                                    <label for="picture">Picture</label>

                                    <div class="custom-file">
                                        <input type="file"
                                               class="custom-file-input @error('picture') is-invalid @enderror"
                                               name="picture" id="inputGroupFile04">
                                        <label class="custom-file-label" for="inputGroupFile04">Choose file</label>

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
