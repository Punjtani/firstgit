@extends('layouts.main')
@section('online-course')
    <style>
        .carousel-item {
            position: relative;
        }

        .main_bg_color {
            background-color: #570055;
        }

        .carousel_item_header_icon {
            overflow: hidden;
            position: absolute;
            top: 4px;
            right: 15px;
            padding: 0px 5px;
        }

        .carousel_item_header_icon p {
            background-color: black;
            padding: 3px 5px;
        }

        .carousel_item_header_icon i {
            font-size: 24px;
            color: white;
        }

        .newcard {
            margin-bottom: 2%;
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

        .carousel_item_footer div p:nth-child(1) {
            font-size: 14px;
        }

        .carousel_item_footer div p:nth-child(2) {
            font-size: 8px;
        }

        .carousel_item_footer div p {
            margin: 0px;
        }

    </style>

    <div class="flex-center mb-1 mt-1 offline_courses">
        {{--    {{dd($category)}}--}}

        <u><a class="Socialb plum-text" href="#">{{$category['name']}} Courses</a></u>
    </div>


    <div class="container">
        <div class="row">
            <!-- start block -->
            @foreach($data as $c)
                <div class="col-sm-12 col-md-4 newcard">
                    <div class="card">
                        <a href="{{route('detail-course',['id'=>$c->id])}}">

                            <img class="card-img-top" src="{{asset('course/'.$c->thumbnail)}}" alt="Card image cap">
                        </a>
                    </div>
                    <div class="col carousel_item_footer main_bg_color" style="color: white">
                        <div class="row">
                            <div class="col-2">
                                <div style="width:45px;height:45px;">
                                    <img class="rounded-circle full-img" src="{{asset('users/'.$c->users['image'])}}">
                                </div>
                            </div>
                            <div class="col-6 pr-0">
                                <div class="trainer-n mb-1">
                                    <b>{{$c->users['name']}}</b>
                                </div>
                                <div class="course-d">
                                    {{$c->name}}
                                    -{{$c->lessons->count()}} Courses in 1 <br>
                                    {!! $c->description !!}


                                </div>
                            </div>
                            <div class="col-4 text-center p-0">
                                <ul class="course-rating-list">
                                    <li>
                                        <img src="{{asset('assets/frontend/img/star-small-fill.png')}}">
                                    </li>
                                    <li>
                                        <img src="{{asset('assets/frontend/img/star-small-fill.png')}}">
                                    </li>
                                    <li>
                                        <img src="{{asset('assets/frontend/img/star-small-fill.png')}}">
                                    </li>
                                    <li>
                                        <img src="{{asset('assets/frontend/img/star-small-fill.png')}}">
                                    </li>
                                    <li>
                                        <img src="{{asset('assets/frontend/img/star-small.png')}}">
                                    </li>
                                </ul>
                                <ul class="course-rating-list">
                                    <li>
                                        <span>4.8</span>
                                    </li>
                                    <li>
                                        (123)
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
        @endforeach
        <!-- blocek close -->

        </div>
    </div>


    <!--/.First slide-->









    <div class="pagina-footer">
        <nav aria-label="Page navigation example">
            <ul class="pagination pg-blue">
                <li class="page-item ">
                    {{$data->links()}}
                </li>

                {{--    <li class="page-item ">--}}
                {{--      <a class="page-link" tabindex="-1">Previous</a>--}}
                {{--    </li>--}}
                {{--    <li class="page-item"><a class="page-link">1</a></li>--}}
                {{--    <li class="page-item active">--}}
                {{--      <a class="page-link">2 <span class="sr-only">(current)</span></a>--}}
                {{--    </li>--}}
                {{--    <li class="page-item"><a class="page-link">3</a></li>--}}
                {{--    <li class="page-item ">--}}
                {{--      <a class="page-link">Next</a>--}}
                {{--    </li>--}}
            </ul>
        </nav>
    </div>
    <!-- <div class="next_previous flex-center mb-1 mt-1 offline_courses">

    <a href="#" class="Socialb plum-text ">{{$data->links()}}</a>
  <a href="#" class=" Socialb plum-text next">Next &raquo;</a>
</div> -->
    <!--Description-->

    <div class="plum-bg">
        <div class="col-12 col-sm-10 offset-md-1" style="padding: 40px 15px;line-height: 1;">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <!-- <div class="mb-1 white-text">Sign up to receive our weekly newsletter</div> -->
                    <div class="white-text pull-right">Sign up to receive our weekly newsletter</div>
                    <br><br>
                    <div class="white-text pull-right">Stay uploaded on all new online and offline training courses
                    </div>
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
    <!-- Latter Subscription End -->
@endsection
