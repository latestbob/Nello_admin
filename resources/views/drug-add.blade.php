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
                        <li class="breadcrumb-item active">Add</li>
                    </ol>
                </div>
                <h4 class="page-title">Drug Add</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">

        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('drug-add') }}" enctype="multipart/form-data">
                        <h5 class="mb-4 text-uppercase"><i class="uil-medical mr-1"></i> Drug Info</h5>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           value="{{ old('name') }}" name="name" placeholder="Enter name">

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
                                    <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                           id="brand"
                                           value="{{ old('brand') }}" name="brand" placeholder="Enter brand">

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
                                    <select class="form-control @error('category') is-invalid @enderror" id="category" name="category">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $category->id == old('category') ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>

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
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description"
                                              name="description"
                                              placeholder="Enter description">{{ old('description')  }}</textarea>

                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dosage_type">Dosage Type</label>
                                    <input type="text" class="form-control @error('dosage_type') is-invalid @enderror"
                                           id="dosage_type"
                                           value="{{ old('dosage_type')  }}" name="dosage_type"
                                           placeholder="Enter dosage type">

                                    @error('dosage_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                           id="price"
                                           value="{{ old('price')  }}" name="price" placeholder="Enter price">

                                    @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                           id="quantity"
                                           value="{{ old('quantity')  }}" name="quantity" placeholder="Enter quantity">

                                    @error('quantity')
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
                                        <input type="file"
                                               class="custom-file-input @error('image') is-invalid @enderror"
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

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="prescription"
                                               name="prescription" @if(old('prescription') == 1) checked @endif>
                                        <label class="custom-control-label" for="prescription">Require prescription?</label>
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
