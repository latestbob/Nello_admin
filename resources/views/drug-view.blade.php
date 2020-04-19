@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Drugs</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
                <h4 class="page-title">Drug</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card text-center">
                <div class="card-body">

                    <img src="{{ $drug->image ?? asset('images/drug-placeholder.png') }}"
                        class="rounded-circle avatar-lg img-thumbnail" alt="drug-image"/>

                    <h4 class="mb-0 mt-2">{{ $drug->name }}</h4>

                    <div class="text-left mt-3">
                        <h4 class="font-13 text-uppercase">About Drug:</h4>

                        <p class="text-muted mb-2 font-13">
                            <strong>Full Name :</strong> <span class="ml-2">{{ $drug->name }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Brand :</strong><span class="ml-2">{{ $drug->brand ?: 'Unavailable' }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Category :</strong><span class="ml-2">{{ $drug->category ?: 'Unavailable' }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Price :</strong> <span class="ml-2 ">N{{ number_format($drug->price ?: 0) }}</span></p>

                    </div>

                </div> <!-- end card-body -->
            </div> <!-- end card -->

        </div> <!-- end col-->

        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('drug-view', ['uuid' => $uuid]) }}" enctype="multipart/form-data">
                        <h5 class="mb-4 text-uppercase"><i class="uil-medical mr-1"></i> Drug Info</h5>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                           value="{{ old('name', $drug->name) }}" name="name" placeholder="Enter name">

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="brand">Brand</label>
                                    <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand"
                                           value="{{ old('brand', $drug->brand) }}" name="brand" placeholder="Enter brand">

                                    @error('brand')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <input type="text" class="form-control @error('category') is-invalid @enderror" id="category"
                                           value="{{ old('category', $drug->category) }}" name="category" placeholder="Enter category">

                                    @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="tel" class="form-control @error('price') is-invalid @enderror" id="price"
                                           value="{{ old('price', $drug->price)  }}" name="price" placeholder="Enter price">

                                    @error('price')
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
                                    <label for="image">Image</label>

                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('image') is-invalid @enderror"
                                               name="image" id="inputGroupFile04">
                                        <label class="custom-file-label" for="inputGroupFile04">Choose file</label>

                                        @error('image')
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
