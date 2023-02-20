<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

@yield('css')

<!-- App css -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style"/>
    <link href="{{ asset('css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Scripts -->
    <script src="{{ asset('js/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert/sweetalert.js') }}"></script>
    <script>
        function successMsg(title, message, confirmBtnTxt, cancelBtnTxt, btnCallback, isHtml = false) {
            // swal.fire(title, message, 'success')
            swal.fire({
                title: title,
                [isHtml ? 'html': 'text']: message,
                type: "success",
                confirmButtonClass: "btn-success no-border-radius",
                confirmButtonText: confirmBtnTxt ? confirmBtnTxt : 'Ok',
                showCancelButton: (typeof cancelBtnTxt === "string"),
                cancelButtonText: cancelBtnTxt ? cancelBtnTxt : 'Cancel',
                closeOnConfirm: true
            }).then((btnCallback instanceof Function ? function (isConfirm) {
                btnCallback(isConfirm);
            } : null));
        }


        function isEmpty(variable) {
            let string = variable.toString();
            return (
                variable === false
                || variable === null
                || string === "0"
                || string === ""
                || string === " "
            );
        }

        function serializeMessage(object) {
            let message = "", x, count = 0;
            for (x in object) {
                if (object.hasOwnProperty(x)) {
                    if (isEmpty(object[x])) continue;
                    // language=HTML
                    message += (!isEmpty(message) ? "<br>" : "") + (++count + ". " +
                        (typeof object[x] !== "string" ? serializeMessage(object[x]) : object[x]));
                }
            }
            return message;
        }

        function processErrors(xhr) {
            if (xhr.responseJSON) {
                const errors = xhr.responseJSON.errors;
                if (errors) {
                    let msg = '';
                    Object.keys(errors).forEach(key => {
                        errors[key].forEach(err => {
                            msg += `${err} <br/>`
                        })
                    });
                    return msg
                }
            }
        }

        function bootBox(title, message, confirmBtnTxt,
                         confirmBtnCallback, cancelBtnTxt,
                         cancelBtnCallback) {

            let button = (
                typeof cancelBtnTxt === "string" ?
                    {
                        ok: {
                            label: confirmBtnTxt,
                            className: "btn-primary no-border-radius",
                            callback: (confirmBtnCallback instanceof Function ? confirmBtnCallback : null)
                        },
                        cancel: {
                            label: cancelBtnTxt,
                            className: "btn-warning",
                            callback: (cancelBtnCallback instanceof Function ? cancelBtnCallback : null)
                        }
                    } :
                    {
                        ok: {
                            label: confirmBtnTxt,
                            className: "btn-primary no-border-radius",
                            callback: (confirmBtnCallback instanceof Function ? confirmBtnCallback : null)
                        }
                    }
            );

            bootbox.dialog({
                title: title,
                message: message,
                buttons: button
            });
        }

        function errorMsg(title, msg, html = false) {
            if (html) {
                swal.fire({
                    title: title,
                    html: msg,
                    icon: 'error'
                })
            } else {
                swal.fire(
                    title,
                    msg,
                    'error'
                )
            }
        }

        const getSearchParameters = () => {
            let params = window.location.search.substr(1);
            return params != null && params !== "" ? transformToAssocArray(params) : {};
        };

        const transformToAssocArray = (paramStr) => {
            let params = {};
            let paramsArr = paramStr.split("&");
            for (let i = 0; i < paramsArr.length; i++) {
                let tmpArr = paramsArr[i].split("=");
                params[tmpArr[0]] = tmpArr[1];
            }
            return params;
        };

        const serialize = (object) => {
            let list = [], x;
            for (x in object) {
                if (object.hasOwnProperty(x)) {
                    list[list.length] = encodeURIComponent(x) + "=" + encodeURIComponent(
                        null == object[x] ? "" : object[x]);
                }
            }
            return list.join('&');
        };
    </script>
</head>

<body class="loading">

<!-- Begin page -->
<div class="wrapper">
    <!-- ========== Left Sidebar Start ========== -->
    <div class="left-side-menu">

        @include('include.navbar')

    </div>
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">
            <!-- Topbar Start -->
            <div class="navbar-custom">
                <ul class="list-unstyled topbar-right-menu float-right mb-0">
                    <li class="notification-list">
                        <a class="nav-link right-bar-toggle" href="javascript: void(0);">
                            <i class="dripicons-gear noti-icon"></i>
                        </a>
                    </li>

                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown" href="#"
                           role="button" aria-haspopup="false"
                           aria-expanded="false">
                                    <span class="account-user-avatar">
                                        <img src="https://res.cloudinary.com/edifice-solutions/image/upload/v1665056651/ladynello_uyzc9g.png"style="width:50px; height:50px;" alt="user-image"
                                             class="rounded-circle">
                                    </span>
                            <span>
                                <span class="account-user-name ml-3 mt-2">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
                                <!-- <span class="account-position">{{ Auth::user()->vendor->name }} - ({{ Auth::user()->user_type }})</span> -->
                            </span>
                        </a>
                        <div
                            class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown">

                            <!-- item-->
                            <a href="{{route('myaccount')}}" class="dropdown-item notify-item">
                                <i class="mdi mdi-account-circle mr-1"></i>
                                <span>My Account</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                            <!-- item-->
                            <a href="{{ route('logout') }}" class="dropdown-item notify-item"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="mdi mdi-logout mr-1"></i>
                                <span>Logout</span>
                            </a>

                        </div>
                    </li>

                </ul>
                <button class="button-menu-mobile open-left disable-btn">
                    <i class="mdi mdi-menu"></i>
                </button>
            </div>
            <!-- end Topbar -->

            <!-- Start Content-->
            <div class="container-fluid">

                @include('include.messages')

                @yield('content')

            </div>
            <!-- container -->
        </div>
        <!-- content -->

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        {{ config('app.year', '2020') }} © {{ config('app.name', 'Laravel') }}
                    </div>
                    <div class="col-md-6">
                       
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->

    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->


</div>
<!-- END wrapper -->

<!-- Right Sidebar -->
<div class="right-bar">

    <div class="rightbar-title">
        <a href="javascript:void(0);" class="right-bar-toggle float-right">
            <i class="dripicons-cross noti-icon"></i>
        </a>
        <h5 class="m-0">Settings</h5>
    </div>

    <div class="slimscroll-menu rightbar-content">

        <div class="p-3">
            <div class="alert alert-warning" role="alert">
                <strong>Customize </strong> the overall color scheme, sidebar menu, etc. Note that, {{ config('app.name', 'Laravel') }} stores the
                preferences in local storage.
            </div>

            <!-- Settings -->
            <h5 class="mt-3">Color Scheme</h5>
            <hr class="mt-1"/>

            <div class="custom-control custom-switch mb-1">
                <input type="radio" class="custom-control-input" name="color-scheme-mode" value="light"
                       id="light-mode-check"
                       checked/>
                <label class="custom-control-label" for="light-mode-check">Light Mode</label>
            </div>

            <div class="custom-control custom-switch mb-1">
                <input type="radio" class="custom-control-input" name="color-scheme-mode" value="dark"
                       id="dark-mode-check"/>
                <label class="custom-control-label" for="dark-mode-check">Dark Mode</label>
            </div>

            <!-- Width -->
            <h5 class="mt-4">Width</h5>
            <hr class="mt-1"/>
            <div class="custom-control custom-switch mb-1">
                <input type="radio" class="custom-control-input" name="width" value="fluid" id="fluid-check" checked/>
                <label class="custom-control-label" for="fluid-check">Fluid</label>
            </div>
            <div class="custom-control custom-switch mb-1">
                <input type="radio" class="custom-control-input" name="width" value="boxed" id="boxed-check"/>
                <label class="custom-control-label" for="boxed-check">Boxed</label>
            </div>

            <!-- Left Sidebar-->
            <h5 class="mt-4">Left Sidebar</h5>
            <hr class="mt-1"/>
            <div class="custom-control custom-switch mb-1">
                <input type="radio" class="custom-control-input" name="theme" value="default" id="default-check"
                       checked/>
                <label class="custom-control-label" for="default-check">Default</label>
            </div>

            <div class="custom-control custom-switch mb-1">
                <input type="radio" class="custom-control-input" name="theme" value="light" id="light-check"/>
                <label class="custom-control-label" for="light-check">Light</label>
            </div>

            <div class="custom-control custom-switch mb-3">
                <input type="radio" class="custom-control-input" name="theme" value="dark" id="dark-check"/>
                <label class="custom-control-label" for="dark-check">Dark</label>
            </div>

            <div class="custom-control custom-switch mb-1">
                <input type="radio" class="custom-control-input" name="compact" value="fixed" id="fixed-check" checked/>
                <label class="custom-control-label" for="fixed-check">Fixed</label>
            </div>

            <div class="custom-control custom-switch mb-1">
                <input type="radio" class="custom-control-input" name="compact" value="condensed" id="condensed-check"/>
                <label class="custom-control-label" for="condensed-check">Condensed</label>
            </div>

            <div class="custom-control custom-switch mb-1">
                <input type="radio" class="custom-control-input" name="compact" value="scrollable"
                       id="scrollable-check"/>
                <label class="custom-control-label" for="scrollable-check">Scrollable</label>
            </div>

            <button class="btn btn-primary btn-block mt-4" id="resetBtn">Reset to Default</button>

        </div> <!-- end padding-->

    </div>
</div>

<div class="rightbar-overlay"></div>
<!-- /Right-bar -->

<!-- end page -->

<!-- bundle -->

<script src="{{ asset('js/vendor.min.js') }}"></script>
<script src="{{ asset('js/app.min.js') }}"></script>


@yield('js')

</body>

</html>
