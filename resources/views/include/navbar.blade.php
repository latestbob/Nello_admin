<div class="slimscroll-menu" id="left-side-menu-container">

    <!-- LOGO -->
    <a href="{{ url('/') }}" class="logo text-center">
                <span class="logo-lg">
                    <img src="{{ asset('images/logo.png') }}" alt="" height="40" id="side-main-logo">
                </span>
        <span class="logo-sm">
                    <img src="{{ asset('images/logo.png') }}" alt="" height="40" id="side-sm-main-logo">
                </span>
    </a>

    <!--- Sidemenu -->
    <ul class="metismenu side-nav">

        <li class="side-nav-title side-nav-item">Navigation</li>

        <li class="side-nav-item">
            <a href="/" class="side-nav-link">
                <i class="uil-dashboard"></i>
                <span> Dashboard </span>
            </a>
        </li>

        <li class="side-nav-title side-nav-item">Modules</li>

        <li class="side-nav-item">
            <a href="{{ route('feedbacks') }}" class="side-nav-link">
                <i class="uil-rss"></i>
                <span> Feedbacks </span>
            </a>
        </li>

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
                    <a href="{{ route('drugs-order') }}">Orders</a>
                </li>
            </ul>
        </li>

        <li class="side-nav-item">
            <a href="{{ route('doctors') }}" class="side-nav-link">
                <i class="uil-heart-medical"></i>
                <span> Doctors </span>
            </a>
        </li>

    </ul>

    <!-- End Sidebar -->

    <div class="clearfix"></div>

</div>
<!-- Sidebar -left -->
