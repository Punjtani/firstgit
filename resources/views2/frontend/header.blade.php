<style>

  header ul.ulDBlock li {
    display: contents;
}

@media (max-width: 900px) {
  #searchbox_header{
    display: none;
  }
}

@media (max-width: 600px) {
  header ul.ulDBlock li:nth-child(3) {
    display: none;
  }
}

@media (max-width: 370px) {
  header #hide_on_mobile {
    display: none;
  }
}
</style>

<!-- Start your project here-->
<header>
 <!-- Start your project here-->
 <nav>
    <div class="d-flex justify-content-between top-header">
      <div class="col">
        <ul class="ulDBlockMenu">
          <li>
            <img src="{{asset('assets/frontend/img/toggle.png')}}" class="openbtn" onclick="openNav()">
          </li>
          <li>
           <a href="{{url('/')}}"> <img class="animated fadeIn logo_img" src="{{asset('assets/frontend/img/logo.png')}}"></a>
          </li>
        </ul>
      </div>

      <div class="col-4" id="searchbox_header">
        <div class="HeaderSearch d-none d-sm-block">
          <input class="SearchInput z-depth-1" type="search" placeholder="Search">
          <img class="plumIcon" src="{{asset('assets/frontend/img/search (2).png')}}">
        </div>
      </div>

      <div class="col text-end" id="hide_on_mobile">
        <ul class="ulDBlock d-sm-block">
            <li>
                <div class="custom-select">
              <!--   <select id="select_language">
                    <option class="plum-text" value="0">Language</option>
                    <option value="1">English</option>
                    <option value="2">Arabic</option>
                </select> -->
                @if(Session::has('lang'))
                <button id="change_lamguage"> @if(Session::get('lang')=="ar") English @else Arabic @endif</button>
                @else
                <button id="change_lamguage">Arabic</button>
                @endif
                </div>
            </li>
{{--       <li><img src="{{asset('assets/frontend/img/line.png')}}" alt="cart"></li>--}}
            <li>
              <a href="#"><img src="{{asset('assets/frontend/img/cart.png')}}" alt="cart"></a>
            </li>
            @guest
            <li>
                <a href="{{route('login')}}"><button class="btn white-btn">Login</button></a>
            </li>

                @endguest
            @auth
                <li>
                    <a href=""><button class="btn white-btn">{{\Illuminate\Support\Facades\Auth::user()->name}}</button></a>
                </li>

                    @if(\Illuminate\Support\Facades\Auth::user()->role_id == 3)
                    <li>
                        @if(\Illuminate\Support\Facades\Auth::user()->is_trainer == 0)
                            <a href="{{route('become_trainer')}}">
                                <button class="btn white-btn">Become a Trainer</button>
                            </a>
                        @else
                            <a href="{{route('Trainer/dashboard')}}">
                                <button class="btn white-btn">Trainer Dashboard</button>
                            </a>

                        @endif
                      </li>
                    @endif

                <li>
                    <a href="{{route('logout')}}"><button class="btn plum-btn">Logout</button></a>
                </li>
                @endauth
        </ul>
      </div>

    </div>
</nav>

<!--   sidebar -->

    <div id="mySidebar" class="sidebar z-depth-1-half">
        @auth
            <div class="row login_id">
                @if(Auth::user()->image == "")
                <img src="{{asset('assets/frontend/img/singup_person.png')}}">
                @else
                  <div class="login-img">
                        <img class="rounded-circle full-img" src="{{asset('users/'. Auth::user()->image)}}">
                  </div>
                @endif

                <!-- <div class="mb-1 white-text">Sign up to receive our weekly newsletter</div> -->
                <div class="flex-center mb-1 mt-1 offline_courses">
                    <u><a class="Socialb plum-text singup_id_name p-0" href="">{{Auth::user()->name}}</a></u>
                </div>
                <p>{{Auth::user()->email}}</p>
            </div>

        @endauth
                @guest
                    <div class="row login_id">
                        <img src="{{asset('assets/frontend/img/singup_person.png')}}">

                        <!-- <div class="mb-1 white-text">Sign up to receive our weekly newsletter</div> -->
                        <div class="flex-center mb-1 mt-1 offline_courses">
                            <u><a class="Socialb plum-text singup_id_name" href="{{route('login')}}">Login/Signup</a></u>
                        </div>
{{--                        <p>guest</p>--}}
                    </div>

                    @endguest
            <div class="row sidebar_icons">
              <a href="javascript:void(0)" class="closebtn close_sidbar" onclick="closeNav()">×</a>
                @auth
                    @if(Auth::user()->role_id == 2)
              <a href="{{route('Trainer/dashboard')}}"><img src="{{asset('assets/frontend/img/dark_mode.png')}}">Dashboard</a>
                    @endif
              <a href="#"><img src="{{asset('assets/frontend/img/dark_mode.png')}}">Dark Mode</a>
                        @if(Auth::user()->role_id == 3)

                        <a href="{{route('my_courses')}}"><img src="{{asset('assets/frontend/img/online_courses.png')}}">My Courses</a>
              <a href="#"><img src="{{asset('assets/frontend/img/offline_courses.png')}}">Offline Courses</a>
              <a href="#"><img src="{{asset('assets/frontend/img/your_courses.png')}}">Your Courses</a>
              <a href="#"><img src="{{asset('assets/frontend/img/favourites%20.png')}}">Favourites </a>
                        @endif
              <a href="#"><img src="{{asset('assets/frontend/img/currency_rate.png')}}">Currency Rate</a>
              <a href="{{route('logout')}}"><img src="{{asset('assets/frontend/img/currency_rate.png')}}">Logout</a>
                @endauth
              <a href="#"><img src="{{asset('assets/frontend/img/contact_us.png')}}">Contact Us</a>
            </div>
    </div>

</header>
<!--Header End Here  -->
