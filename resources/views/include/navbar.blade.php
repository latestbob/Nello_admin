<div class="slimscroll-menu" id="left-side-menu-container">

    <!-- LOGO -->

    @if(\Illuminate\Support\Facades\Auth::check() &&
                \Illuminate\Support\Facades\Auth::user()->user_type == "admin")
    <a href="{{ url('/') }}" class="logo text-center">
                <span class="logo-lg">
                    <img src="{{ asset('images/logo.png') }}" alt="" height="40" id="side-main-logo">
                </span>
        <span class="logo-sm">
                    <img src="{{ asset('images/logo.png') }}" alt="" height="40" id="side-sm-main-logo">
                </span>
    </a>

    @elseif(Auth::user()->email == "admin@owcappointment.com")
    <a href="{{ url('/') }}" class="logo text-center mb-5 mt-3">
                <span class="logo-lg">
                    <img style="border-radius:100%;" src="https://owcappointment.com/static/media/mainlogo.97dcb8ed6a9966819480.png" alt="" width="120" id="side-main-logo">
                </span>
        <span class="logo-sm mt-5">
                    <img src="https://owcappointment.com/static/media/mainlogo.97dcb8ed6a9966819480.png" alt="" height="40" id="side-sm-main-logo">
                </span>
    </a>

    @elseif(Auth::user()->email == "admin@famacare.com")
    <a href="{{ url('/') }}" class="logo text-center mb-5 mt-3">
                <span class="logo-lg">
                    <img style="" src="https://res.cloudinary.com/edifice-solutions/image/upload/v1668425307/logofour_1_nvthvu.png" alt="" width="150" id="side-main-logo">
                </span>
        <span class="logo-sm mt-5">
                    <img src="https://res.cloudinary.com/edifice-solutions/image/upload/v1668425307/logofour_1_nvthvu.png" alt="" height="50" id="side-sm-main-logo">
                </span>
    </a>

    @endif

    <!--- Sidemenu -->
    <ul class="metismenu side-nav">

    @if(\Illuminate\Support\Facades\Auth::check() &&
                \Illuminate\Support\Facades\Auth::user()->user_type == "admin")
        <li class="side-nav-title side-nav-item">Navigation</li>

        <li class="side-nav-item">
            <a href="/" class="side-nav-link">
                <i class="uil-dashboard"></i>
                <span> Dashboard </span>
            </a>
        </li>

    @endif

      

        <li class="side-nav-title side-nav-item">Modules</li>

        @if(\Illuminate\Support\Facades\Auth::check() &&
                \Illuminate\Support\Facades\Auth::user()->email == "admin@owcappointment.com")
            <li class="side-nav-item mt-5">
                <a href="{{route('owcappointment')}}" class="side-nav-link">
                    <i class="uil-calender"></i>
                    <span> Appointments </span>
                </a>
            </li>

            <li class="side-nav-item mt-2">
                <a href="{{route('owcmedicalcalender')}}" class="side-nav-link">
                    <i class="uil-calender"></i>
                    <span> Calendar Schedules </span>
                </a>
            </li>

            <li class="side-nav-item mt-2">
                <a href="" class="side-nav-link">
                    <i class="uil-user"></i>
                    <span> Doctors</span>
                </a>
            </li>

            @elseif(\Illuminate\Support\Facades\Auth::check() &&
                \Illuminate\Support\Facades\Auth::user()->email == "admin@famacare.com")
            <li class="side-nav-item mt-5">
                <a href="{{route('famacareappointment')}}" class="side-nav-link">
                    <i class="uil-calender"></i>
                    <span>Online  Appointments </span>
                </a>
            </li>

            <li class="side-nav-item mt-2">
                <a href="{{route('famacarephysicalappointment')}}" class="side-nav-link">
                    <i class="uil-calender"></i>
                    <span>Physical  Appointments </span>
                </a>
            </li>

            <li class="side-nav-item mt-2">
                <a href="{{route('famacarespecialist')}}" class="side-nav-link">
                    <i class="uil-clock"></i>
                    <span> Specialist Schedule </span>
                </a>
            </li>

            <li class="side-nav-item mt-2">
                <a href="{{route('famacarespecialization')}}" class="side-nav-link">
                    <i class="uil-clock"></i>
                    <span> Center Schedule</span>
                </a>
            </li>


            <li class="side-nav-item mt-2">
                <a href="" class="side-nav-link">  
                    <i class="uil:money-bill"></i>
                    <span> Transactions</span>
                </a>
            </li>
        @endif

         @if(\Illuminate\Support\Facades\Auth::check() &&
                \Illuminate\Support\Facades\Auth::user()->user_type == "admin")
            <li class="side-nav-item">
                <a href="{{ route('feedbacks') }}" class="side-nav-link">
                    <i class="uil-rss"></i>
                    <span> Feedbacks </span>
                </a>
            </li>
        @endif 

        @if(\Illuminate\Support\Facades\Auth::check() &&
                \Illuminate\Support\Facades\Auth::user()->user_type == "admin")
            <li class="side-nav-item">
                <a href="{{ route('appointments') }}" class="side-nav-link">
                    <i class="uil-calender"></i>
                    <span> Appointments </span>
                </a>
            </li>
        @endif

        @if(\Illuminate\Support\Facades\Auth::check() &&
                        \Illuminate\Support\Facades\Auth::user()->user_type == "admin")

        <li class="side-nav-item">
            <a href="javascript: void(0);" class="side-nav-link">
                <i class="uil-medical"></i>
                <span> Drugs </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="side-nav-second-level" aria-expanded="false">

              
                    <li>
                        <a href="{{ route('drug-add') }}">Add</a>
                    </li>
                    <li>
                        <a href="{{ route('drugs') }}">View</a>
                    </li>
                    <li>
                        <a href="{{ route('drug-categories') }}">Categories</a>
                    </li>
                    <li>
                        <a href="{{ route('coupons') }}">Coupons</a>
                    </li>
                    <!-- <li>
                        <a href="{{ route('drugs-import') }}">Import</a>
                    </li> -->
                
                <li>
                    <a href="{{ route('drugs-order') }}">Orders</a>
                </li>
            </ul>
        </li>

        @endif

        {{-- @if(\Illuminate\Support\Facades\Auth::check() &&
                (\Illuminate\Support\Facades\Auth::user()->user_type == "admin" ||
                \Illuminate\Support\Facades\Auth::user()->user_type == "doctor"))
        <li class="side-nav-item">
            <a href="{{ route('doctor-messages') }}" class="side-nav-link">
                <i class="uil-message"></i>
                <span> Doctors Messages </span>
            </a>
        </li>
        @endif --}}

        @if(\Illuminate\Support\Facades\Auth::check() &&
                \Illuminate\Support\Facades\Auth::user()->user_type == "admin")

            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class="uil-heart-medical"></i>
                    <span> Doctors </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ route('doctor-add') }}">Add</a>
                    </li>
                    <li>
                        <a href="{{ route('doctors') }}">View</a>
                    </li>
                </ul>
            </li>

            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class="uil-store"></i>
                    <span> Pharmacies </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ route('pharmacy-add') }}">Add</a>
                    </li>
                    <li>
                        <a href="{{ route('pharmacies') }}">View</a>
                    </li>
                    <!-- <li>
                        <a href="{{ route('pharmacy-agents') }}">Agents</a>
                    </li> -->
                </ul>
            </li>

            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class="uil-store"></i>
                    <span> Health Centers </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ route('health-center-add') }}">Add</a>
                    </li>
                    <li>
                        <a href="{{ route('health-centers') }}">View</a>
                    </li>
                </ul>
            </li>

            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class="mdi mdi-car"></i>
                    <span> Riders </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ route('rider-add') }}">Add</a>
                    </li>
                    <li>
                        <a href="{{ route('riders') }}">View</a>
                    </li>
                </ul>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('customers') }}" class="side-nav-link">
                    <i class="uil uil-user"></i>
                    <span> Customers </span>
                </a>
            </li>


            <li class="side-nav-item">
                <a href="{{route('transaction-view')}}" class="side-nav-link">
                    <i class="uil-money-bill"></i>
                    <span> Transactions </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{route('owcappointment')}}" class="side-nav-link">
                    <i class="uil uil-user"></i>
                    <span> OWC</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class="uil-money-bill"></i>
                    <span> Sales Report </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{route('drugsalesreport')}}">Drug Sales Report</a>
                    </li>
                    <li>
                        <a href="{{route('cancelledinvoices')}}">Cancelled Invoices</a>
                    </li>


                    <li>
                        <a href="{{route('skinsreport')}}">Skinns Inventory </a>
                    </li>
                </ul>
            </li>

            <li class="side-nav-item">
                <a href="{{route('password-activity')}}" class="side-nav-link">
                    <i class="uil uil-user"></i>
                    <span> Password Activity </span>
                </a>
            </li>


            <li class="side-nav-item">
                <a href="{{route('getinterest')}}" class="side-nav-link">
                    <i class="uil uil-user"></i>
                    <span> Business Interests </span>
                </a>
            </li>

            <!-- <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class="uil-book-medical"></i>
                    <span> Health Tips </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ route('health-tip-add') }}">Add</a>
                    </li>
                    <li>
                        <a href="{{ route('health-tips') }}">View</a>
                    </li>
                </ul>
            </li> -->

            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class="uil-location"></i>
                    <span> Locations </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ route('location-add') }}">Add</a>
                    </li>
                    <li>
                        <a href="{{ route('locations') }}">View</a>
                    </li>
                </ul>
            </li>

            <!-- <li class="side-nav-item">
                <a href="{{ route('point-rule') }}" class="side-nav-link">
                    <i class="dripicons-gear"></i>
                    <span> Customer Point Rules </span>
                </a>
            </li> -->

            <!-- <li class="side-nav-item">
                <a href="{{ route('prescription-fee') }}" class="side-nav-link">
                    <i class="uil-money-bill"></i>
                    <span> Prescription Fee </span>
                </a>
            </li> -->
        @endif

    </ul>

    <!-- End Sidebar -->

    <div class="clearfix"></div>

</div>
<!-- Sidebar -left -->
