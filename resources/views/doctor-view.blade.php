@extends('layouts.dashboard')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Doctor</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
                <h4 class="page-title">Doctor View</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card text-center">
                <div class="card-body">

                    <img
                        src="{{ $doctor->image ?: ($doctor->gender == 'Male' ? asset('images/male_doc.png') : ($doctor->gender == 'Female' ? asset('images/female_doc.png') : asset('images/neutral_doc.png'))) }}"
                        class="rounded-circle avatar-lg img-thumbnail" alt="profile-image"/>

                    <h4 class="mb-0 mt-2">{{ $doctor->lastname }} {{ $doctor->firstname }}</h4>

                    <div class="text-left mt-3">
                        <h4 class="font-13 text-uppercase">About {{ $doctor->firstname }}:</h4>

                        <p class="text-muted mb-2 font-13">
                            <strong>Full Name :</strong> <span class="ml-2">{{ $doctor->title }}. {{ $doctor->lastname }} {{ $doctor->firstname }} {{ $doctor->middlename }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Hospital :</strong><span class="ml-2">{{ $doctor->hospital ?: 'Unavailable' }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Specialization :</strong><span class="ml-2">{{ $doctor->aos ?: 'Unavailable' }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Mobile :</strong><span class="ml-2">{{ $doctor->phone }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ml-2 ">{{ $doctor->email }}</span></p>

                        <p class="text-muted mb-1 font-13"><strong>Address :</strong> <span class="ml-2">{{ $doctor->address ?: 'Unavailable' }}</span></p>
                    </div>

                </div> <!-- end card-body -->
            </div> <!-- end card -->

        </div> <!-- end col-->

        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('doctor-view', ['uuid' => $uuid]) }}" enctype="multipart/form-data">
                        <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Personal Info</h5>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                           value="{{ old('title', $doctor->title) }}" name="title" placeholder="Enter title">

                                    @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                        </div>
                            <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="firstname">First Name</label>
                                    <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname"
                                           value="{{ old('firstname', $doctor->firstname) }}" name="firstname" placeholder="Enter first name">

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
                                           value="{{ old('middlename', $doctor->middlename) }}" name="middlename" placeholder="Enter middle name">

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
                                           value="{{ old('lastname', $doctor->lastname) }}" name="lastname" placeholder="Enter last name">

                                    @error('lastname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <div class="row">
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                           value="{{ old('phone', $doctor->phone)  }}" name="phone" placeholder="Enter phone">

                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div> -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                           value="{{ old('email', $doctor->email) }}" name="email" placeholder="Enter email">

                                    @error('email')
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
                                    <label for="about">About</label>
                                    <textarea class="form-control @error('about') is-invalid @enderror" rows="3"
                                              id="about" name="about" placeholder="Enter about">{{ old('about', $doctor->about)  }}</textarea>

                                    @error('about')
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
                                    <label for="gender">Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" name="gender" id="gender">
                                        <option value="Male" @if(old('gender', $doctor->gender) == "Male") selected @endif>Male</option>
                                        <option value="Female" @if(old('gender', $doctor->gender) == "Female") selected @endif>Female</option>
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
                                           value="{{ old('dob', $doctor->dob) }}" data-toggle="date-picker" data-format="yyyy-mm-dd"
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
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="state">State</label>
                                    <!-- <input type="text" class="form-control @error('state') is-invalid @enderror" id="state"
                                           value="{{ old('state') }}" name="state" placeholder="Enter state"> -->

                                           <select name="state" class="form-control @error('state') is-invalid @enderror" id="state">
                                               <option value="">Select State</option>

                                               @foreach($states as $state)
                                                    <option value="{{$state['id']}}">{{$state['name']}}</option>
                                               @endforeach
                                           </select>

                                    @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city">L.G.A</label>
                                    <!-- <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                                           value="{{ old('city') }}" name="city" placeholder="Enter city"> -->

                                           <select name="city"class="form-control @error('city') is-invalid @enderror" id="city" >
                                               <option value="">Select LGA</option>
                                           </select>

                                    @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                                           value="{{ old('address', $doctor->address) }}" name="address" placeholder="Enter address">

                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="hospital">Hospital</label>
                                    <input type="text" class="form-control @error('hospital') is-invalid @enderror" id="hospital"
                                           value="{{ old('hospital', $doctor->hospital) }}" name="hospital" placeholder="Enter hospital">

                                    @error('hospital')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sponsor">Sponsor</label>
                                    <input type="text" class="form-control @error('sponsor') is-invalid @enderror" id="sponsor"
                                           value="{{ old('sponsor', $doctor->sponsor) }}" name="sponsor" placeholder="Enter sponsor">

                                    @error('sponsor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="religion">Religion</label>
                                    <input type="text" class="form-control @error('religion') is-invalid @enderror" id="religion"
                                           value="{{ old('religion', $doctor->religion) }}" name="religion" placeholder="Enter religion">

                                    @error('religion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div> -->
                        </div> <!-- end row -->

                        <div class="row">
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="height">Height</label>
                                    <input type="text" class="form-control @error('height') is-invalid @enderror" id="height"
                                           value="{{ old('height', $doctor->height) }}" name="height" placeholder="Enter height">

                                    @error('height')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div> -->
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="weight">Weight</label>
                                    <input type="text" class="form-control @error('weight') is-invalid @enderror" id="weight"
                                           value="{{ old('weight', $doctor->weight) }}" name="weight" placeholder="Enter weight">

                                    @error('weight')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div> -->
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                                    <label for="aos">Specialization</label>
                                    <!-- <input type="text" class="form-control @error('aos') is-invalid @enderror" id="aos"
                                           value="{{ old('aos') }}" name="aos" placeholder="Enter specialization"> -->

                                           <select name="aos" class="form-control @error('aos') is-invalid @enderror" id="aos">
                                               <option value="">Select Specialization</option>
                                               <option value="General Practitioner">General Practitioner</option>
                                               <option value="Dentist">Dentist</option>
                                               <option value="Oncology">Oncology</option>
                                               <option value="Internal Medicine">Internal Medicine</option>
                                               <option value="Pediatrics">Pediatrics</option>
                                               <option value="Neurology">Neurology</option>
                                               <option value="Family Doctor">Family Doctor</option>
                                               <option value="Orthopedics">Orthopedics</option>
                                               <option value="Dermatology">Dermatology</option>
                                               <option value="Opthalmology">Opthalmology</option>
                                               <option value="Anesthesiology">Anesthesiology</option>
                                               <option value="Psychiatry">Psychiatry</option>
                                               <option value="Cardiology">Cardiology</option>
                                               <option value="Gynaecology">Gynaecology</option>
                                               <option value="General Surgery">General Surgery</option>
                                               <option value="Neurosurgery">Neurosurgery</option>
                                               <option value="Pathology">Pathology</option>

                                           </select>

                                    @error('aos')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="picture">Picture <small>(*optional)</small></label>

                                    <!<div class="custom-file">
                                        <input type="file"
                                               class="custom-file-input @error('picture') is-invalid @enderror"
                                               name="picture" id="picture-input">
                                        <label class="custom-file-label" for="picture-input">Choose file</label>

                                        @error('picture')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div> -->

                                <!-- </div>
                            </div>  -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="aos">Consulting Fee</label>
                                    <input type="text" class="form-control " id="consultingfee"
                                           value="{{ old('fee', $doctor->fee) }}" name="fee" placeholder="Enter Consulting">

                                    

                                </div>
                            </div>

                           
                        </div> <!-- end row -->

                        @csrf

                        <div class="col-md-2 offset-md-5 text-center mt-2">
                            <button type="submit" class="btn btn-success  btn-rounded"><i
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

$("select[name='state']").change(function (e) {

let states = $(this).val();
console.log(states)

// $.ajax({
//   url: 'http://locationsng-api.herokuapp.com/api/v1/states',
  
//   success: function(),
  
// });
let apivalue =`https://api.facts.ng/v1/states/${states}`;

$('#city').empty()

fetch(`https://api.facts.ng/v1/states/${states}`)
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      console.log(data["lgas"])

        data["lgas"].map(function(lga, i){
            $('#city').append($('<option>', {
                value: lga,
                text: lga
            }));
      })
    })



});

</script>



@endsection