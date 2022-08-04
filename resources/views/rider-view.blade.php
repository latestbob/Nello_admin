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

                        <p class="text-muted mb-2 font-13"><strong>Username :</strong><span class="ml-2">{{ $rider->username ?: 'Unavailable' }}</span></p>

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
                    <form method="post" action="{{ route('rider-view', ['uuid' => $uuid]) }}" enctype="multipart/form-data">
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
                                    <!-- <input type="text" class="form-control @error('state') is-invalid @enderror" id="state"
                                           value="{{ old('state') }}" name="state" placeholder="Enter state"> -->

                                           <select name="state" class="form-control @error('state') is-invalid @enderror" id="state">
                                               <option value="">Select State</option>

                                               @foreach($states as $state)
                                                    <option value="{{$state['name']}}">{{$state['name']}}</option>
                                               @endforeach
                                           </select>

                                    @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
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


@section('js')
<script type="application/javascript">
    var mystates = [
    {
      "state": "Adamawa",
      "alias": "adamawa",
      "lgas": [
        "Demsa",
        "Fufure",
        "Ganye",
        "Gayuk",
        "Gombi",
        "Grie",
        "Hong",
        "Jada",
        "Larmurde",
        "Madagali",
        "Maiha",
        "Mayo Belwa",
        "Michika",
        "Mubi North",
        "Mubi South",
        "Numan",
        "Shelleng",
        "Song",
        "Toungo",
        "Yola North",
        "Yola South"
      ]
    },
    {
      "state": "Akwa Ibom",
      "alias": "akwa_ibom",
      "lgas": [
        "Abak",
        "Eastern Obolo",
        "Eket",
        "Esit Eket",
        "Essien Udim",
        "Etim Ekpo",
        "Etinan",
        "Ibeno",
        "Ibesikpo Asutan",
        "Ibiono-Ibom",
        "Ikot Abasi",
        "Ika",
        "Ikono",
        "Ikot Ekpene",
        "Ini",
        "Mkpat-Enin",
        "Itu",
        "Mbo",
        "Nsit-Atai",
        "Nsit-Ibom",
        "Nsit-Ubium",
        "Obot Akara",
        "Okobo",
        "Onna",
        "Oron",
        "Udung-Uko",
        "Ukanafun",
        "Oruk Anam",
        "Uruan",
        "Urue-Offong/Oruko",
        "Uyo"
      ]
    },
    {
      "state": "Anambra",
      "alias": "anambra",
      "lgas": [
        "Aguata",
        "Anambra East",
        "Anaocha",
        "Awka North",
        "Anambra West",
        "Awka South",
        "Ayamelum",
        "Dunukofia",
        "Ekwusigo",
        "Idemili North",
        "Idemili South",
        "Ihiala",
        "Njikoka",
        "Nnewi North",
        "Nnewi South",
        "Ogbaru",
        "Onitsha North",
        "Onitsha South",
        "Orumba North",
        "Orumba South",
        "Oyi"
      ]
    },
    {
      "state": "Ogun",
      "alias": "ogun",
      "lgas": [
        "Abeokuta North",
        "Abeokuta South",
        "Ado-Odo/Ota",
        "Egbado North",
        "Ewekoro",
        "Egbado South",
        "Ijebu North",
        "Ijebu East",
        "Ifo",
        "Ijebu Ode",
        "Ijebu North East",
        "Imeko Afon",
        "Ikenne",
        "Ipokia",
        "Odeda",
        "Obafemi Owode",
        "Odogbolu",
        "Remo North",
        "Ogun Waterside",
        "Shagamu"
      ]
    },
    {
      "state": "Ondo",
      "alias": "ondo",
      "lgas": [
        "Akoko North-East",
        "Akoko North-West",
        "Akoko South-West",
        "Akoko South-East",
        "Akure North",
        "Akure South",
        "Ese Odo",
        "Idanre",
        "Ifedore",
        "Ilaje",
        "Irele",
        "Ile Oluji/Okeigbo",
        "Odigbo",
        "Okitipupa",
        "Ondo West",
        "Ose",
        "Ondo East",
        "Owo"
      ]
    },
    {
      "state": "Rivers",
      "alias": "rivers",
      "lgas": [
        "Abua/Odual",
        "Ahoada East",
        "Ahoada West",
        "Andoni",
        "Akuku-Toru",
        "Asari-Toru",
        "Bonny",
        "Degema",
        "Emuoha",
        "Eleme",
        "Ikwerre",
        "Etche",
        "Gokana",
        "Khana",
        "Obio/Akpor",
        "Ogba/Egbema/Ndoni",
        "Ogu/Bolo",
        "Okrika",
        "Omuma",
        "Opobo/Nkoro",
        "Oyigbo",
        "Port Harcourt",
        "Tai"
      ]
    },
    {
      "state": "Bauchi",
      "alias": "bauchi",
      "lgas": [
        "Alkaleri",
        "Bauchi",
        "Bogoro",
        "Damban",
        "Darazo",
        "Dass",
        "Gamawa",
        "Ganjuwa",
        "Giade",
        "Itas/Gadau",
        "Jama'are",
        "Katagum",
        "Kirfi",
        "Misau",
        "Ningi",
        "Shira",
        "Tafawa Balewa",
        "Toro",
        "Warji",
        "Zaki"
      ]
    },
    {
      "state": "Benue",
      "alias": "benue",
      "lgas": [
        "Agatu",
        "Apa",
        "Ado",
        "Buruku",
        "Gboko",
        "Guma",
        "Gwer East",
        "Gwer West",
        "Katsina-Ala",
        "Konshisha",
        "Kwande",
        "Logo",
        "Makurdi",
        "Obi",
        "Ogbadibo",
        "Ohimini",
        "Oju",
        "Okpokwu",
        "Oturkpo",
        "Tarka",
        "Ukum",
        "Ushongo",
        "Vandeikya"
      ]
    },
    {
      "state": "Borno",
      "alias": "borno",
      "lgas": [
        "Abadam",
        "Askira/Uba",
        "Bama",
        "Bayo",
        "Biu",
        "Chibok",
        "Damboa",
        "Dikwa",
        "Guzamala",
        "Gubio",
        "Hawul",
        "Gwoza",
        "Jere",
        "Kaga",
        "Kala/Balge",
        "Konduga",
        "Kukawa",
        "Kwaya Kusar",
        "Mafa",
        "Magumeri",
        "Maiduguri",
        "Mobbar",
        "Marte",
        "Monguno",
        "Ngala",
        "Nganzai",
        "Shani"
      ]
    },
    {
      "state": "Bayelsa",
      "alias": "bayelsa",
      "lgas": [
        "Brass",
        "Ekeremor",
        "Kolokuma/Opokuma",
        "Nembe",
        "Ogbia",
        "Sagbama",
        "Southern Ijaw",
        "Yenagoa"
      ]
    },
    {
      "state": "Cross River",
      "alias": "cross_river",
      "lgas": [
        "Abi",
        "Akamkpa",
        "Akpabuyo",
        "Bakassi",
        "Bekwarra",
        "Biase",
        "Boki",
        "Calabar Municipal",
        "Calabar South",
        "Etung",
        "Ikom",
        "Obanliku",
        "Obubra",
        "Obudu",
        "Odukpani",
        "Ogoja",
        "Yakuur",
        "Yala"
      ]
    },
    {
      "state": "Delta",
      "alias": "delta",
      "lgas": [
        "Aniocha North",
        "Aniocha South",
        "Bomadi",
        "Burutu",
        "Ethiope West",
        "Ethiope East",
        "Ika North East",
        "Ika South",
        "Isoko North",
        "Isoko South",
        "Ndokwa East",
        "Ndokwa West",
        "Okpe",
        "Oshimili North",
        "Oshimili South",
        "Patani",
        "Sapele",
        "Udu",
        "Ughelli North",
        "Ukwuani",
        "Ughelli South",
        "Uvwie",
        "Warri North",
        "Warri South",
        "Warri South West"
      ]
    },
    {
      "state": "Ebonyi",
      "alias": "ebonyi",
      "lgas": [
        "Abakaliki",
        "Afikpo North",
        "Ebonyi",
        "Afikpo South",
        "Ezza North",
        "Ikwo",
        "Ezza South",
        "Ivo",
        "Ishielu",
        "Izzi",
        "Ohaozara",
        "Ohaukwu",
        "Onicha"
      ]
    },
    {
      "state": "Edo",
      "alias": "edo",
      "lgas": [
        "Akoko-Edo",
        "Egor",
        "Esan Central",
        "Esan North-East",
        "Esan South-East",
        "Esan West",
        "Etsako Central",
        "Etsako East",
        "Etsako West",
        "Igueben",
        "Ikpoba Okha",
        "Orhionmwon",
        "Oredo",
        "Ovia North-East",
        "Ovia South-West",
        "Owan East",
        "Owan West",
        "Uhunmwonde"
      ]
    },
    {
      "state": "Ekiti",
      "alias": "ekiti",
      "lgas": [
        "Ado Ekiti",
        "Efon",
        "Ekiti East",
        "Ekiti South-West",
        "Ekiti West",
        "Emure",
        "Gbonyin",
        "Ido Osi",
        "Ijero",
        "Ikere",
        "Ilejemeje",
        "Irepodun/Ifelodun",
        "Ikole",
        "Ise/Orun",
        "Moba",
        "Oye"
      ]
    },
    {
      "state": "Enugu",
      "alias": "enugu",
      "lgas": [
        "Awgu",
        "Aninri",
        "Enugu East",
        "Enugu North",
        "Ezeagu",
        "Enugu South",
        "Igbo Etiti",
        "Igbo Eze North",
        "Igbo Eze South",
        "Isi Uzo",
        "Nkanu East",
        "Nkanu West",
        "Nsukka",
        "Udenu",
        "Oji River",
        "Uzo Uwani",
        "Udi"
      ]
    },
    {
      "state": "Federal Capital Territory",
      "alias": "abuja",
      "lgas": [
        "Abaji",
        "Bwari",
        "Gwagwalada",
        "Kuje",
        "Kwali",
        "Municipal Area Council"
      ]
    },
    {
      "state": "Gombe",
      "alias": "gombe",
      "lgas": [
        "Akko",
        "Balanga",
        "Billiri",
        "Dukku",
        "Funakaye",
        "Gombe",
        "Kaltungo",
        "Kwami",
        "Nafada",
        "Shongom",
        "Yamaltu/Deba"
      ]
    },
    {
      "state": "Jigawa",
      "alias": "jigawa",
      "lgas": [
        "Auyo",
        "Babura",
        "Buji",
        "Biriniwa",
        "Birnin Kudu",
        "Dutse",
        "Gagarawa",
        "Garki",
        "Gumel",
        "Guri",
        "Gwaram",
        "Gwiwa",
        "Hadejia",
        "Jahun",
        "Kafin Hausa",
        "Kazaure",
        "Kiri Kasama",
        "Kiyawa",
        "Kaugama",
        "Maigatari",
        "Malam Madori",
        "Miga",
        "Sule Tankarkar",
        "Roni",
        "Ringim",
        "Yankwashi",
        "Taura"
      ]
    },
    {
      "state": "Oyo",
      "alias": "oyo",
      "lgas": [
        "Afijio",
        "Akinyele",
        "Atiba",
        "Atisbo",
        "Egbeda",
        "Ibadan North",
        "Ibadan North-East",
        "Ibadan North-West",
        "Ibadan South-East",
        "Ibarapa Central",
        "Ibadan South-West",
        "Ibarapa East",
        "Ido",
        "Ibarapa North",
        "Irepo",
        "Iseyin",
        "Itesiwaju",
        "Iwajowa",
        "Kajola",
        "Lagelu",
        "Ogbomosho North",
        "Ogbomosho South",
        "Ogo Oluwa",
        "Olorunsogo",
        "Oluyole",
        "Ona Ara",
        "Orelope",
        "Ori Ire",
        "Oyo",
        "Oyo East",
        "Saki East",
        "Saki West",
        "Surulere Oyo State"
      ]
    },
    {
      "state": "Imo",
      "alias": "imo",
      "lgas": [
        "Aboh Mbaise",
        "Ahiazu Mbaise",
        "Ehime Mbano",
        "Ezinihitte",
        "Ideato North",
        "Ideato South",
        "Ihitte/Uboma",
        "Ikeduru",
        "Isiala Mbano",
        "Mbaitoli",
        "Isu",
        "Ngor Okpala",
        "Njaba",
        "Nkwerre",
        "Nwangele",
        "Obowo",
        "Oguta",
        "Ohaji/Egbema",
        "Okigwe",
        "Orlu",
        "Orsu",
        "Oru East",
        "Oru West",
        "Owerri Municipal",
        "Owerri North",
        "Unuimo",
        "Owerri West"
      ]
    },
    {
      "state": "Kaduna",
      "alias": "kaduna",
      "lgas": [
        "Birnin Gwari",
        "Chikun",
        "Giwa",
        "Ikara",
        "Igabi",
        "Jaba",
        "Jema'a",
        "Kachia",
        "Kaduna North",
        "Kaduna South",
        "Kagarko",
        "Kajuru",
        "Kaura",
        "Kauru",
        "Kubau",
        "Kudan",
        "Lere",
        "Makarfi",
        "Sabon Gari",
        "Sanga",
        "Soba",
        "Zangon Kataf",
        "Zaria"
      ]
    },
    {
      "state": "Kebbi",
      "alias": "kebbi",
      "lgas": [
        "Aleiro",
        "Argungu",
        "Arewa Dandi",
        "Augie",
        "Bagudo",
        "Birnin Kebbi",
        "Bunza",
        "Dandi",
        "Fakai",
        "Gwandu",
        "Jega",
        "Kalgo",
        "Koko/Besse",
        "Maiyama",
        "Ngaski",
        "Shanga",
        "Suru",
        "Sakaba",
        "Wasagu/Danko",
        "Yauri",
        "Zuru"
      ]
    },
    {
      "state": "Kano",
      "alias": "kano",
      "lgas": [
        "Ajingi",
        "Albasu",
        "Bagwai",
        "Bebeji",
        "Bichi",
        "Bunkure",
        "Dala",
        "Dambatta",
        "Dawakin Kudu",
        "Dawakin Tofa",
        "Doguwa",
        "Fagge",
        "Gabasawa",
        "Garko",
        "Garun Mallam",
        "Gezawa",
        "Gaya",
        "Gwale",
        "Gwarzo",
        "Kabo",
        "Kano Municipal",
        "Karaye",
        "Kibiya",
        "Kiru",
        "Kumbotso",
        "Kunchi",
        "Kura",
        "Madobi",
        "Makoda",
        "Minjibir",
        "Nasarawa",
        "Rano",
        "Rimin Gado",
        "Rogo",
        "Shanono",
        "Takai",
        "Sumaila",
        "Tarauni",
        "Tofa",
        "Tsanyawa",
        "Tudun Wada",
        "Ungogo",
        "Warawa",
        "Wudil"
      ]
    },
    {
      "state": "Kogi",
      "alias": "kogi",
      "lgas": [
        "Ajaokuta",
        "Adavi",
        "Ankpa",
        "Bassa",
        "Dekina",
        "Ibaji",
        "Idah",
        "Igalamela Odolu",
        "Ijumu",
        "Kogi",
        "Kabba/Bunu",
        "Lokoja",
        "Ofu",
        "Mopa Muro",
        "Ogori/Magongo",
        "Okehi",
        "Okene",
        "Olamaboro",
        "Omala",
        "Yagba East",
        "Yagba West"
      ]
    },
    {
      "state": "Osun",
      "alias": "osun",
      "lgas": [
        "Aiyedire",
        "Atakunmosa West",
        "Atakunmosa East",
        "Aiyedaade",
        "Boluwaduro",
        "Boripe",
        "Ife East",
        "Ede South",
        "Ife North",
        "Ede North",
        "Ife South",
        "Ejigbo",
        "Ife Central",
        "Ifedayo",
        "Egbedore",
        "Ila",
        "Ifelodun",
        "Ilesa East",
        "Ilesa West",
        "Irepodun",
        "Irewole",
        "Isokan",
        "Iwo",
        "Obokun",
        "Odo Otin",
        "Ola Oluwa",
        "Olorunda",
        "Oriade",
        "Orolu",
        "Osogbo"
      ]
    },
    {
      "state": "Sokoto",
      "alias": "sokoto",
      "lgas": [
        "Gudu",
        "Gwadabawa",
        "Illela",
        "Isa",
        "Kebbe",
        "Kware",
        "Rabah",
        "Sabon Birni",
        "Shagari",
        "Silame",
        "Sokoto North",
        "Sokoto South",
        "Tambuwal",
        "Tangaza",
        "Tureta",
        "Wamako",
        "Wurno",
        "Yabo",
        "Binji",
        "Bodinga",
        "Dange Shuni",
        "Goronyo",
        "Gada"
      ]
    },
    {
      "state": "Plateau",
      "alias": "plateau",
      "lgas": [
        "Bokkos",
        "Barkin Ladi",
        "Bassa",
        "Jos East",
        "Jos North",
        "Jos South",
        "Kanam",
        "Kanke",
        "Langtang South",
        "Langtang North",
        "Mangu",
        "Mikang",
        "Pankshin",
        "Qua'an Pan",
        "Riyom",
        "Shendam",
        "Wase"
      ]
    },
    {
      "state": "Taraba",
      "alias": "taraba",
      "lgas": [
        "Ardo Kola",
        "Bali",
        "Donga",
        "Gashaka",
        "Gassol",
        "Ibi",
        "Jalingo",
        "Karim Lamido",
        "Kumi",
        "Lau",
        "Sardauna",
        "Takum",
        "Ussa",
        "Wukari",
        "Yorro",
        "Zing"
      ]
    },
    {
      "state": "Yobe",
      "alias": "yobe",
      "lgas": [
        "Bade",
        "Bursari",
        "Damaturu",
        "Fika",
        "Fune",
        "Geidam",
        "Gujba",
        "Gulani",
        "Jakusko",
        "Karasuwa",
        "Machina",
        "Nangere",
        "Nguru",
        "Potiskum",
        "Tarmuwa",
        "Yunusari",
        "Yusufari"
      ]
    },
    {
      "state": "Zamfara",
      "alias": "zamfara",
      "lgas": [
        "Anka",
        "Birnin Magaji/Kiyaw",
        "Bakura",
        "Bukkuyum",
        "Bungudu",
        "Gummi",
        "Gusau",
        "Kaura Namoda",
        "Maradun",
        "Shinkafi",
        "Maru",
        "Talata Mafara",
        "Tsafe",
        "Zurmi"
      ]
    },
    {
      "state": "Lagos",
      "alias": "lagos",
      "lgas": [
        "Agege",
        "Ajeromi-Ifelodun",
        "Alimosho",
        "Amuwo-Odofin",
        "Badagry",
        "Apapa",
        "Epe",
        "Eti Osa",
        "Ibeju-Lekki",
        "Ifako-Ijaiye",
        "Ikeja",
        "Ikorodu",
        "Kosofe",
        "Lagos Island",
        "Mushin",
        "Lagos Mainland",
        "Ojo",
        "Oshodi-Isolo",
        "Shomolu",
        "Surulere Lagos State"
      ]
    },
    {
      "state": "Katsina",
      "alias": "katsina",
      "lgas": [
        "Bakori",
        "Batagarawa",
        "Batsari",
        "Baure",
        "Bindawa",
        "Charanchi",
        "Danja",
        "Dandume",
        "Dan Musa",
        "Daura",
        "Dutsi",
        "Dutsin Ma",
        "Faskari",
        "Funtua",
        "Ingawa",
        "Jibia",
        "Kafur",
        "Kaita",
        "Kankara",
        "Kankia",
        "Katsina",
        "Kurfi",
        "Kusada",
        "Mai'Adua",
        "Malumfashi",
        "Mani",
        "Mashi",
        "Matazu",
        "Musawa",
        "Rimi",
        "Sabuwa",
        "Safana",
        "Sandamu",
        "Zango"
      ]
    },
    {
      "state": "Kwara",
      "alias": "kwara",
      "lgas": [
        "Asa",
        "Baruten",
        "Edu",
        "Ilorin East",
        "Ifelodun",
        "Ilorin South",
        "Ekiti Kwara State",
        "Ilorin West",
        "Irepodun",
        "Isin",
        "Kaiama",
        "Moro",
        "Offa",
        "Oke Ero",
        "Oyun",
        "Pategi"
      ]
    },
    {
      "state": "Nasarawa",
      "alias": "nasarawa",
      "lgas": [
        "Akwanga",
        "Awe",
        "Doma",
        "Karu",
        "Keana",
        "Keffi",
        "Lafia",
        "Kokona",
        "Nasarawa Egon",
        "Nasarawa",
        "Obi",
        "Toto",
        "Wamba"
      ]
    },
    {
      "state": "Niger",
      "alias": "niger",
      "lgas": [
        "Agaie",
        "Agwara",
        "Bida",
        "Borgu",
        "Bosso",
        "Chanchaga",
        "Edati",
        "Gbako",
        "Gurara",
        "Katcha",
        "Kontagora",
        "Lapai",
        "Lavun",
        "Mariga",
        "Magama",
        "Mokwa",
        "Mashegu",
        "Moya",
        "Paikoro",
        "Rafi",
        "Rijau",
        "Shiroro",
        "Suleja",
        "Tafa",
        "Wushishi"
      ]
    },
    {
      "state": "Abia",
      "alias": "abia",
      "lgas": [
        "Aba North",
        "Arochukwu",
        "Aba South",
        "Bende",
        "Isiala Ngwa North",
        "Ikwuano",
        "Isiala Ngwa South",
        "Isuikwuato",
        "Obi Ngwa",
        "Ohafia",
        "Osisioma",
        "Ugwunagbo",
        "Ukwa East",
        "Ukwa West",
        "Umuahia North",
        "Umuahia South",
        "Umu Nneochi"
      ]
    }
  ]

$("select[name='state']").change(function (e) {

    let found;
let states = $(this).val();
console.log(states)

found = mystates.find(e => e.state === states);
//console.log(found.lgas);

$('#city').empty()


found.lgas.map(function(lga, i){
    console.log(lga)

    $('#city').append($('<option>', {
                value: lga,
                text: lga
            }));
})




});

</script>


@endsection