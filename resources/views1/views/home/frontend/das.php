@extends('layouts.main')

@section('indexpage')
<style>
    #img_slider .slider_header a {
        color: #570055;
        font-size: 29px;
    }

    section div:nth-child(0) {
        max-width: 1920px;
    }

    section #content_main_body {
        /* margin-top:201px; */
        margin-bottom: 41px;
        padding-bottom: 2%;
        background-color: white;
    }

    .main_bg_color {
        background-color: #570055;
    }

    .main_color {
        color: #570055;
    }

    .auto_height {
        min-height: 100px;
        /* overflow: hidden; */
    }

    #left_body {

        /*background-color: lightpink;*/
    }

    #trainer11_logo {
        /* margin-top:-150px */
    }

    #trainer11_logo_title p:nth-child(1) {
        font-size: 43px;

    }

    #trainer11_logo_title p:nth-child(2) {
        font-size: 19px;
    }

    .border_right {
        border-right: 1px solid #570055;
    }

    .table_f_size {
        font-size: 18px;
    }

    .carousel_item_footer {
        overflow: hidden;
        position: relative;
        bottom: 0px;
        right: 0px;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        padding: 2%;
    }

    .carousel_item_footer div {
        padding: 5px;
    }

    .carousel_item_footer div p:nth-child(1) {
        font-size: 8px;
    }

    .carousel_item_footer div p:nth-child(2) {
        font-size: 6px;
        margin-top: 2%;
        margin-bottom: 1%;
    }

    .carousel_item_footer div p {
        margin: 0px;
    }

    .col-md-1 {
        margin-top: 10%;
    }

    .hr_home {
        margin-top: 1rem;
        margin-bottom: 1rem;
        border: 0;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

</style>

<!-- Cetegory slider -->
<div class="plum-bg pt-3 pb-3">
    <div class="container">
        <div id="carousel-example-1" class="carousel slide carousel-fade" data-ride="carousel">
            <!--Indicators-->
            <!-- <ol class="carousel-indicators custum-indicators">
        <li data-target="#carousel-example-2" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-2" data-slide-to="1"></li>
        <li data-target="#carousel-example-2" data-slide-to="2"></li>
      </ol> -->
            <!--/.Indicators-->
            <!--Slides-->
            <div class="row">
                <div class="col-12">

                    <div class="carousel-inner" role="listbox">

                        <div class="carousel-item active">
                            <div class="row">
                                <div class="col-1 text-center">
                                    <div>
                                        <a href="{{route('all-course')}}">
                                            <i class="fadeIn fa fa-youtube-play"></i>
                                            <div class="font16 white-text cat-text">All</div>
                                        </a>
                                    </div>
                                </div>
                                @if(isset($categories))
                                @foreach($categories as $cat)
                                <div class="col-1 text-center">
                                    <div>
                                        <a href="{{route('all-course',['id'=>$cat->id])}}">
                                            <img class="fadeIn fa " style="width: 57px; height: 57px;" src="{{asset('category/'.$cat->logo)}}">
                                            <div class="font16 white-text cat-text">{{$cat->name}}</div>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                                @endif

                            </div>
                        </div>
                        {{--                            <div class="carousel-item">--}}
                            {{--                                <div class="row">--}}
                                {{--                                    <div class="col-1 text-center">--}}
                                    {{--                                        <div>--}}
                                        {{--                                            <i class="fadeIn fa fa-youtube-play"></i>--}}
                                        {{--                                            <div class="font16 white-text cat-text">match</div>--}}
                                        {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                {{--                                    <div class="col-1 text-center">--}}
                                    {{--                                        <div>--}}
                                        {{--                                            <i class="fadeIn fa fa-youtube-play"></i>--}}
                                        {{--                                            <div class="font16 white-text cat-text">match</div>--}}
                                        {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                {{--                                    <div class="col-1 text-center">--}}
                                    {{--                                        <div>--}}
                                        {{--                                            <i class="fadeIn fa fa-youtube-play"></i>--}}
                                        {{--                                            <div class="font16 white-text cat-text">match</div>--}}
                                        {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                {{--                                    <div class=" col-1 text-center">--}}
                                    {{--                                        <div>--}}
                                        {{--                                            <i class="fadeIn fa fa-youtube-play"></i>--}}
                                        {{--                                            <div class="font16 white-text cat-text">All</div>--}}
                                        {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                {{--                                    <div class="col-1 text-center">--}}
                                    {{--                                        <div>--}}
                                        {{--                                            <i class="fadeIn fa fa-youtube-play"></i>--}}
                                        {{--                                            <div class="font16 white-text cat-text">All</div>--}}
                                        {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                {{--                                    <div class="col-1 text-center">--}}
                                    {{--                                        <div>--}}
                                        {{--                                            <i class="fadeIn fa fa-youtube-play"></i>--}}
                                        {{--                                            <div class="font16 white-text cat-text">All</div>--}}
                                        {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                {{--                                    <div class="col text-center">--}}
                                    {{--                                        <div>--}}
                                        {{--                                            <i class="fadeIn fa fa-youtube-play"></i>--}}
                                        {{--                                            <div class="font16 white-text cat-text">All</div>--}}
                                        {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                {{--                                    <div class="col-1 text-center">--}}
                                    {{--                                        <div>--}}
                                        {{--                                            <i class="fadeIn fa fa-youtube-play"></i>--}}
                                        {{--                                            <div class="font16 white-text cat-text">All</div>--}}
                                        {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                {{--                                    <div class="col-1 text-center">--}}
                                    {{--                                        <div>--}}
                                        {{--                                            <i class="fadeIn fa fa-youtube-play"></i>--}}
                                        {{--                                            <div class="font16 white-text cat-text">All</div>--}}
                                        {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                {{--                                    <div class="col-1 text-center">--}}
                                    {{--                                        <div>--}}
                                        {{--                                            <i class="fadeIn fa fa-youtube-play"></i>--}}
                                        {{--                                            <div class="font16 white-text cat-text">All</div>--}}
                                        {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                {{--                                    <div class="col-1 text-center">--}}
                                    {{--                                        <div>--}}
                                        {{--                                            <i class="fadeIn fa fa-youtube-play"></i>--}}
                                        {{--                                            <div class="font16 white-text cat-text">All</div>--}}
                                        {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                {{--                                    <div class="col-1 text-center">--}}
                                    {{--                                        <div>--}}
                                        {{--                                            <i class="fadeIn fa fa-youtube-play"></i>--}}
                                        {{--                                            <div class="font16 white-text cat-text">All</div>--}}
                                        {{--                                        </div>--}}
                                    {{--                                    </div>--}}

                                {{--                                </div>--}}
                            {{--                            </div>--}}
                    </div>

                </div>
            </div>
            <!--/.Slides-->
            <!--Controls-->
            <a class="carousel-control-prev cat-prev" href="#carousel-example-1" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon cat-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next cat-next" href="#carousel-example-1" role="button" data-slide="next">
                <span class="carousel-control-next-icon cat-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
            <!--/.Controls-->
        </div>
    </div>
</div>
<!-- End -->

</div>
{{-- <div class="plum-bg pt-3 pb-3">
    <div class="container">
        <ul class="ulDBlock-top">
            <li>
                <a href="{{route('all-course')}}" class="course_list"><i class="fadeIn fa fa-youtube-play"></i>
                    <div class="font16 white-text">All</div>
                </a>
            </li>
            @if(isset($categories))
            @foreach($categories as $cat)
            <li>
                <a href="{{route('all-course',['id'=>$cat->id])}}" class="course_list"> <i> <img
                            class='fadeIn' src="{{url('category'.'/'.$cat->logo)}}" style="
                 width: 57px;
                 height: 57px;
             "></i>
                    <div class="font16 white-text">{{$cat->name}}</div>
                </a>
            </li>
            @endforeach
            @endif

        </ul>
    </div>
</div>--}}
<!-- Services Section End Here -->
<!--Carousel Wrapper-->
<div id="carousel-example-2" class="carousel slide carousel-fade" data-ride="carousel">
    <!--Indicators-->
    <ol class="carousel-indicators custum-indicators">
        <li data-target="#carousel-example-2" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-2" data-slide-to="1"></li>
        <li data-target="#carousel-example-2" data-slide-to="2"></li>
    </ol>
    <!--/.Indicators-->
    <!--Slides-->
    <div class="carousel-inner" role="listbox">
        @if(isset($slider1))
        <div class="carousel-item active">
            <div>
                <div class="view main-slider-height">
                    <img class="d-block full-img w-100" src="{{url('slider').'/'.$slider1['image']}}"
                         alt="First slide">
                    <div class="mask rgba-black-light"></div>
                </div>
                <div class="carousel-caption">
                    <h3 class="h3-responsive font45">{{$slider1['title']}}</h3>
                    <p class="font35">{{$slider1['heading']}}</p>
                </div>
            </div>
        </div>
        @endif
        @if(isset($slider))

        @foreach($slider as $slide)
        <div class="carousel-item">
            <!--Mask color-->
            <div class="view main-slider-height">
                <img class="d-block full-img w-100" src="{{url('slider').'/'.$slide['image']}}"
                     alt="Second slide">
                <div class="mask rgba-black-strong"></div>
            </div>
            <div class="carousel-caption">
                <h3 class="h3-responsive font45">{{$slide['title']}}</h3>
                <p class="font35">{{$slide['heading']}}</p>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    {{-- <div class="carousel-item">
        <!--Mask color-->
        <div class="view">
            <img class="d-block w-100" src="img/slider-11.png" alt="Third slide">
            <div class="mask rgba-black-slight"></div>
        </div>
        <div class="carousel-caption">
            <h3 class="h3-responsive font45">Slight mask</h3>
            <p class="font35">Third text</p>
        </div>
    </div> --}}
    <!--/.Slides-->
    <!--Controls-->
    <a class="carousel-control-prev" href="#carousel-example-2" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carousel-example-2" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
    <!--/.Controls-->
</div>
<!--/.Carousel Wrapper-->
<div class="plum-bg">
    <div class="container over-view-box">
        <div class="row text-center">
            <div class="col-12 col-sm-4 text-center over-view">
                <div><img src="{{asset('assets/frontend/img/customer.png')}}"></div>
                <div class="ml-3">
                    <span class="font-23"><b>100,000 online courses</b></span><br>
                    <span>Explore a veriety of fresh topics</span>
                </div>
            </div>
            <div class="col-12 col-sm-4 text-center over-view">
                <img src="{{asset('assets/frontend/img/instructions.png')}}">
                <div class="ml-3">
                    <span class="font-23"><b>Expert instruction</b></span><br>
                    <span>Find the right instructor for you</span>
                </div>
            </div>
            <div class="col-12 col-sm-4 text-center over-view">
                <img src="{{asset('assets/frontend/img/time.png')}}">
                <div class="ml-3">
                    <span class="font-23"><b>Lifetime access</b></span><br>
                    <span>Learn on your schedule</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="heading mt-3">Latest Courses</div>

<div class="container mt-5 mb-5">
    <div class="carousel slide multi-item-carousel" id="theCarousel">
        <div class="carousel-inner row w-100 ">
            <div class="carousel-item active multi-img p-0">
                <div class="">
                    @if(isset($latest1))
                    <img src="{{asset('course/'. $latest1->thumbnail)}}" class="thumbnail-img">
                    <div class="img-hover">
                        <div class="row hover-bg">
                            <div class="col-3 p-0 text-center">
                                <div class="course-traine-img">
                                    @if($latest1->users['image'] == "")
                                    <img src="{{asset('assets/frontend/img/user-1.png')}}" class="">
                                    @else
                                    <img src="{{asset('users/'. $latest1->users['image'])}}" class="">

                                    @endif
                                </div>
                            </div>
                            <div class="col-5 p-0">
                                <div class="trainer-name">{{$latest1->users['name']}}</div>
                                <div class="course-d mt-2">{{$latest1->name}}</div>
                            </div>
                            <div class="col-4 pt-3">
                                <ul class="card-item">
                                    <li><img src="{{asset('assets/frontend/img/star-small-fill.png')}}">
                                    </li>
                                    <li><img src="{{asset('assets/frontend/img/star-small-fill.png')}}">
                                    </li>
                                    <li><img src="{{asset('assets/frontend/img/star-small.png')}}"></li>
                                    <li>4.8</li>
                                    <li>(12)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @if(isset($latest))

            @foreach($latest as $c)
            <div class="carousel-item  multi-img p-0">
                <div class="">
                    <img src="{{asset('course/'. $c->thumbnail)}}" class="thumbnail-img">
                    <div class="img-hover">
                        <div class="row hover-bg">
                            <div class="col-3 p-0 text-center">
                                <div class="course-traine-img">
                                    @if($c->users['image'] == "")
                                    <img src="{{asset('assets/frontend/img/user-1.png')}}" class="">
                                    @else
                                    <img src="{{asset('users/'. $c->users['image'])}}" class="">

                                    @endif
                                </div>
                            </div>
                            <div class="col-5 p-0">
                                <div class="trainer-name">{{$c->users['name']}}</div>
                                <div class="course-d mt-2">{{$c->name}}</div>
                            </div>
                            <div class="col-4 pt-3">
                                <ul class="card-item">
                                    <li><img src="{{asset('assets/frontend/img/star-small-fill.png')}}">
                                    </li>
                                    <li><img src="{{asset('assets/frontend/img/star-small-fill.png')}}">
                                    </li>
                                    <li><img src="{{asset('assets/frontend/img/star-small.png')}}"></li>
                                    <li>4.8</li>
                                    <li>(12)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @endforeach
            @endif

        </div>
        <a class="carousel-control-prev" href="#theCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#theCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
<!-- Product Slider End -->
<hr style="border-top: 2px solid;height: 5px;width:100%;">
<div>
    <div class="col-12 col-sm-10 offset-md-1 mt-5">
        <div class="row text-center">
            <div class="col-12 col-sm-9 pt-5 text-right">
                <div class="plum-text font36">اشكرك أ.دانة عالطرح الرائع حقيقة استفدت من كلامك كثير واتمنى لو
                    احضر
                </div>
                <div class="plum-text font36">عندك دورة مباشرة احس راح تضيفين لي الكثييير</div>
                <div class="social">
                    <ul class="tranee-social">
                        <li>
                            <a href="#"><img src="{{asset('assets/frontend/img/t-twi.png')}}"></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{asset('assets/frontend/img/t-fb.png')}}"></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{asset('assets/frontend/img/t-insta.png')}}"></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{asset('assets/frontend/img/t-link.png')}}"></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-sm-3">
                <div class="trainee-img">
                    <img class="rounded-circle img-fluid"
                         src="{{asset('assets/frontend/img/Mask Group 1.png')}}">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Description -->
<div class="heading mt-3 mb-3">Different Courses</div>

<hr style="width: 100%; height: 5px; border-top:2px solid">
<!-- Cetegory slider -->
<div class="col-md-12 float-left" id="img_slider">
    <!-- block 1 -->
    <div class="col pt-1" style="min-height: 100px ;overflow: hidden; font-size: 17px;">

        @foreach($categories1 as $c)
        <div class="slider_header text-center m-2" style="color:#570055;"><a href="#"><u>{{$c->name}}</u></a>
        </div>
        @endforeach
        <!--Carousel Wrapper-->
        <div id="multi-item-example2" class="carousel slide carousel-multi-item" data-ride="carousel">

            <!--/.Indicators-->

            <div class="col-md-1 controls-top float-left text-end">
                <a class="btn-floating" href="#multi-item-example2" style="color: #570055" data-slide="prev"><i
                        class="fa fa-chevron-left"></i></a>
            </div>
            <!--Slides-->
            @foreach($categories1 as $c)

            <div class="col-md-10 float-left">
                <div class="carousel-inner" role="listbox">
                    <!--First slide-->
                    <div class="carousel-item active mb-2">

                        <div class="row">
                            <div class="col-md-4 newcard">

                                @foreach($c->courses as $course)
                                <div class="card">
                                    <img class="card-img-top"
                                         src="{{asset('course/' .$course->thumbnail)}}"
                                         alt="{{$course->name}}">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>{{$course->name}}<br>
                                            {{$course->description}}</p>
                                        <p>{{$course->users->name}}</p>
                                        <p class="float-right p-0" style="font-size:10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{--                                @endforeach--}}

                            {{--                                <div class="col-md-4 clearfix d-none d-md-block">--}}
                                {{--                                    <div class="card mb-">--}}
                                    {{--                                        <img class="card-img-top"--}}
                                                                                     {{--                                             src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(18).jpg"--}}
                                                                                     {{--                                             alt="Card image cap">--}}
                                    {{--                                    </div>--}}
                                {{--                                    <div class="col carousel_item_footer main_bg_color" style="color: white">--}}

                                    {{--                                        <div class="col float-left">--}}
                                        {{--                                            <p>The Art Painting and Digital Art Course<br>--}}
                                            {{--                                                -12 Courses in 1</p>--}}
                                        {{--                                            <p>Miss Nabeela, Coderstars by rob percival Experien</p>--}}
                                        {{--                                            <p class="float-right p-0" style="font-size: 10px">--}}
                                            {{--                                                <span class="fa fa-star checked"></span>--}}
                                            {{--                                                <span class="fa fa-star checked"></span>--}}
                                            {{--                                                <span class="fa fa-star checked"></span>--}}
                                            {{--                                                <span class="fa fa-star"></span>--}}
                                            {{--                                                <span class="fa fa-star"></span>--}}
                                            {{--                                                <span> 5</span>--}}
                                            {{--                                                <span>(12345)</span>--}}
                                            {{--                                            </p>--}}
                                        {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                {{--                                </div>--}}

                            {{--                                <div class="col-md-4 clearfix d-none d-md-block">--}}
                                {{--                                    <div class="card">--}}
                                    {{--                                        <img class="card-img-top"--}}
                                                                                     {{--                                             src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(35).jpg"--}}
                                                                                     {{--                                             alt="Card image cap">--}}
                                    {{--                                    </div>--}}
                                {{--                                    <div class="col carousel_item_footer main_bg_color" style="color: white">--}}

                                    {{--                                        <div class="col float-left">--}}
                                        {{--                                            <p>The Art Painting and Digital Art Course<br>--}}
                                            {{--                                                -12 Courses in 1</p>--}}
                                        {{--                                            <p>Miss Nabeela, Coderstars by rob percival Experien</p>--}}
                                        {{--                                            <p class="float-right p-0" style="font-size: 10px">--}}
                                            {{--                                                <span class="fa fa-star checked"></span>--}}
                                            {{--                                                <span class="fa fa-star checked"></span>--}}
                                            {{--                                                <span class="fa fa-star checked"></span>--}}
                                            {{--                                                <span class="fa fa-star"></span>--}}
                                            {{--                                                <span class="fa fa-star"></span>--}}
                                            {{--                                                <span> 5</span>--}}
                                            {{--                                                <span>(12345)</span>--}}
                                            {{--                                            </p>--}}
                                        {{--                                        </div>--}}
                                </div>
                            </div>
                        </div>

                    </div>
                    @endforeach

                    <!--/.First slide-->

                    <!--Second slide-->
                    <div class="carousel-item mb-2">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="https://mdbootstrap.com/img/Photos/Horizontal/City/4-col/img%20(60).jpg"
                                         alt="Card image cap">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>The Art Painting and Digital Art Course<br>
                                            -12 Courses in 1</p>
                                        <p>Miss Nabeela, Coderstars by rob percival Experien</p>
                                        <p class="float-right p-0" style="font-size: 10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 clearfix d-none d-md-block">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="https://mdbootstrap.com/img/Photos/Horizontal/City/4-col/img%20(47).jpg"
                                         alt="Card image cap">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>The Art Painting and Digital Art Course<br>
                                            -12 Courses in 1</p>
                                        <p>Miss Nabeela, Coderstars by rob percival Experien</p>
                                        <p class="float-right p-0" style="font-size: 10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 clearfix d-none d-md-block">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="https://mdbootstrap.com/img/Photos/Horizontal/City/4-col/img%20(48).jpg"
                                         alt="Card image cap">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>The Art Painting and Digital Art Course<br>
                                            -12 Courses in 1</p>
                                        <p>Miss Nabeela, Coderstars by rob percival Experien</p>
                                        <p class="float-right p-0" style="font-size: 10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--/.Second slide-->

                </div>
            </div>

            <div class="controls-top col-md-1 float-left">
                <a class="btn-floating" href="#multi-item-example2" style="color: #570055" data-slide="next"><i
                        class="fa fa-chevron-right"></i></a>
            </div>
            <!--/.Slides-->

        </div>
        <!--/.Carousel Wrapper-->
    </div>
    <!-- col close -->
    <!-- block 2 -->

    <div class="col" style="min-height: 100px ;overflow: hidden;">
        <div class="slider_header text-center m-2"><a href="#"><u>IT & Tech</u></a>
        </div>
        <!--Carousel Wrapper-->
        <div id="multi-item-example" class="carousel slide carousel-multi-item" data-ride="carousel">

            <!--/.Indicators-->

            <div class="col-md-1 controls-top float-left text-end">
                <a class="btn-floating" href="#multi-item-example" style="color: #570055" data-slide="prev"><i
                        class="fa fa-chevron-left"></i></a>
            </div>
            <!--Slides-->

            <div class="col-md-10 float-left">
                <div class="carousel-inner" role="listbox">
                    <!--First slide-->
                    <div class="carousel-item active mb-2">

                        <div class="row">

                            <div class="col-md-4 clearfix d-none d-md-block">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(18).jpg"
                                         alt="Card image cap">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>The Art Painting and Digital Art Course<br>
                                            -12 Courses in 1</p>
                                        <p>Miss Nabeela, Coderstars by rob percival Experien</p>
                                        <p class="float-right p-0" style="font-size: 10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 clearfix d-none d-md-block">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(18).jpg"
                                         alt="Card image cap">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>The Art Painting and Digital Art Course<br>
                                            -12 Courses in 1</p>
                                        <p>Miss Nabeela, Coderstars by rob percival Experien</p>
                                        <p class="float-right p-0" style="font-size: 10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 clearfix d-none d-md-block">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(35).jpg"
                                         alt="Card image cap">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>The Art Painting and Digital Art Course<br>
                                            -12 Courses in 1</p>
                                        <p>Miss Nabeela, Coderstars by rob percival Experien</p>
                                        <p class="float-right p-0" style="font-size: 10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--/.First slide-->

                    <!--Second slide-->
                    <div class="carousel-item mb-2">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="https://mdbootstrap.com/img/Photos/Horizontal/City/4-col/img%20(60).jpg"
                                         alt="Card image cap">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>The Art Painting and Digital Art Course<br>
                                            -12 Courses in 1</p>
                                        <p>Miss Nabeela, Coderstars by rob percival Experien</p>
                                        <p class="float-right p-0" style="font-size: 10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4 clearfix d-none d-md-block">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(18).jpg"
                                         alt="Card image cap">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>The Art Painting and Digital Art Course<br>
                                            -12 Courses in 1</p>
                                        <p>Miss Nabeela, Coderstars by rob percival Experien</p>
                                        <p class="float-right p-0" style="font-size: 10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 clearfix d-none d-md-block">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="https://mdbootstrap.com/img/Photos/Horizontal/City/4-col/img%20(48).jpg"
                                         alt="Card image cap">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>The Art Painting and Digital Art Course<br>
                                            -12 Courses in 1</p>
                                        <p>Miss Nabeela, Coderstars by rob percival Experien</p>
                                        <p class="float-right p-0" style="font-size: 10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--/.Second slide-->

                </div>
            </div>

            <div class="controls-top col-md-1 float-left">
                <a class="btn-floating" href="#multi-item-example" style="color: #570055" data-slide="next"><i
                        class="fa fa-chevron-right"></i></a>
            </div>
            <!--/.Slides-->

        </div>
        <!--/.Carousel Wrapper-->
    </div>
    <!-- col close -->
    <!-- block 3 -->
    <div class="col" style="min-height: 100px ;overflow: hidden;">
        <div class="slider_header text-center m-2"><a href="#"><u>Accountancy</u></a>
        </div>
        <!--Carousel Wrapper-->
        <div id="multi-item-example3" class="carousel slide carousel-multi-item" data-ride="carousel">

            <!--/.Indicators-->

            <div class="col-md-1 controls-top float-left text-end">
                <a class="btn-floating" href="#multi-item-example3" style="color: #570055" data-slide="prev"><i
                        class="fa fa-chevron-left"></i></a>
            </div>
            <!--Slides-->

            <div class="col-md-10 float-left">
                <div class="carousel-inner" role="listbox">
                    <!--First slide-->
                    <div class="carousel-item active mb-2">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(34).jpg"
                                         alt="Card image cap">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>The Art Painting and Digital Art Course<br>
                                            -12 Courses in 1</p>
                                        <p>Miss Nabeela, Coderstars by rob percival Experien</p>
                                        <p class="float-right p-0" style="font-size: 10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 clearfix d-none d-md-block">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(18).jpg"
                                         alt="Card image cap">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>The Art Painting and Digital Art Course<br>
                                            -12 Courses in 1</p>
                                        <p>Miss Nabeela, Coderstars by rob percival Experien</p>
                                        <p class="float-right p-0" style="font-size: 10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 clearfix d-none d-md-block">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(35).jpg"
                                         alt="Card image cap">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>The Art Painting and Digital Art Course<br>
                                            -12 Courses in 1</p>
                                        <p>Miss Nabeela, Coderstars by rob percival Experien</p>
                                        <p class="float-right p-0" style="font-size: 10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--/.First slide-->

                    <!--Second slide-->
                    <div class="carousel-item mb-2">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="https://mdbootstrap.com/img/Photos/Horizontal/City/4-col/img%20(60).jpg"
                                         alt="Card image cap">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>The Art Painting and Digital Art Course<br>
                                            -12 Courses in 1</p>
                                        <p>Miss Nabeela, Coderstars by rob percival Experien</p>
                                        <p class="float-right p-0" style="font-size: 10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 clearfix d-none d-md-block">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(18).jpg"
                                         alt="Card image cap">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>The Art Painting and Digital Art Course<br>
                                            -12 Courses in 1</p>
                                        <p>Miss Nabeela, Coderstars by rob percival Experien</p>
                                        <p class="float-right p-0" style="font-size: 10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 clearfix d-none d-md-block">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="https://mdbootstrap.com/img/Photos/Horizontal/City/4-col/img%20(48).jpg"
                                         alt="Card image cap">
                                </div>
                                <div class="col carousel_item_footer main_bg_color" style="color: white">

                                    <div class="col float-left">
                                        <p>The Art Painting and Digital Art Course<br>
                                            -12 Courses in 1</p>
                                        <p>Miss Nabeela, Coderstars by rob percival Experien</p>
                                        <p class="float-right p-0" style="font-size: 10px">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star"></span>
                                            <span class="fa fa-star"></span>
                                            <span> 5</span>
                                            <span>(12345)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--/.Second slide-->

                </div>
            </div>

            <div class="controls-top col-md-1 float-left">
                <a class="btn-floating" href="#multi-item-example3" style="color: #570055" data-slide="next"><i
                        class="fa fa-chevron-right"></i></a>
            </div>
            <!--/.Slides-->

        </div>
        <!--/.Carousel Wrapper-->
    </div>
    <!-- col close -->

    <!-- slide close -->
</div>


<!-- slider close -->




<!-- display hide  plum ->
<div class="plum-bg pt-3 pb-3" style="display: none;">
    <div class="container">
        <div id="carousel-example-1" class="carousel slide carousel-fade" data-ride="carousel">

        <!--Indicators-->
<!-- <ol class="carousel-indicators custum-indicators">
<li data-target="#carousel-example-2" data-slide-to="0" class="active"></li>
<li data-target="#carousel-example-2" data-slide-to="1"></li>
<li data-target="#carousel-example-2" data-slide-to="2"></li>
</ol> -->
<!--/.Indicators-->
<!--Slides-->

<!--
        <div class="row">
            <div class="col-12">

                <div class="carousel-inner" role="listbox">

                    <div class="carousel-item active">
                        <div class="row">
                            <div class="col-4 text-center">
                                <div class="slider-box">
                                 <img class="fill-img" src="{{asset('assets/frontend/img/ios.png')}}">
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="slider-box">
                                 <img class="fill-img" src="{{asset('assets/frontend/img/ios.png')}}">
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="slider-box">
                                 <img class="fill-img" src="{{asset('assets/frontend/img/ios.png')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--/.Slides-->
<!--Controls->
<a class="carousel-control-prev cat-prev" href="#carousel-example-1" role="button" data-slide="prev">
<span class="carousel-control-prev-icon cat-prev-icon" aria-hidden="true"></span>
<span class="sr-only">Previous</span>
</a>
<a class="carousel-control-next cat-next" href="#carousel-example-1" role="button" data-slide="next">
  <span class="carousel-control-next-icon cat-prev-icon" aria-hidden="true"></span>
  <span class="sr-only">Next</span>
</a>
    <!--/.Controls->
</div>
</div>
</div>
<!-- display none -->

<!-- End -->

<!--Description-->
<hr style="width: 100%; height: 5px; border-top:2px solid">
<!-- Student Comment Section -->
<div class="heading mt-5">Student Feedback</div>
<div class="container mt-3 mb-3">
    <div class="carousel slide multi-item-carousel" id="theCarousel">
        <div class="carousel-inner row mx-auto">
            <div class="carousel-item active col-md-4 mb-5">
                <div class="student-box shadow-sm bg-white rounded">
                    <div class="d-flex">
                        <div class="student-img">
                            <img class="img-fluid rounded-circle"
                                 src="{{asset('assets/frontend/img/Mask Group 1.png')}}">
                        </div>
                        <div class="stu-name">Abcom</div>
                    </div>
                    <div class="stu-description">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                        has been the industry's standard dummy text ever since the 1500s, when an unknown
                        printer took a galley of type and scrambled it to make a type specimen book. It has
                        survived not only five centuries, but also the leap into electronic typesetting,
                        remaining essentially unchanged. It was popularised in the 1960s with the release of
                        Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                        publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                </div>
            </div>
            <div class="carousel-item active col-md-4 mb-5">
                <div class="student-box shadow-sm bg-white rounded">
                    <div class="d-flex">
                        <div class="student-img">
                            <img class="img-fluid rounded-circle"
                                 src="{{asset('assets/frontend/img/Mask Group 1.png')}}">
                        </div>
                        <div class="stu-name">Abcom</div>
                    </div>
                    <div class="stu-description">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                        has been the industry's standard dummy text ever since the 1500s, when an unknown
                        printer took a galley of type and scrambled it to make a type specimen book. It has
                        survived not only five centuries, but also the leap into electronic typesetting,
                        remaining essentially unchanged. It was popularised in the 1960s with the release of
                        Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                        publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                </div>
            </div>
            <div class="carousel-item active col-md-4 mb-5">
                <div class="student-box shadow-sm bg-white rounded">
                    <div class="d-flex">
                        <div class="student-img">
                            <img class="img-fluid rounded-circle"
                                 src="{{asset('assets/frontend/img/Mask Group 1.png')}}">
                        </div>
                        <div class="stu-name">Abcom</div>
                    </div>
                    <div class="stu-description">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                        has been the industry's standard dummy text ever since the 1500s, when an unknown
                        printer took a galley of type and scrambled it to make a type specimen book. It has
                        survived not only five centuries, but also the leap into electronic typesetting,
                        remaining essentially unchanged. It was popularised in the 1960s with the release of
                        Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                        publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#theCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#theCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
<!-- End -->
</div>
<!-- Books Reading Section -->
<div class="book-sec">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-6 text-center">
                <h4><b>Become an instructor</b></h4>
                <p>Top instructors from around the world teach millions of students on Udemy. We provide the
                    tools and skills to teach what you love.</p>
                <button class="btn plum-btn waves-effect waves-light">Start teaching today</button>
            </div>
            <div class="col-12 col-sm-6 text-center">
                <h4><b>Become an Student</b></h4>
                <p>Top instructors from around the world teach millions of students on Udemy. We provide the
                    tools and skills to teach what you love.</p>
                <button class="btn plum-btn waves-effect waves-light">Start learning today</button>
            </div>
        </div>
    </div>
</div>
<!-- End -->
<div class="plum-bg">
    <div class="col-12 col-sm-10 offset-md-1" style="padding: 40px 15px;line-height: 1;">
        <div class="row">
            <div class="col-12 col-sm-6">
                <!-- <div class="mb-1 white-text">Sign up to receive our weekly newsletter</div> -->
                <div class="white-text">Stay uploaded on all new online and offline training courses</div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="Subscription">
                    <input class="SubInput z-depth-1" type="text" placeholder="Enter your email"><span
                        class="SubBtn"><a
                            href="#">Subscribe</a></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="to-top" title="back to top">↑</div>
<div class="sidepanel">
    <span><i class="fa fa-cog fa-spin"></i></span>
    <div class="color-picker">
        <h3>Choose Your Color</h3>
        <a class="color applied" onclick="setActiveStyleSheet('color'); return false;" href="{{url('/')}}"></a>
        <a class="color1" onclick="setActiveStyleSheet('color1'); return false;" href="{{url('/')}}"></a>
    </div>
</div>

@endsection
<!-- Latter Subscription End -->
