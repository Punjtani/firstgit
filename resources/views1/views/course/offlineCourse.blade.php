@extends('layouts.main')
@section('offline-course')
<div class="flex-center mb-1 mt-1 offline_courses">
    <u><a class="Socialb plum-text" href="#">Offline Courses</a></u>
</div>

<div class="container mt-5 mb-5">
    <div class="carousel slide multi-item-carousel" id="theCarousel">
        <div class="carousel-inner row w-100 mx-auto">
            <div class="carousel-item active col-md-4">
                <a href="#"><img src="{{asset('assets/frontend/')}}img/tipstricks.png" class="img-fluid mx-auto d-block zoom"></a>
            </div>
            <div class="carousel-item col-md-4">
                <img src="{{asset('assets/frontend/')}}img/ios.png" class="img-fluid mx-auto d-block zoom">
            </div>
            <div class="carousel-item col-md-4">
                <img src="{{asset('assets/frontend/')}}img/note20.png" class="img-fluid mx-auto d-block zoom">
            </div>
        </div>
    </div>
</div>
<div class="container mt-5 mb-5">
    <div class="carousel slide multi-item-carousel" id="theCarousel">
        <div class="carousel-inner row w-100 mx-auto">
            <div class="carousel-item active col-md-4">
                <a href="#"><img src="{{asset('assets/frontend/img/tipstricks.png')}}" class="img-fluid mx-auto d-block zoom"></a>
            </div>
            <div class="carousel-item col-md-4">
                <img src="{{asset('assets/frontend/img/ios.png')}}" class="img-fluid mx-auto d-block zoom">
            </div>
            <div class="carousel-item col-md-4">
                <img src="{{asset('assets/frontend/img/note20.png')}}" class="img-fluid mx-auto d-block zoom">
            </div>
        </div>
    </div>
</div>
<div class="container mt-5 mb-5">
    <div class="carousel slide multi-item-carousel" id="theCarousel">
        <div class="carousel-inner row w-100 mx-auto">
            <div class="carousel-item active col-md-4">
                <a href="#"><img src="{{asset('assets/frontend/img/tipstricks.png')}}" class="img-fluid mx-auto d-block zoom"></a>
            </div>
            <div class="carousel-item col-md-4">
                <img src="{{asset('assets/frontend/img/ios.png')}}" class="img-fluid mx-auto d-block zoom">
            </div>
            <div class="carousel-item col-md-4">
                <img src="{{asset('assets/frontend/img/note20.png')}}" class="img-fluid mx-auto d-block zoom">
            </div>
        </div>

    </div>
</div>
<div class="container mt-5 mb-5">
    <div class="carousel slide multi-item-carousel" id="theCarousel">
        <div class="carousel-inner row w-100 mx-auto">
            <div class="carousel-item active col-md-4">
                <a href="#"><img src="{{asset('assets/frontend/img/tipstricks.png')}}" class="img-fluid mx-auto d-block zoom"></a>
            </div>
            <div class="carousel-item col-md-4">
                <img src="{{asset('assets/frontend/img/ios.png')}}" class="img-fluid mx-auto d-block zoom">
            </div>
            <div class="carousel-item col-md-4">
                <img src="{{asset('assets/frontend/img/note20.png')}}" class="img-fluid mx-auto d-block zoom">
            </div>
        </div>

    </div>
</div>

<div class="next_previous flex-center mb-1 mt-1 offline_courses">
    <a href="#" class="Socialb plum-text previous">&laquo; Previous</a>
    <a href="#" class=" Socialb plum-text next">Next &raquo;</a>
</div>
<!--Description-->

<div class="plum-bg">
    <div class="col-12 col-sm-10 offset-md-1" style="padding: 40px 15px;line-height: 1;">
        <div class="row">
            <div class="col-12 col-sm-6">
                <!-- <div class="mb-1 white-text">Sign up to receive our weekly newsletter</div> -->
                <div class="white-text pull-right">Sign up to receive our weekly newsletter</div><br><br>
                <div class="white-text pull-right">Stay uploaded on all new online and offline training courses</div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="Subscription">
                    <input class="SubInput z-depth-1" type="text" placeholder="Enter your email"><span class="SubBtn"><a
                            href="#">Subscribe</a></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="to-top" title="back to top">↑</div>
@endsection
<!-- Latter Subscription End -->
