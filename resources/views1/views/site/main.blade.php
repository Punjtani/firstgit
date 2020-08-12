@extends('layouts.app_site')
@section('style')




@endsection


@section('content')

<div class="complete-content">

<div class="container full-width-container ihome-banner">
    <div class="banner col-sm-12 col-xs-12 col-md-12">
        <ul class="slider-img">
            @foreach ($slides as $key=>$item)
            <li data-transition="papercut" data-slotamount="7">
                <img src="{{url('slider').'/'.$item['image']}}"  alt="slidebg1"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
                    <div class="tp-caption bluebg-t2 sfr skewtoright imed-sl1"
                        data-x="right"
                        data-y="222"
                        data-hoffset="-10"
                        data-speed="1000"
                        data-start="2900"
                        data-easing="Back.easeOut"
                        data-endspeed="400"
                        data-endeasing="Power1.easeIn"
                        >
                        <p style="background: #6cafaf00;font-size: 36px; margin-top: -57px;margin-right: 650px;color: antiquewhite;font-family: initial;">A Largest Network  of Professional Psychologists</p>

                    </div>
                    <div class="tp-caption bluebg-t1 sfr skewtoright imed-sl1"
                        data-x="right"
                        data-y="115"
                        data-hoffset="-60"
                        data-speed="1000"
                        data-start="2400"
                        data-easing="Back.easeOut"
                        data-endspeed="400"
                        data-endeasing="Power1.easeIn"
                        >
                        <p style="background: #6cafaf00;font-size: 36px;margin-right: 720px;color: antiquewhite;font-family: initial;">The Mind<span class="x">X</span>perts</p>
                    </div>

                    <!-- LAYER NR. 7 -->
                    <div class="tp-caption bluebg-t3 sfr skewtoright imed-sl1" style="display: none"
                        data-x="right"
                        data-y="280"
                        data-hoffset="-60"
                        data-speed="1000"
                        data-start="3400"
                        data-easing="Back.easeOut"
                        data-endspeed="400"
                        data-endeasing="Power1.easeIn"
                        >
                        ........................... <br> ...............................
                    </div>


                    <!-- LAYER NR. 8 -->
                    <div class="tp-caption s1-but customin skewtoright imed-sl1"
                        data-x="center"
                        data-y="365"
                        data-hoffset="205"
                        data-speed="1000"
                        data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0;scaleY:0;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
                        data-start="3900"
                        data-easing="Back.easeOut"
                        data-endspeed="400"
                        data-endeasing="Power1.easeIn"
                        >
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>



               <!--Icon Boxes 1 end-->
<div class="container txt_bg1">

                    <div class="row ">

                        <!--About-us top-content-->

                        <div class="col-md-2 col-sm-2 col-lg-2 col-xs-2 column-element"></div>
                        <div class="col-md-8 col-sm-8 col-lg-8 col-xs-8 column-element ">

                            <!--                            <h3>Introduction</h3>-->
                            <p style="
    font-size: 16px;
	color: black;

">The <B>
    MindXperts</B>  is a project owned by <b>Psyche & The MindXperts Pvt Ltd.</b>
The MindXperts is a multidimensional and a visionary initiative,
 created to provide help and facilitation to the masses through
 online counseling and psychotherapy. <br><br> Access to mental healthcare
  is a basic human right. It’s a well-known fact that our society
  lacks basic understanding and awareness of mental health and other
  issues linked to it. Even if one knows about the current issues, access to help was nearly impossible.
   This lead to the creation of this platform where we provide awareness regarding mental healthcare and issues,
   along with an easy access to psychologists and mental health practitioners.<br><br>

The  MindXperts is designed to fill this gap between mental health practitioners and  general public.
This service is easily accessible and affordable for
 all the people who are having stress related or any
 other psychological issues. It has also been designed
  to facilitate and promote the level of psychologists for
   having their own forum to provide help and therapy.<br><br>

The  MindXperts is glad to share that we have also got international collaborations from USA, UK, India and Ireland.  Professionals of these states have joined hands to help spread awareness on mental health and cure people from mental health issues.

                            </p>

                            <p style="
    font-size: 16px;
	color: black;
"></p>

                        </div>
                        <div class="col-md-2 col-sm-2 col-lg-2 col-xs-2 column-element"></div>


                    </div>
                </div>
        	<!--<div class="parallax-out wpb_row vc_row-fluid ihome-parallax txt_bg" style="background: #46b8da">-->
        	<div class=" wpb_row vc_row-fluid ihome-parallax txt_bg2" style="">


                    <div id="second" class="upb_row_bg vcpb-hz-jquery" style="background-position: -199px 0px;/* background-image: url(../images/list-background.png); */" data-upb_br_animation="" data-parallax_sense="30" data-bg-override="ex-full">

                          <div class="container">
                          	<div class="row">
                                   <!-- <div class="bg col-lg-4 col-sm-4 col-md-5 col-xs-12 notViewed wow fadeInUp" data-wow-delay="1.5s" data-wow-offset="200">
								   </div> -->
                                    <div class="float-left col-lg-7 col-sm-7 col-md-7 col-xs-12">

                                        <div class="iconlist-wrap" style="color: #414042;">
                                            <div class="subtitle notViewed wow fadeInRight" data-wow-delay="0.5s" data-wow-offset="20"><span class="iconlist-mid-title" style="color: #0c0c0c ;margin-left: 83px;"><u>Why  Choose Us</u> </span> </div>
                                                <ul>
                                                    <li class="notViewed wow fadeInDown" data-wow-delay="0.5s" data-wow-offset="50">
                                                    {{-- <i class="icon-hospital2 icon-list-icons" style="background: #ffffff00;/* font-size: 15px; */"> --}}
                                                        {{-- <img class="img-fluid" src="images/icon.png" style="
                                                        width: 84px;
                                                        height: 113px;
                                                    "> --}}
                                                    {{-- </i> --}}
                                                    <div class="iconlist-content">

                                                        <div class="iconlist-title" style=""> Great Infrastructure</div>
                                                        <p class="iconlist-text" style="color: #0c0c0c;font-size: 17px;">24 Hours Service With Best And Great Mental Health Practitioners </p>
                                                    </div>

                                                    </li>

                                                    <li class="notViewed wow fadeInDown" data-wow-delay="0.5s" data-wow-offset="60">
                                                    {{-- <i class="fa fa-user-md icon-list-icons" style="background: #ffffff00; /* font-size: 15px; */">
                                                        <img class="img-fluid" src="images/icon.png" style="
                                                        width: 84px;
                                                        height: 87px;
                                                        border-radius: 50%;
                                                        margin-top: -33px;
                                                        margin-left: -8px;

                                                    ">
                                                    </i> --}}
                                                    <div class="iconlist-content">

                                                        <div class="iconlist-title" style="color: #0c0c0c;font-size: 21px; font-weight: bold;">Qualified Mental Health Practitioners</div>
                                                        <p class="iconlist-text" style="color: #0c0c0c;font-size: 17px;">International AND National Psychologists/Clinical Psychologists . </p>
                                                    </div>

                                                    </li>

                                                </ul>
                                        </div>
                                    </div>
                                    </div> <!--.story-->
                           </div>
                    </div> <!--#second-->

                </div>
                <div class="v-height">
                <div class="txt_bg3 img-fluid" style="">

                <div class="container">

                    <div class="row">

                        <!--About-us top-content-->

                        <div class="col-md-2 col-sm-2 col-lg-2 col-xs-2 "></div>
                        <div class="col-md-8 col-sm-8 col-lg-8 col-xs-8 ">

                                                        <h3 class="v-text"><b><u>Vision</u></b></h3>

                            <p class="v-text">To provide affordable and easily accessible online mental health services for all remote areas particularly in Pakistan and outside, enhance the general understanding regarding therapy & help to remove stigma.
                            </p>

                        </div>
                        <div class="col-md-2 col-sm-2 col-lg-2 col-xs-2 "></div>


                    </div>
                </div>

                <div class="container">

                    <div class="row">

                        <!--About-us top-content-->

                        <div class="col-md-2 col-sm-2 col-lg-2 col-xs-2 "></div>
                        <div class="col-md-8 col-sm-8 col-lg-8 col-xs-8 ">

                                                        <h3 class="v-text"><b><u>Mission</u></b></h3>

                            <p class="v-text">To spread the knowledge and awareness regarding mental health issues and providing a platform to promote the standards of psychologists and their understandings regarding mental health issues.
                            </p>

     </div>
                        <div class="col-md-2 col-sm-2 col-lg-2 col-xs-2 "></div>


                    </div>
                </div>
                </div>
            </div>


            <!--Purchase Box-->
         <div class="purchase-wrap-blue ipurchase-wrap" style="
     background-image: url(images/bar_pic1.png);
">
            	<div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 pull-right">
                        <div class="purchase-strip-blue pull-right col-sm-12 col-md-12 col-xs-12 pull-left notViewed wow fadeInUp animated" data-wow-delay="0.5s" data-wow-offset="150" style="visibility: visible;-webkit-animation-delay: 0.5s; -moz-animation-delay: 0.5s; animation-delay: 0.5s;">
                        <div class="purchase-strip-text" style="
    color: #414042;
">The MindXperts  <span class="ipurcahse-strip-text" style="
    color: #414042;
">PROFESSIONALS</span>  FOR YOU !</div>
                        <div class="color-4">
                            <p class="ipurchase-paragraph">
<!--                                <button class="btn btn-4 btn-4a notViewed wow fadeInUp" data-wow-delay="0.5s" data-wow-offset="150">Register Now</button>-->
                                <a href="contact-3.html" class="btn btn-4 btn-4a notViewed wow fadeInUp animated" data-wow-delay="0.5s" data-wow-offset="150" style="visibility: visible;-webkit-animation-delay: 0.5s; -moz-animation-delay: 0.5s; animation-delay: 0.5s;">Book My Session</a>
                            </p>
                        </div>

                        </div>

                     </div>
                  </div>
                  </div>
             </div>


            <div class="container">
            <div class="row">


                </div>
                </div>

           </div>
           <div>

</div>

@endsection

@section('scripts')
<script>

</script>
@endsection
