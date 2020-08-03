@extends('layouts.main2')
@section('headerbody')
    Registration
    @stop
@section('register')
    <div class="img-bg">
        <div class="flex-center flex-column col-11 col-sm-4 mx-auto">
            <img class="mb-1 img-fluid SiteLogo" src="{{asset('assets/frontend/img/logo.png')}}" alt="Logo">
            <h1 class="text-hide animated fadeIn mb-1 font45 plum-text mb-2">أهلا وسهلا بك إلى رمقات</h1>
            <div class="inputform plum-bg text-center white-text">
                <h2>Sign Up</h2>
                <!-- Default form row -->
                <form method="POST" action="{{ route('register') }}">
                @csrf
                    <!-- Grd row -->
                    <div class="form-row">
                        <!-- Grid column -->
                        <div class="col-12 mx-auto mb-3">
                            <!-- Default input -->
                            <input id="name" type="text" placeholder="Name" class="custom-height form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-12 mx-auto mb-3">
                            <!-- Default input -->
                            <input id="email" type="email" placeholder="Email" class="custom-height form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <!-- Grid column -->
                        <!-- Grid column -->
                        <div class="col-12 mx-auto mb-3">
                            <!-- Default input -->
                            <input id="password" type="password" placeholder="Password" class="custom-height form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <!-- Grid column -->
                        <!-- Grid column -->
                        <div class="col-12 mx-auto mb-3">
                            <!-- Default input -->
                                <input id="password-confirm" placeholder="Re-Type Password" type="password" class="custom-height form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                        <div class="col-12 mx-auto mb-3">
                            <input type="checkbox" name="trainer" value="1" > As Trainer
                        </div>

                    </div>
                    <!-- Grd row -->
                    <button type="submit" class="login-btn mb-3">Sign Up</button>

                </form>
                <!-- Default form row -->

                <div>Aleady Register?&nbsp;<span><a href="">Login</a></span></div>
                <hr style="background-color:#fff;">
                <div class="col-12">
                    <bitton style="border-radius:5px;" class="btn white-btn waves-effect waves-light w-100">Trainer</buton>
                </div>
            </div>
            <div class="font36 plum-text mt-3 mb-3">تخطى</div>
        </div>
    </div>


    <div id="to-top" title="back to top">↑</div>
    @endsection
