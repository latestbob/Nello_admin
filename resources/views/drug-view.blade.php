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

                        <p class="text-muted mb-2 font-13"><strong>Category :</strong><span class="ml-2">{{ $drug->category->name ?: 'Unavailable' }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Price :</strong> <span class="ml-2 ">N{{ number_format($drug->price ?: 0) }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Quantity :</strong> <span class="ml-2 ">{{ number_format($drug->quantity ?: 0) }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Vendor:</strong> <span class="ml-2 ">{{ $drug->vendor ?: 'Unavailable' }}</span></p>


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

                                    <select class="form-control @error('category') is-invalid @enderror" id="category" name="category">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $category->id == $drug->category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                              name="description" placeholder="Enter description">{{ old('description', $drug->description)  }}</textarea>

                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="indications">Indications</label>
                                    <textarea class="form-control @error('indications') is-invalid @enderror"
                                              id="indications"
                                              name="indications"
                                              placeholder="Enter indications">{{ old('indications', $drug->indications)  }}</textarea>

                                    @error('indications')
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
                                    <label for="side_effects">Common Side Effects</label>
                                    <textarea class="form-control @error('side_effects') is-invalid @enderror"
                                              id="side_effects"
                                              name="side_effects"
                                              placeholder="Enter common side effects">{{ old('side_effects', $drug->side_effects)  }}</textarea>

                                    @error('side_effects')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contraindications">Contraindications</label>
                                    <textarea class="form-control @error('contraindications') is-invalid @enderror"
                                              id="contraindications"
                                              name="contraindications"
                                              placeholder="Enter contraindications">{{ old('contraindications', $drug->contraindications)  }}</textarea>

                                    @error('contraindications')
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
                                    <label for="dosage_type">Dosage Type</label>
                                    <input type="text" class="form-control @error('dosage_type') is-invalid @enderror" id="dosage_type"
                                           value="{{ old('dosage_type', $drug->dosage_type)  }}" name="dosage_type" placeholder="Enter dosage type">

                                    @error('dosage_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
                                           value="{{ old('price', $drug->price)  }}" name="price" placeholder="Enter price">

                                    @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                                           value="{{ old('quantity', $drug->quantity)  }}" name="quantity" placeholder="Enter quantity">

                                    @error('quantity')
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
                                    <label for="image">Image</label>

                                    <div class="custom-file">
                                        <input type="file" class=" @error('image') is-invalid @enderror"
                                               name="image" id="inputGroupFile04">
                                        

                                        @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                            <div class="form-group">
                                    <label for="category">Vendor(Optional)</label>
                                    <select class="form-control @error('vendor') is-invalid @enderror" id="vendor" name="vendor"required>
                                       
                                        <option value=""> Select Vendor </option>
                                            <option value="Skinns">Skinns</option>
                                            <option value="Famacare">Famacare</option>
                                    </select>

                                    @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                        </div> <!-- end row -->

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input @if(old('prescription', $drug->require_prescription) == 1) checked @endif type="checkbox" class="custom-control-input" id="prescription" name="prescription">
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
