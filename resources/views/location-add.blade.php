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
                        <li class="breadcrumb-item active">Add</li>
                    </ol>
                </div>
                <h4 class="page-title">Location Add</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">

        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4 text-uppercase"><i class="uil-location mr-1"></i> Location Info</h5>
                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#add-delivery-location" data-toggle="tab" aria-expanded="false"
                               class="nav-link {{ old('action', 'delivery') == 'delivery' ? 'active' : '' }}">
                                Delivery Location
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#add-pickup-location" data-toggle="tab" aria-expanded="true"
                               class="nav-link {{ old('action') == 'pickup' ? 'active' : '' }}">
                                Pickup Location
                            </a>
                        </li>
                    </ul> <!-- end nav-->
                    <div class="tab-content">
                        <div class="tab-pane {{ old('action', 'delivery') == 'delivery' ? 'show active' : '' }}" id="add-delivery-location">
                            <form method="post" action="{{ route('location-add') }}">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Location</label>
                                            <input type="text" class="form-control @if(old('action') == 'delivery') @error('name') is-invalid @enderror @endif"
                                                   id="name"
                                                   value="{{ old('name') }}" name="name" placeholder="Enter location">

                                            @if(old('action') == 'delivery')
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            @endif

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price">Price</label>
                                            <input type="number"
                                                   class="form-control @error('price') is-invalid @enderror" id="price"
                                                   value="{{ old('price')  }}" name="price" placeholder="Enter price">

                                            @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>
                                </div>

                                @csrf

                                <input type="hidden" name="action" value="delivery">

                                <div class="col-md-2 offset-md-5 text-center mt-2">
                                    <button type="submit" class="btn btn-success btn-block btn-rounded"><i
                                            class="mdi mdi-content-save"></i> Save
                                    </button>
                                </div>
                            </form>
                        </div> <!-- end preview-->

                        <div class="tab-pane {{ old('action') == 'pickup' ? 'show active' : '' }}" id="add-pickup-location">
                            <form method="post" action="{{ route('location-add') }}">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Location</label>
                                            <input type="text" class="form-control @if(old('action') == 'pickup') @error('name') is-invalid @enderror @endif"
                                                   id="name"
                                                   value="{{ old('name') }}" name="name" placeholder="Enter location and pickup time">

                                            @if(old('action') == 'pickup')
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                @csrf

                                <input type="hidden" name="action" value="pickup">

                                <div class="col-md-2 offset-md-5 text-center mt-2">
                                    <button type="submit" class="btn btn-success btn-block btn-rounded"><i
                                            class="mdi mdi-content-save"></i> Save
                                    </button>
                                </div>
                            </form>
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->
                </div> <!-- end card body -->
            </div> <!-- end card -->
        </div> <!-- end col -->
    </div>

@endsection
