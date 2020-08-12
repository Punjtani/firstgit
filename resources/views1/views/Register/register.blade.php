@extends('layouts.main')
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
                <form>
                    <!-- Grd row -->
                    <div class="form-row">
                        <!-- Grid column -->
                        <div class="col-12 mx-auto mb-3">
                            <!-- Default input -->
                            <input type="text" class="form-control custom-height" placeholder="Name">
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-12 mx-auto mb-3">
                            <!-- Default input -->
                            <input type="email" class="form-control custom-height" placeholder="Email">
                        </div>
                        <!-- Grid column -->
                        <!-- Grid column -->
                        <div class="col-12 mx-auto mb-3">
                            <!-- Default input -->
                            <input type="password" class="form-control custom-height" placeholder="password">
                        </div>
                        <!-- Grid column -->
                        <!-- Grid column -->
                        <div class="col-12 mx-auto mb-3">
                            <!-- Default input -->
                            <input type="password" class="form-control custom-height" placeholder="Re-Type password">
                        </div>
                        <!-- Grid column -->

                    </div>
                    <!-- Grd row -->
                </form>
                <button class="login-btn mb-3">Sign Up</button>
                <!-- Default form row -->

                <br>
                <div>Still not member?&nbsp;<span><a href="">Sign up</a></span></div>
            </div>
            <div class="font36 plum-text mt-3 mb-3">تخطى</div>
        </div>
    </div>


    <div id="to-top" title="back to top">↑</div>
    @endsection
