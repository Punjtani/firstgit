@extends('layouts.main2')
@section('userlogin')
<div class="img-bg" style="height:100vh">
    <div class="flex-center flex-column col-11 col-sm-4 mx-auto">
        <img class="mb-2 SiteLogo" src="{{asset('assets/frontend/img/logo.png')}}" alt="Logo">
        <h1 class="text-hide animated fadeIn mb-2 font45 plum-text mb-2">أهلا وسهلا بك إلى رمقات</h1>
        <div class="inputform plum-bg text-center white-text">
            <h2>Sign in</h2>
            <!-- Default form row -->
            <form method="POST" action="{{ route('login_user') }}">
            @csrf
            <!-- Grd row -->
                <div class="form-row">
                    <!-- Grid column -->
                    <div class="col-12 mx-auto mb-3">
                        <!-- Default input -->
                        <input type="email" name="email" class="form-control custom-height" placeholder="Email">
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-12 mx-auto mb-3">
                        <!-- Default input -->
                        <input type="password" name="password" class="form-control custom-height" placeholder="password">
                    </div>
                    <!-- Grid column -->
                </div>
                <!-- Grd row -->
                <button type="submit" class="login-btn mb-3">Sign in</button>

            </form>
            <div>
                <ul class="social-login">
                    <li><img src="{{asset('assets/frontend/img/fb.png')}}" alt="facebook"></li>
                    <li><img src="{{asset('assets/frontend/img/google.png')}}" alt="google"></li>
                    <li><img src="{{asset('assets/frontend/img/apple.png')}}" alt="apple"></li>
                </ul>
            </div>
            <!-- Default form row -->
            <!-- <div class="d-none d-sm-block">
              <div class="mb-3">Or</div>
              <div><a href="#"><img src="img/login-apple.png" alt=""></a></div>
              <div><a href="#"><img src="img/login-gogle.png" alt=""></a></div>
              <div><a href="#"><img src="img/login-fb.png" alt=""></a></div>
            </div> -->
            <br>
            <div>Still not member?&nbsp;<span><a href="{{ route('register') }}">Sign up</a></span></div>
        </div>
        <div class="font36 plum-text mt-3 mb-3">تخطى</div>
    </div>
</div>
<div id="to-top" title="back to top">↑</div>
@endsection
