@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card">

                    <!-- Logo -->
                    <div class="card-header pt-4 pb-4 text-center">
                        <a href="{{ url('/') }}">
                            <span><img src="{{ asset('images/logo.png') }}" alt="" height="auto"></span>
                        </a>
                    </div>

                    <div class="card-body p-4">

                        <div class="text-center w-75 m-auto">
                            <h4 class="text-dark-50 text-center mt-0 font-weight-bold">Sign In</h4>
                            <p class="text-muted mb-4">Enter your email address and password to access admin panel.</p>
                        </div>

                        @include('include.messages')

                        <form method="POST" action="{{ route('login') }}">

                            <div class="form-group">
                                <label for="emailaddress">Email address</label>

                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email"
                                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                            <div class="form-group">
                                @if (Route::has('password.request'))
                                    <a class="text-muted float-right" href="{{ route('password.request') }}">
                                        <small>{{ __('Forgot Your Password?') }}</small>
                                    </a>
                                @endif
                                <label for="password">Password</label>

                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password"
                                       name="password" required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                            <div class="form-group mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="remember" id="checkbox-signin" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                </div>
                            </div>

                            @csrf

                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-primary" type="submit"> Log In </button>
                            </div>
                        </form>
                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-muted">Don't have an account? <a href="{{ route('register') }}" class="text-muted ml-1"><b>Sign Up</b></a></p>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
@endsection
