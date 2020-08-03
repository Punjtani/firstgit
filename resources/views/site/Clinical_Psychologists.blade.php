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

  <!--Mid Content Start-->


  <div class="about-intro-wrap pull-left">

      <div class="bread-crumb-wrap ibc-wrap-4">
          <div class="container">
              <!--Title / Beadcrumb-->
              <div class="inner-page-title-wrap col-xs-12 col-md-12 col-sm-12">
                  <div class="bread-heading"><h1>Clinical Psychologists</h1></div>
                  <div class="bread-crumb pull-right">
                      <ul>
                          <li><a href="index.html">Home</a></li>
                          <li><a href="about-us.html">Clinical Psychologists</a></li>
                      </ul>
                  </div>
              </div>
          </div>
      </div>

<!--        &lt;!&ndash;map&ndash;&gt;-->
<!--        <div class="pull-left map-full no-pad contact-v1-map">-->
<!--            <div id="map-canvas"></div>-->
<!--            <div class="map-shadow"></div>-->
<!--        </div>-->


       <div class="contact-widgets-wrap" style="
       margin-top: -41px;
   ">

          <div class="container ">

            <div class="row">

                <div class="contact-widgets">
                    <div class="contact-widget-box col-md-3 col-lg-3 col-sm-6 col-xs-12 wow bounceIn" data-wow-delay="0.5s" data-wow-offset="150">
                        <div class="fold-wrap"><i class="icon-globe cw-icon"></i></div>
                        <div class="contact-widget-title">Bank</div>
                        <p>Meezan Bank</p>
                    </div>
                    <div class="contact-widget-box col-md-3 col-lg-3 col-sm-6 col-xs-12 wow bounceIn" data-wow-delay="1.4s" data-wow-offset="150">
                        <div class="fold-wrap"><i class="fa fa-comments-o cw-icon"></i></div>
                        <div class="contact-widget-title">Account Title</div>
                        <p>Psyche & The Mind Experts </p>
                    </div>



                    <div class="contact-widget-box col-md-3 col-lg-3 col-sm-6 col-xs-12 wow bounceIn" data-wow-delay="0.8s" data-wow-offset="150">
                        <div class="fold-wrap"><i class="icon-phone2 cw-icon"></i></div>
                        <div class="contact-widget-title">Account IBAN</div>
                        <p> PK90MEZN0011350104544488</p>
                    </div>
                    <div class="contact-widget-box col-md-3 col-lg-3 col-sm-6 col-xs-12 wow bounceIn" data-wow-delay="0.8s" data-wow-offset="150">
                        <div class="fold-wrap"><i class="icon-phone2 cw-icon"></i></div>
                        <div class="contact-widget-title">Account #</div>
                        <p> 1135-0104544488</p>
                    </div>




                </div>

            </div>

          </div>

      </div>

      <div class="container">



          <!--About-us top-content-->

          <div class="row">


              <div class="col-xs-12 col-lg-12  col-sm-12 col-md-12 pull-left contact2-wrap no-pad">



                  <!--contact widgets-->
                  <div class="col-xs-12 col-lg-12 col-sm-12 col-md-12 pull-left no-pad">


                      <div class="subtitle col-xs-12 no-pad col-sm-12 col-md-12 pull-left news-sub icontact-widg">Registration Form</div>

                      <!--Contact form-->
                      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 no-pad wow fadeInUp" data-wow-delay="0.5s" data-wow-offset="160">

                          <div></div>

                          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 no-pad">

                              <div class="alert alert-success hidden" id="contactSuccess">
                                  <strong>Success!</strong> Your message has been sent to us.
                              </div>

                              <div class="alert alert-error hidden" id="contactError">
                                  <strong>Error!</strong> There was an error sending your message.
                              </div>

                              {{-- <form  id="course_form" method="POST" action="{{route('course_data')}}" enctype="multipart/form-data"> --}}

                              <form method="POST" action="{{route('add_clinical_psychologists')}}" enctype="multipart/form-data" class="contact2-page-form col-lg-12 col-sm-12 col-md-12 col-xs-12 no-pad contact-v1" id="">
                                @csrf

                                  <div class="col-lg-4 col-sm-12 col-md-4 col-xs-12 control-group">
                                    <label>Name</label>
                                    <input type="text" class="contact2-textbox" placeholder="Name*" name="name" id="name" />

                                  </div>

                                  <div class="col-lg-4 col-sm-12 col-md-4 col-xs-12 control-group">
                                    <label>Email</label>
                                    <input type="email" class="contact2-textbox" placeholder="Email*" name="email" id="email"/>
                                  </div>

                                  <div class="col-lg-4 col-sm-12 col-md-4 col-xs-12 control-group">
                                    <label>Contact Number</label>
                                      <input type="text" placeholder="Contact*" class="contact2-textbox" name="phone" id="contact">
                                  </div><div class="col-lg-4 col-sm-12 col-md-4 col-xs-12 control-group">
                                    <label>Age</label>
                                      <input type="text" placeholder="Age*" class="contact2-textbox" name="age" id="age">
                                  </div><div class="col-lg-4 col-sm-12 col-md-4 col-xs-12 control-group">
                                    <label>CNIC</label>
                                      <input type="text" placeholder="CNIC*" class="contact2-textbox" name="cnic" id="cnic">
                                  </div><div class="col-lg-4 col-sm-12 col-md-4 col-xs-12 control-group">
                                    <label>Qualification</label>
                                      <input type="text" placeholder="Qualification*" class="contact2-textbox" name="qualification" id="qualification">
                                  </div><div class="col-lg-4 col-sm-12 col-md-4 col-xs-12 control-group">
                                    <label>Experience</label>
                                      <input type="text" placeholder="Experience*" class="contact2-textbox" name="experience" id="experience">
                                  </div><div class="col-lg-4 col-sm-12 col-md-4 col-xs-12 control-group">
                                    <label>Specialty</label>
                                      <input type="text" placeholder="Specialty*" class="contact2-textbox" name="specialty" id="specialty">
                                  </div><div class="col-lg-4 col-sm-12 col-md-4 col-xs-12 control-group">
                                    <label>Bank Name</label>
                                      <input type="text" placeholder="Bank name*" class="contact2-textbox" name="bank_name" id="bank_name">
                                  </div><div class="col-lg-4 col-sm-12 col-md-4 col-xs-12 control-group">
                                    <label>Bank Account Number</label>
                                      <input type="text" placeholder="Acct num*" class="contact2-textbox" name="account_number" id="acct_num">
                                  </div><div class="col-lg-4 col-sm-12 col-md-4 col-xs-12 control-group">
                                    <label>Bank Deposit Slip Number</label>
                                      <input type="text" placeholder="Registration Fee*" class="contact2-textbox" name="bank_slip1" id="Registration">
                                  </div>
                                  <div class="col-lg-4 col-sm-12 col-md-4 col-xs-12 control-group">
                                    <label>Attach Bank Deposit Slip</label>
                                      <input type="file" placeholder="Upload Your CV*" class="contact2-textbox" name="bank_slip" id="bank_slip">
                                  </div>
                                  <div class="col-lg-4 col-sm-12 col-md-4 col-xs-12 control-group">
                                        <label>Attach Resume</label>
                                      <input type="file" placeholder="Upload Your CV*" class="contact2-textbox" name="cv" id="cv">
                                  </div>
                                  <div class="col-lg-4 col-sm-12 col-md-4 col-xs-12 control-group">
                                    <label>Document</label>
                                      <input type="file" placeholder="Upload Your CV*" class="contact2-textbox" name="docs" id="docs">
                                  </div>

<!--                                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">-->
<!--                                        <textarea class="contact2-textarea" placeholder="Reason of Appointment" name="message" id="message"></textarea>-->
<!--                                    </div>-->

                                  <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">

                                      <section class="color-7" id="btn-click">
                                          <button class="icon-mail btn2-st2 btn-7 btn-7b"  data-loading-text="Loading..." type="submit">Submit</button>

                                      </section></div>

                              </form>
                          </div>



                      </div>



                  </div>

              </div>

          </div>





      </div>

  </div>


  <!--Mid Content End-->


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
