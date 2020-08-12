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

   <div class="bread-crumb-wrap ibc-wrap-6">
    <div class="container">
  <!--Title / Beadcrumb-->
         <div class="inner-page-title-wrap col-xs-12 col-md-12 col-sm-12">
            <div class="bread-heading"><h1>About Us</h1></div>
              <div class="bread-crumb pull-right">
              <ul>
              <li><a href="index.html">Home</a></li>
              <li><a href="about-us.html">About Us</a></li>
              </ul>
              </div>
          </div>
       </div>
   </div>

       <div class="container">






        <div class="row">

          <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 dept-tabs-wrap wow fadeInUp animated" data-wow-delay="1s" data-wow-offset="200">

            <!-- tabs left -->
            <div class="tabbable tabs-left">
              <ul class="nav nav-tabs col-md-4 col-sm-4 col-xs-5">
                <li class="active"><a href="#a" data-toggle="tab"><i class="icon-eye-open dept-tabs-icon"></i><span class="tabs-heads">Introduction</span><i class="right-arr"></i></a></li>
                <li><a href="#b" data-toggle="tab"><i class="icon-file dept-tabs-icon"></i><span class="tabs-heads">Plan of Action</span><i class="right-arr"></i></a></li>
                <li><a href="#c" data-toggle="tab"><i class="icon-globe dept-tabs-icon"></i><span class="tabs-heads">Mission</span><i class="right-arr"></i></a></li>
                <li><a href="#d" data-toggle="tab"><i class="icon-envelope dept-tabs-icon"></i><span class="tabs-heads">Vision</span><i class="right-arr"></i></a></li>
                <li><a href="#e" data-toggle="tab"><i class="icon-rocket dept-tabs-icon"></i><span class="tabs-heads">Objectives</span><i class="right-arr"></i></a></li>
                <li><a href="#f" data-toggle="tab"><i class="icon-search dept-tabs-icon"></i><span class="tabs-heads">Purpose</span><i class="right-arr"></i></a></li>
                <li><a href="#g" data-toggle="tab"><i class="icon-list-ul dept-tabs-icon"></i><span class="tabs-heads">Services</span><i class="right-arr"></i></a></li>
                <li><a href="#h" data-toggle="tab"><i class="icon-list-ul dept-tabs-icon"></i><span class="tabs-heads">Departments</span><i class="right-arr"></i></a></li>
<!--                  <li><a href="#i" data-toggle="tab"><i class="icon-hospital dept-tabs-icon"></i><span class="tabs-heads">Outpatient Surgery</span><i class="right-arr"></i></a></li>-->
<!--                  <li><a href="#j" data-toggle="tab"><i class="icon-ambulance dept-tabs-icon"></i><span class="tabs-heads">Ophthalmology Clinic</span><i class="right-arr"></i></a></li>-->
<!--                  <li><a href="#k" data-toggle="tab"><i class="icon-hospital dept-tabs-icon"></i><span class="tabs-heads">Cardiac Clinic</span><i class="right-arr"></i></a></li>-->
<!--                  <li><a href="#l" data-toggle="tab"><i class="icon-eye-open dept-tabs-icon"></i><span class="tabs-heads">Outpatient Surgery</span><i class="right-arr"></i></a></li>-->

              </ul>

              <div class="tab-content col-md-8 col-sm-8 col-xs-7 pull-right">

               <div class="fade tab-pane active in" id="a">
                  <div class="dept-title-tabs">Introduction</div>
                  <img alt="" class="img-responsive" src="http://themindxperts.com/images/Introduction.jpeg" />
                  <div class="dept-subtitle-tabs">The MindXperts</div>
                  <p>The MindXperts is a project owned by Psyche & The MindXperts Pvt Ltd,
                   which is a multi-dimensional and a visionary initiative to provide help
                   and facilitation to the masses through online counseling and psychotherapy.
                    The idea was created after having a thorough observation that our
                    society lacks general understanding and awareness regarding mental
                    health issues and their easy access to psychologists/Clinical Psychologists/mental
                     health practitioners.</p>
                     <p>The MindXperts is designed to fulfill this gap among mental health practitioner
                   with general public. This service is easily accessible and affordable for all
                    the people who are having stress related or any psychological issue.


                    It has also been designed to facilitate and promote the level of psychologists
                    for having their own forum to provide help and therapy.</p>
                 <p>Our selecting board mainly focused on those psychologists who are professional,
                  experienced and wholehearted as well to help people in need.
                  The MindXperts is consisting on a board of such professionals who are dedicated,
                   devoted and focused.</p>
                 <p>Psyche &The MindXperts is glad to share that we have also got international collaboration
                  from many different states such as USA, UK, India, Ireland, the professionals
                  of these states have joint hands to help and cure people from mental health issues.</p>








                  {{-- <p>MindXperts is a multidimensional and a visionary initiative to provide help and facilitation to the masses through online counseling and psychotherapy. The idea was created after having a thorough observation that our society lacks general understanding and awareness regarding mental health issues and their easy access to psychologists/Clinical Psychologists/mental health practitioners.
                  </p>

                  <p>MindXperts is designed to fulfill this gap among mental health practitioner with general public.
                      This service is easily accessible and affordable for all the people who are having stress related
                      or any psychological issue. It has also been designed to facilitate
                      and promote the level of psychologists for having their own forum to provide help and therapy. </p>

                  <p>Our selecting board mainly focused on those psychologists who are professional, experienced and wholehearted as well to help people in need. MindXperts is consisting on a board of such professionals who are dedicated, devoted and focused.</p>

                  <p>MindXperts is glad to share that we have also got international collaboration from many different states such as USA, UK, India, Ireland, the professionals of these states have joint hands to help and cure people from mental health issues. .</p>
 --}}



               </div>

               <div class="tab-pane fade" id="b">
               <div class="dept-title-tabs">Plan of Action</div>
                   <img alt="" class="img-responsive" src="http://themindxperts.com/images/Introduction.jpeg" />
                  <div class="dept-subtitle-tabs">Plan of Action</div>
                   <ul>
                       {{-- <li>To design a website particularly for psychologists/Mental health practitioners to work independently on a reliable forum</li> --}}
                       <li>To add skilled and well experienced clinical psychologists in our psychologist panel to work collaboratively as a team. </li>
                      <li>To have international collaborations with psychologists and mental health practitioners to increase the coordination and cohesiveness among different therapists working globally</li>
                      <li>To provide online counseling and psychotherapy services to the people having any mental health related issues</li>
                      <li>To Help the people in handling and managing their stress by providing online counseling</li>
                      <li>Spreading the mental health awareness to the masses by our mental health activists</li>
                      {{-- <li>Registering students from different fields i.e. (medical, psychology, social work, other) as mental health volunteers</li> --}}
                      {{-- <li>Mental health activists will work on these different domains
                      <ul>
                          <li>Awareness</li>
                          <li>Promotions</li>
                          <li>Building Clientage</li>
                      </ul> --}}


                      </li>
                      <li>Conducting workshops for activists and general public to build their capacity, (psychologists on panel will design a workshop plan).</li>
                      <li>	Registering more clinical psychologists/psychologists (national and international) to improve the standards</li>
                      <li>	Work to promote the psychological wellbeing of people around the globe</li>
                      <li>	Providing support and facilitate the psychologists to promote their expertise in the field</li>

                   </ul>
<!--                    <p>Holisticly expedite innovative manufactured products after highly efficient ROI. Energistically enhance adaptive results rather than functionalized experiences. Uniquely enhance web-enabled channels for high-payoff convergence. Synergistically myocardinate tactical materials rather than virtual resources. Competently facilitate exceptional sources with high-quality e-business.</p>-->

<!--                    <p>Globally scale cross-unit customer service after enterprise methods of empowerment. Progressively procrastinate magnetic strategic theme areas for inexpensive architectures. Dynamically revolutionize multifunctional markets vis-a-vis resource-leveling outsourcing. Compellingly re-engineer client-centered outsourcing vis-a-vis excellent data. Objectively maintain impactful e-commerce without principle-centered deliverables.</p>-->

<!--                    <p>Dynamically revolutionize 2.0 platforms rather than backend data. Competently deploy strategic opportunities without customized communities. Competently innovate alternative data whereas effective data. Collaboratively aggregate wireless vortals through front-end imperatives.</p>-->
<!--                    -->


                  </div>

               <div class="tab-pane fade" id="c">
               <div class="dept-title-tabs">Mission</div>
                   <img alt="" class="img-responsive" src="http://themindxperts.com/images/Introduction.jpeg" />
                  <div class="dept-subtitle-tabs">Mission</div>
                  <p>

                    To spread the knowledge and awareness regarding mental health issues and providing a platform to promote the standards of psychologists and their understandings regarding mental health issues.

                  </p>
                  {{-- <p>Globally scale cross-unit customer service after enterprise methods of empowerment. Progressively procrastinate magnetic strategic theme areas for inexpensive architectures. Dynamically revolutionize multifunctional markets vis-a-vis resource-leveling outsourcing. Compellingly re-engineer client-centered outsourcing vis-a-vis excellent data. Objectively maintain impactful e-commerce without principle-centered deliverables.</p>

                  <p>Dynamically revolutionize 2.0 platforms rather than backend data. Competently deploy strategic opportunities without customized communities. Competently innovate alternative data whereas effective data. Collaboratively aggregate wireless vortals through front-end imperatives.</p>

                  <p>Holisticly expedite innovative manufactured products after highly efficient ROI. Energistically enhance adaptive results rather than functionalized experiences. Uniquely enhance web-enabled channels for high-payoff convergence. Synergistically myocardinate tactical materials rather than virtual resources. Competently facilitate exceptional sources with high-quality e-business.</p>

                  <p>Appropriately disintermediate cost effective users and technically sound methods of empowerment. Conveniently revolutionize client-based experiences whereas standards compliant content. Collaboratively repurpose clicks-and-mortar communities after holistic infrastructures. Uniquely engage vertical paradigms without cross functional users. Phosfluorescently disseminate cutting-edge e-commerce through goal-oriented intellectual capital.</p> --}}



               </div>

               <div class="tab-pane fade" id="d">
               <div class="dept-title-tabs">Vision</div>
                   <img alt="" class="img-responsive" src="http://themindxperts.com/images/Introduction.jpeg" />
                  <div class="dept-subtitle-tabs">Vision</div>
                  <p>

                      To provide affordable and easily accessible online mental health services for all remote areas particularly in Pakistan and outside, enhance the general understanding regarding therapy & help to remove stigma.
                  </p>
                  {{-- <p>Competently leverage other's ethical networks vis-a-vis equity invested bandwidth. Intrinsicly evisculate distinctive process improvements with team building web-readiness. Dramatically deploy stand-alone results and value-added markets. Efficiently enhance leveraged.</p>

                  <p>Competently evisculate strategic testing procedures without intermandated channels. Energistically streamline bleeding-edge users without cooperative opportunities. Compellingly recaptiualize cross-platform opportunities through fully tested growth strategies. Holisticly enable seamless interfaces via diverse platforms. Conveniently reintermediate B2B systems with cooperative catalysts for change.</p>

                  <p>Seamlessly build turnkey functionalities vis-a-vis ubiquitous data. Efficiently negotiate effective schemas before high standards in users. Holisticly restore performance based e-markets for alternative testing procedures. Interactively whiteboard global relationships after technically sound initiatives. Monotonectally envisioneer mission-critical models rather than efficient communities.</p> --}}





               </div>

               <div class="tab-pane fade" id="e">
               <div class="dept-title-tabs">Objectives</div>
                  <img alt="" class="img-responsive" src="http://themindxperts.com/images/Introduction.jpeg" />
                  <div class="dept-subtitle-tabs">Objectives</div>
                  <p>•	Providing online services 24/7 round the clock</p>
                  <p>•	To promote the understanding of Psychology</p>
                    <p>•	To promote the level of Practicing Psychologists</p>
                        <p>•	Providing judgement free counseling</p>
                            <p>•	To give an easy and reliable access of Therapy/Counseling</p>
                                <p>•	To involve students as mental health activists/members to raise awareness regarding general and psychological issues.</p>

                  {{-- <p>Interactively leverage existing resource-leveling action items rather than enabled information. Professionally iterate visionary quality vectors via customized methods of empowerment. Synergistically optimize reliable imperatives with optimal processes. Monotonectally transform prospective ROI for inexpensive e-commerce. Monotonectally create orthogonal infrastructures via stand-alone methodologies.</p>

                  <p>Competently evisculate strategic testing procedures without intermandated channels. Energistically streamline bleeding-edge users without cooperative opportunities. Compellingly recaptiualize cross-platform opportunities through fully tested growth strategies. Holisticly enable seamless interfaces via diverse platforms. Conveniently reintermediate B2B systems with cooperative catalysts for change.</p>

                  <p>Seamlessly build turnkey functionalities vis-a-vis ubiquitous data. Efficiently negotiate effective schemas before high standards in users. Holisticly restore performance based e-markets for alternative testing procedures. Interactively whiteboard global relationships after technically sound initiatives. Monotonectally envisioneer mission-critical models rather than efficient communities.</p>

                  <p>Competently leverage other's ethical networks vis-a-vis equity invested bandwidth. Intrinsicly evisculate distinctive process improvements with team building web-readiness. Dramatically deploy stand-alone results and value-added markets. Efficiently enhance leveraged.</p> --}}



               </div>

               <div class="tab-pane fade" id="f">
               <div class="dept-title-tabs">Purpose</div>
                  <img alt="" class="img-responsive" src="images/dept-page-09.jpg" />
                  <div class="dept-subtitle-tabs">Purpose</div>
                  {{-- <p>Seamlessly build turnkey functionalities vis-a-vis ubiquitous data. Efficiently negotiate effective schemas before high standards in users. Holisticly restore performance based e-markets for alternative testing procedures. Interactively whiteboard global relationships after technically sound initiatives. Monotonectally envisioneer mission-critical models rather than efficient communities.</p>

                  <p>Competently leverage other's ethical networks vis-a-vis equity invested bandwidth. Intrinsicly evisculate distinctive process improvements with team building web-readiness. Dramatically deploy stand-alone results and value-added markets. Efficiently enhance leveraged.</p>
 --}}
                        <p>The main purpose to design this service is to promote the psychologists, help to modify the public perception towards psychology and to spread awareness regarding general and mental health related issues. Another important purpose of this platform is to conduct research on different national and international projects</p>
               </div>

               <div class="tab-pane fade" id="g">
               <div class="dept-title-tabs">Services</div>
                  <img alt="" class="img-responsive" src="images/dept-page-06.jpg" />
                  <div class="dept-subtitle-tabs">Services</div>
                  {{-- <p>Dynamically revolutionize 2.0 platforms rather than backend data. Competently deploy strategic opportunities without customized communities. Competently innovate alternative data whereas effective data. Collaboratively aggregate wireless vortals through front-end imperatives.</p>

                  <p>Holisticly expedite innovative manufactured products after highly efficient ROI. Energistically enhance adaptive results rather than functionalized experiences. Uniquely enhance web-enabled channels for high-payoff convergence. Synergistically myocardinate tactical materials rather than virtual resources. Competently facilitate exceptional sources with high-quality e-business.</p>

                  <p>Appropriately disintermediate cost effective users and technically sound methods of empowerment. Conveniently revolutionize client-based experiences whereas standards compliant content. Collaboratively repurpose clicks-and-mortar communities after holistic infrastructures. Uniquely engage vertical paradigms without cross functional users. Phosfluorescently disseminate cutting-edge e-commerce through goal-oriented intellectual capital.</p> --}}
                  <p>The MindXperts is designed to provide services which are as follow:</p>
                  <p>1.	Online Counseling  (Tele-therapy)</p>
                  <p>2.	Psychological Assessme</p>

                 <p>3.	Registering and capacity building of volunteers</p>
                  <p>4.	Mental Health/ Psychological Awareness</p>
                  <p>5.	Research</p>
                  <p>6.	Conduction of Workshops</p>




               </div>

               <div class="tab-pane fade" id="h">
               <div class="dept-title-tabs">Departments</div>
                  <img alt="" class="img-responsive" src="images/dept-page-07.jpg" />
                  <div class="dept-subtitle-tabs">Departments</div>
                  {{-- <p>Holisticly expedite innovative manufactured products after highly efficient ROI. Energistically enhance adaptive results rather than functionalized experiences. Uniquely enhance web-enabled channels for high-payoff convergence. Synergistically myocardinate tactical materials rather than virtual resources. Competently facilitate exceptional sources with high-quality e-business.</p>

                  <p>Appropriately disintermediate cost effective users and technically sound methods of empowerment. Conveniently revolutionize client-based experiences whereas standards compliant content. Collaboratively repurpose clicks-and-mortar communities after holistic infrastructures. Uniquely engage vertical paradigms without cross functional users. Phosfluorescently disseminate cutting-edge e-commerce through goal-oriented intellectual capital.</p>

                  <p>Seamlessly build turnkey functionalities vis-a-vis ubiquitous data. Efficiently negotiate effective schemas before high standards in users. Holisticly restore performance based e-markets for alternative testing procedures. Interactively whiteboard global relationships after technically sound initiatives. Monotonectally envisioneer mission-critical models rather than efficient communities.</p>

                  <p>Competently leverage other's ethical networks vis-a-vis equity invested bandwidth. Intrinsicly evisculate distinctive process improvements with team building web-readiness. Dramatically deploy stand-alone results and value-added markets. Efficiently enhance leveraged.</p> --}}



               </div>

               <div class="tab-pane fade" id="i">
               <div class="dept-title-tabs">Ophthalmology Clinic</div>
                  <img alt="" class="img-responsive" src="images/dept-page-08.jpg" />
                  <div class="dept-subtitle-tabs">Donec scelerisque leo</div>
                  <p>Energistically impact dynamic catalysts for change vis-a-vis real-time core competencies. Compellingly mesh one-to-one strategic theme areas rather than adaptive infrastructures. Intrinsicly initiate proactive potentialities after timely processes. Proactively target state of the art collaboration and idea-sharing vis-a-vis competitive models. Synergistically synergize empowered leadership via holistic bandwidth.</p>

                  <p>Synergistically drive proactive leadership with ubiquitous "outside the box" thinking. Synergistically deploy cross-unit potentialities for scalable core competencies. Competently exploit functional synergy via resource maximizing outsourcing. Energistically promote web-enabled infrastructures with adaptive data. Holisticly reintermediate </p>pandemic solutions after compelling e-markets.

                  <p>Authoritatively enable tactical services without leading-edge scenarios. Collaboratively exploit cooperative methodologies via installed base applications. Dynamically restore real-time.</p>



               </div>

               <div class="tab-pane fade" id="j">
               <div class="dept-title-tabs">Ophthalmology Clinic</div>
                  <img alt="" class="img-responsive" src="images/dept-page-09.jpg" />
                  <div class="dept-subtitle-tabs">Donec scelerisque leo</div>
                  <p>Progressively generate impactful web-readiness through maintainable growth strategies. Synergistically coordinate 24/7 total linkage rather than user friendly manufactured products. Progressively deliver granular technology without dynamic methods of empowerment. Phosfluorescently incubate cooperative "outside the box" thinking via holistic quality vectors. Efficiently develop B2C deliverables before stand-alone applications.</p>

                  <p>Energistically impact dynamic catalysts for change vis-a-vis real-time core competencies. Compellingly mesh one-to-one strategic theme areas rather than adaptive infrastructures. Intrinsicly initiate proactive potentialities after timely processes. Proactively target state of the art collaboration and idea-sharing vis-a-vis competitive models. Synergistically synergize empowered leadership via holistic bandwidth.</p>

                  <p>Synergistically drive proactive leadership with ubiquitous "outside the box" thinking. Synergistically deploy cross-unit potentialities for scalable core competencies. Competently exploit functional synergy via resource maximizing outsourcing. Energistically promote web-enabled infrastructures with adaptive data. Holisticly reintermediate </p>pandemic solutions after compelling e-markets.

                  <p>Authoritatively enable tactical services without leading-edge scenarios. Collaboratively exploit cooperative methodologies via installed base applications. Dynamically restore real-time.</p>



               </div>

               <div class="tab-pane fade" id="k">
               <div class="dept-title-tabs">Ophthalmology Clinic</div>
                  <img alt="" class="img-responsive" src="images/dept-page-10.jpg" />
                  <div class="dept-subtitle-tabs">Donec scelerisque leo</div>
                  <p>Synergistically drive proactive leadership with ubiquitous "outside the box" thinking. Synergistically deploy cross-unit potentialities for scalable core competencies. Competently exploit functional synergy via resource maximizing outsourcing. Energistically promote web-enabled infrastructures with adaptive data. Holisticly reintermediate </p>pandemic solutions after compelling e-markets.

                  <p>Authoritatively enable tactical services without leading-edge scenarios. Collaboratively exploit cooperative methodologies via installed base applications. Dynamically restore real-time.</p>

                  <p>Energistically impact dynamic catalysts for change vis-a-vis real-time core competencies. Compellingly mesh one-to-one strategic theme areas rather than adaptive infrastructures. Intrinsicly initiate proactive potentialities after timely processes. Proactively target state of the art collaboration and idea-sharing vis-a-vis competitive models. Synergistically synergize empowered leadership via holistic bandwidth.</p>



               </div>

               <div class="tab-pane fade" id="l">
               <div class="dept-title-tabs">Ophthalmology Clinic</div>
                  <img alt="" class="img-responsive" src="images/dept-page-01.jpg" />
                  <div class="dept-subtitle-tabs">Donec scelerisque leo</div>
                  <p>Seamlessly build turnkey functionalities vis-a-vis ubiquitous data. Efficiently negotiate effective schemas before high standards in users. Holisticly restore performance based e-markets for alternative testing procedures. Interactively whiteboard global relationships after technically sound initiatives. Monotonectally envisioneer mission-critical models rather than efficient communities.</p>

                  <p>Competently leverage other's ethical networks vis-a-vis equity invested bandwidth. Intrinsicly evisculate distinctive process improvements with team building web-readiness. Dramatically deploy stand-alone results and value-added markets. Efficiently enhance leveraged.</p>



               </div>

              </div>



              <div class="sidebar-wrap-dept col-md-4 col-sm-4 col-xs-12 no-pad wow fadeInUp animated" data-wow-delay="1s" data-wow-offset="200">

              <div class="appointment-form no-pad dept-form">

              </section>
                  </form>
            </div>

              <div class="dept-call-info wow fadeInUp animated idept-call" data-wow-delay="1s" data-wow-offset="200">
                       </div>
            </div>
            <!-- /tabs -->

          </div>



        </div><!-- /row -->






          </div>



       </div>
   </div>


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
