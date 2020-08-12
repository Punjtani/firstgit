@extends('layouts.app_site')
@section('style')


{box-sizing:border-box}

/* Slideshow container */
.slideshow-container {
  max-width: 1000px;
  position: relative;
  margin: auto;
}

/* Hide the images by default */
.mySlides {
  display: none;
}

/* Next & previous buttons */
.prev, .next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  margin-top: -22px;
  padding: 16px;
  color: white;
  font-weight: bold;
  font-size: 18px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
}

/* Position the "next button" to the right */
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover, .next:hover {
  background-color: rgba(0,0,0,0.8);
}

/* Caption text */
.text {
  color: #f2f2f2;
  font-size: 15px;
  padding: 8px 12px;
  position: absolute;
  bottom: 8px;
  width: 100%;
  text-align: center;
}

/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

/* The dots/bullets/indicators */
.dot {
  cursor: pointer;
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
  transition: background-color 0.6s ease;
}

.active, .dot:hover {
  background-color: #717171;
}

/* Fading animation */
.fade {
  -webkit-animation-name: fade;
  -webkit-animation-duration: 1.5s;
  animation-name: fade;
  animation-duration: 1.5s;
}

@-webkit-keyframes fade {
  from {opacity: .4}
  to {opacity: 1}
}

@keyframes fade {
  from {opacity: .4}
  to {opacity: 1}
}
@endsection


@section('content')

<section class="complete-content content-footer-space">


    <div class="bread-crumb-wrap ibc-wrap-3">
        <div class="container">
            <!--Title / Beadcrumb-->
            <div class="inner-page-title-wrap col-xs-12 col-md-12 col-sm-12">
                <div class="bread-heading"><h1>Join Us</h1></div>
                <div class="bread-crumb pull-right">
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="about-us.html">Join Us</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container iflip">
        <div class="row">

            <div class="col-md-12 col-xs-12 col-sm-12 pull-left subtitle ibg-transparent">Join Us</div>

            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">

                <div class="flip-box-wrap">
                    <div class="flip-box auto horizontal_flip_left" data-animation="" data-animation-delay="03" >
                        <div class="ifb-flip-box">

                            <div class="ifb-face ifb-front">
                                <div class="flip-box-icon">
                                    <div class="aio-icon none " data-animation="" data-animation-delay="03">
                                        <img class="img-fluid" src="images/pd.png" style="width: 161px;
                                        height: 94px;">
                                    </div>
                                </div>

                                <h3 class="flip-box-head-txt">Clinical Psychologists/Psychologists  </h3>
                                <p>Join us for better future and a moral cause,vast opportunities and limitless benefits await you.</p>
                            </div><!-- END .front -->

                            <div class="ifb-face ifb-back flip-backface">
                                <h3>Clinical Psychologists/Psychologists</h3>
                                <p>Join us for better future and a moral cause,vast opportunities and limitless benefits await you.</p>
                                <div class="flip_link"><a href="{{url('Clinical-Psychologists')}}" class="flip-read-more">Register</a></div>
                            </div><!-- END .back -->

                        </div> <!-- ifb-flip-box -->
                    </div> <!-- flip-box -->
                </div>
            </div>


            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">

                <div class="flip-box-wrap">
                    <div class="flip-box auto horizontal_flip_left" data-animation="" data-animation-delay="03" >
                        <div class="ifb-flip-box">

                            <div class="ifb-face ifb-front">
                                <div class="flip-box-icon">
                                    <div class="aio-icon none " data-animation="" data-animation-delay="03">
                                        <!-- <i class="flip-icons icon-female"></i> -->
										 <img class="img-fluid" src="images/sasa.png" style="width: 121px;height: 94px;">

                                    </div>
                                </div>

                                <h3 class="flip-box-head-txt">Psychiatrists</h3>
                                <p>we welcome you to a platform worth your capabilities and excellency join us and explore a new world of opportunities.</p>
                            </div><!-- END .front -->

                            <div class="ifb-face ifb-back flip-backface">
                                <h3>Psychiatrists</h3>
                                <p>we welcome you to a platform worth your capabilities and excellency join us and explore a new world of opportunities.</p>
                                <div class="flip_link"><a href="{{url('Psychiatrists')}}" class="flip-read-more">Register</a></div>
                            </div><!-- END .back -->

                        </div> <!-- ifb-flip-box -->
                    </div> <!-- flip-box -->
                </div>
            </div>

            <!-- <div>Icons made by <a href="https://www.flaticon.com/authors/smashicons" title="Smashicons">Smashicons</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div> -->


            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">

                <div class="flip-box-wrap">
                    <div class="flip-box auto horizontal_flip_left" data-animation="" data-animation-delay="03" >
                        <div class="ifb-flip-box">

                            <div class="ifb-face ifb-front">
                                <div class="flip-box-icon">
                                    <div class="aio-icon none " data-animation="" data-animation-delay="03">
                                        <img class="img-fluid" src="images/ph.png" style="
                                        width: 84px;
                                        height: 113px;
                                    ">
                                    </div>
                                </div>

                                <h3 class="flip-box-head-txt">Mental Health Activists</h3>
                                <p>A wide network of Professionals are waiting
                                    for you to learn alongside all the other
                                    golden opportunities</p>
                            </div><!-- END .front -->

                            <div class="ifb-face ifb-back flip-backface">
                                <h3>Mental Health Activists</h3>
                                <p>A wide network of Professionals are waiting
                                    for you to learn alongside all the other
                                    golden opportunities</p>
                                <div class="flip_link"><a href="{{url('mental-health-volunteers')}}" class="flip-read-more">Register</a></div>
                            </div><!-- END .back -->

                        </div> <!-- ifb-flip-box -->
                    </div> <!-- flip-box -->
                </div>
            </div>




<!--            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">-->

<!--                <div class="flip-box-wrap">-->
<!--                    <div class="flip-box auto horizontal_flip_left" data-animation="" data-animation-delay="03" >-->
<!--                        <div class="ifb-flip-box">-->

<!--                            <div class="ifb-face ifb-front">-->
<!--                                <div class="flip-box-icon">-->
<!--                                    <div class="aio-icon none " data-animation="" data-animation-delay="03">-->
<!--                                        <i class="flip-icons icon-female"></i>-->
<!--                                    </div>-->
<!--                                </div>-->

<!--                                <h3 class="flip-box-head-txt">Caring Staff</h3>-->
<!--                                <p>Built with you in mind, it's super clean, sleek and simply pleasure to use.</p>-->
<!--                            </div>&lt;!&ndash; END .front &ndash;&gt;-->

<!--                            <div class="ifb-face ifb-back flip-backface">-->
<!--                                <h3>Caring Staff</h3>-->
<!--                                <p>lass aptent taciti sociosqu ad litora torquent per conubia nostra, per himenaeos per vestibulum.</p>-->
<!--                                <div class="flip_link"><a href="" class="flip-read-more">Read More!</a></div>-->
<!--                            </div>&lt;!&ndash; END .back &ndash;&gt;-->

<!--                        </div> &lt;!&ndash; ifb-flip-box &ndash;&gt;-->
<!--                    </div> &lt;!&ndash; flip-box &ndash;&gt;-->
<!--                </div>-->
<!--            </div>-->





        </div>


<!--        <div class="row">-->

<!--            <div class="col-md-12 col-xs-12 col-sm-12 pull-left subtitle ibg-transparent">With Animation</div>-->

<!--            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">-->

<!--                <div class="flip-box-wrap wow flipInY" data-wow-delay="0.5s" data-wow-offset="10">-->
<!--                    <div class="flip-box auto horizontal_flip_left" data-animation="" data-animation-delay="03" >-->
<!--                        <div class="ifb-flip-box">-->

<!--                            <div class="ifb-face ifb-front">-->
<!--                                <div class="flip-box-icon">-->
<!--                                    <div class="aio-icon none " data-animation="" data-animation-delay="03">-->
<!--                                        <i class="flip-icons icon-heart"></i>-->
<!--                                    </div>-->
<!--                                </div>-->

<!--                                <h3 class="flip-box-head-txt">Cardio Monitoring</h3>-->
<!--                                <p>Built with you in mind, it's super clean, sleek and simply pleasure to use.</p>-->
<!--                            </div>&lt;!&ndash; END .front &ndash;&gt;-->

<!--                            <div class="ifb-face ifb-back flip-backface">-->
<!--                                <h3>Cardio Monitoring</h3>-->
<!--                                <p>lass aptent taciti sociosqu ad litora torquent per conubia nostra, per himenaeos per vestibulum.</p>-->
<!--                                <div class="flip_link"><a href="" class="flip-read-more">Read More!</a></div>-->
<!--                            </div>&lt;!&ndash; END .back &ndash;&gt;-->

<!--                        </div> &lt;!&ndash; ifb-flip-box &ndash;&gt;-->
<!--                    </div> &lt;!&ndash; flip-box &ndash;&gt;-->
<!--                </div>-->
<!--            </div>-->


<!--            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">-->

<!--                <div class="flip-box-wrap wow flipInY" data-wow-delay="0.8s" data-wow-offset="10">-->
<!--                    <div class="flip-box auto horizontal_flip_left" data-animation="" data-animation-delay="03" >-->
<!--                        <div class="ifb-flip-box">-->

<!--                            <div class="ifb-face ifb-front">-->
<!--                                <div class="flip-box-icon">-->
<!--                                    <div class="aio-icon none " data-animation="" data-animation-delay="03">-->
<!--                                        <i class="flip-icons icon-ambulance"></i>-->
<!--                                    </div>-->
<!--                                </div>-->

<!--                                <h3 class="flip-box-head-txt">24 x 7 Service</h3>-->
<!--                                <p>Built with you in mind, it's super clean, sleek and simply pleasure to use.</p>-->
<!--                            </div>&lt;!&ndash; END .front &ndash;&gt;-->

<!--                            <div class="ifb-face ifb-back flip-backface">-->
<!--                                <h3>24 x 7 Service</h3>-->
<!--                                <p>lass aptent taciti sociosqu ad litora torquent per conubia nostra, per himenaeos per vestibulum.</p>-->
<!--                                <div class="flip_link"><a href="" class="flip-read-more">Read More!</a></div>-->
<!--                            </div>&lt;!&ndash; END .back &ndash;&gt;-->

<!--                        </div> &lt;!&ndash; ifb-flip-box &ndash;&gt;-->
<!--                    </div> &lt;!&ndash; flip-box &ndash;&gt;-->
<!--                </div>-->
<!--            </div>-->




<!--            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">-->

<!--                <div class="flip-box-wrap wow flipInY" data-wow-delay="1.1s" data-wow-offset="10">-->
<!--                    <div class="flip-box auto horizontal_flip_left" data-animation="" data-animation-delay="03" >-->
<!--                        <div class="ifb-flip-box">-->

<!--                            <div class="ifb-face ifb-front">-->
<!--                                <div class="flip-box-icon">-->
<!--                                    <div class="aio-icon none " data-animation="" data-animation-delay="03">-->
<!--                                        <i class="flip-icons icon-stethoscope"></i>-->
<!--                                    </div>-->
<!--                                </div>-->

<!--                                <h3 class="flip-box-head-txt">Medical Treatment</h3>-->
<!--                                <p>Built with you in mind, it's super clean, sleek and simply pleasure to use.</p>-->
<!--                            </div>&lt;!&ndash; END .front &ndash;&gt;-->

<!--                            <div class="ifb-face ifb-back flip-backface">-->
<!--                                <h3>Medical Treatment</h3>-->
<!--                                <p>lass aptent taciti sociosqu ad litora torquent per conubia nostra, per himenaeos per vestibulum.</p>-->
<!--                                <div class="flip_link"><a href="" class="flip-read-more">Read More!</a></div>-->
<!--                            </div>&lt;!&ndash; END .back &ndash;&gt;-->

<!--                        </div> &lt;!&ndash; ifb-flip-box &ndash;&gt;-->
<!--                    </div> &lt;!&ndash; flip-box &ndash;&gt;-->
<!--                </div>-->
<!--            </div>-->




<!--            <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">-->

<!--                <div class="flip-box-wrap wow flipInY" data-wow-delay="1.4s" data-wow-offset="10">-->
<!--                    <div class="flip-box auto horizontal_flip_left" data-animation="" data-animation-delay="03" >-->
<!--                        <div class="ifb-flip-box">-->

<!--                            <div class="ifb-face ifb-front">-->
<!--                                <div class="flip-box-icon">-->
<!--                                    <div class="aio-icon none " data-animation="" data-animation-delay="03">-->
<!--                                        <i class="flip-icons icon-female"></i>-->
<!--                                    </div>-->
<!--                                </div>-->

<!--                                <h3 class="flip-box-head-txt">Caring Staff</h3>-->
<!--                                <p>Built with you in mind, it's super clean, sleek and simply pleasure to use.</p>-->
<!--                            </div>&lt;!&ndash; END .front &ndash;&gt;-->

<!--                            <div class="ifb-face ifb-back flip-backface">-->
<!--                                <h3>Caring Staff</h3>-->
<!--                                <p>lass aptent taciti sociosqu ad litora torquent per conubia nostra, per himenaeos per vestibulum.</p>-->
<!--                                <div class="flip_link"><a href="" class="flip-read-more">Read More!</a></div>-->
<!--                            </div>&lt;!&ndash; END .back &ndash;&gt;-->

<!--                        </div> &lt;!&ndash; ifb-flip-box &ndash;&gt;-->
<!--                    </div> &lt;!&ndash; flip-box &ndash;&gt;-->
<!--                </div>-->
<!--            </div>-->





<!--        </div>-->


    </div>


    <!--Footer Start-->

</section>

@endsection

@section('scripts')
<script>
var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
}
</script>
@endsection
