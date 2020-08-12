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
</div>
 
           
           
           <!--Icon Boxes 1-->
<!--            <div class="container">-->
<!--            	<div class="row">-->
<!--            	<div class="no-pad icon-boxes-1"> -->
<!--                -->
<!--                	&lt;!&ndash;Icon-box-start&ndash;&gt;-->
<!--                    <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">-->
<!--                     <div class="icon-box-3 wow fadeInUp" data-wow-delay="0.6s" data-wow-offset="150">-->
<!--                    	<div class="icon-boxwrap2"><i class="fa fa-medkit icon-box-back2"></i></div>-->
<!--                        <div class="icon-box2-title">24 hour Service</div>-->
<!--                        <p>.</p>-->
<!--                        &lt;!&ndash;<section class="color-10">-->
<!--                            <nav class="cl-effect-10">-->
<!--                            <a href="#" data-hover="Read More"><span>Read More</span></a>-->
<!--                            </nav>-->
<!--                        </section>&ndash;&gt;-->
<!--                        <div class="iconbox-readmore"><a href="#">Read More</a></div>-->
<!--                     </div>   -->
<!--                    </div>-->
<!--                    -->
<!--                    &lt;!&ndash;Icon-box-start&ndash;&gt;-->
<!--                    <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">-->
<!--                     <div class="icon-box-3 wow fadeInDown" data-wow-delay="0.9s" data-wow-offset="150">-->
<!--                    	<div class="icon-boxwrap2"><i data-icon="\e609" class="icon-stethoscope icon-box-back2"></i></div>-->
<!--                        <div class="icon-box2-title">Health Care Solutions</div>-->
<!--                        <p>.</p>-->
<!--                        &lt;!&ndash;<section class="color-10">-->
<!--                            <nav class="cl-effect-10">-->
<!--                            <a href="#" data-hover="Read More"><span>Read More</span></a>-->
<!--                            </nav>-->
<!--                        </section>&ndash;&gt;-->
<!--                        <div class="iconbox-readmore"><a href="#">Read More</a></div>-->
<!--                     </div>   -->
<!--                    </div>-->
<!--                    -->
<!--                    &lt;!&ndash;Icon-box-start&ndash;&gt;-->
<!--                    <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">-->
<!--                     <div class="icon-box-3 wow fadeInUp" data-wow-delay="1.2s" data-wow-offset="150">-->
<!--                    	<div class="icon-boxwrap2"><i class="icon-ambulance icon-box-back2"></i></div>-->
<!--                        <div class="icon-box2-title">Advanced Technology</div>-->
<!--                        <p>.</p>-->
<!--                        &lt;!&ndash;<section class="color-10">-->
<!--                            <nav class="cl-effect-10">-->
<!--                            <a href="#" data-hover="Read More"><span>Read More</span></a>-->
<!--                            </nav>-->
<!--                        </section>&ndash;&gt;-->
<!--                        <div class="iconbox-readmore"><a href="#">Read More</a></div>-->
<!--                     </div>   -->
<!--                    </div>-->
<!--                    -->
<!--                    &lt;!&ndash;Icon-box-start&ndash;&gt;-->
<!--                    <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">-->
<!--                     <div class="icon-box-3 notViewed wow fadeInUp" data-wow-delay="1.5s" data-wow-offset="150">-->
<!--                    	<div class="icon-boxwrap2"><i class="fa fa-clock-o icon-box-back2"></i></div>-->
<!--                         <div class="icon-box2-title">Opening Hours</div>-->
<!--                        <ul>-->
<!--                        <li>Monday - Friday <span class="ibox-right-span">8.00  - 18.00</span></li>-->
<!--                        <li>Saturday <span class="ibox-right-span">8.00  - 16.00</span></li>-->
<!--                        <li>Sunday <span class="ibox-right-span">8.00 - 13.00</span></li>-->
<!--                        </ul>-->
<!--                     </div>   -->
<!--                    </div>-->
<!--                -->
<!--                </div>-->
<!--                </div>-->
<!--            </div>-->
               <!--Icon Boxes 1 end-->

               <div class="container txt_bg1">

                   <div class="row ">

                       <!--About-us top-content-->

                       <div class="col-md-2 col-sm-2 col-lg-2 col-xs-2 column-element"></div>
                       <div class="col-md-8 col-sm-8 col-lg-8 col-xs-8 column-element " >

                           <!--                            <h3>Introduction</h3>-->
                           <p style="
   font-size: 16px;
   color: black;

">MindXperts is a multidimensional and a visionary initiative to provide help and facilitation
                               to the masses through online counseling and psychotherapy.
                               The idea was created after having a thorough observation that
                               our society lacks general understanding and awareness
                               regarding mental health issues and their easy access to
                               psychologists/Clinical Psychologists/mental health practitioners.
                           </p>

                           <!--                            <p>MindXperts is designed to fulfill this gap among mental health practitioner with general public.-->
                           <!--                                This service is easily accessible and affordable for all the people who are having stress related-->
                           <!--                                or any psychological issue. It has also been designed to facilitate-->
                           <!--                                and promote the level of psychologists for having their own forum to provide help and therapy. </p>-->

                           <!--                            <p>Our selecting board mainly focused on those psychologists who are professional, experienced and wholehearted as well to help people in need. MindXperts is consisting on a board of such professionals who are dedicated, devoted and focused.</p>-->

                           <p style="
   font-size: 16px;
   color: black;
">MindXperts is glad to share that we have also got international collaboration from many different states such as USA, UK, India, Ireland, the professionals of these states have joint hands to help and cure people from mental health issues. .</p>

                       </div>
                       <div class="col-md-2 col-sm-2 col-lg-2 col-xs-2 column-element"></div>


<!--                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 column-element">-->

<!--                            <h3>Vision</h3>-->
<!--                            <p>Competently leverage other's ethical networks vis-a-vis equity invested bandwidth. Intrinsicly evisculate distinctive process improvements with team building web-readiness. Dramatically deploy stand-alone results and value-added markets. Efficiently enhance leveraged.</p>-->

<!--                            <p>Competently evisculate strategic testing procedures without intermandated channels. Energistically streamline bleeding-edge users without cooperative opportunities. Compellingly recaptiualize cross-platform opportunities through fully tested growth strategies. Holisticly enable seamless interfaces via diverse platforms. Conveniently reintermediate B2B systems with cooperative catalysts for change.</p>-->

<!--                            <p>Seamlessly build turnkey functionalities vis-a-vis ubiquitous data. Efficiently negotiate effective schemas before high standards in users. Holisticly restore performance based e-markets for alternative testing procedures. Interactively whiteboard global relationships after technically sound initiatives. Monotonectally envisioneer mission-critical models rather than efficient communities.</p>-->

<!--                        </div>-->

<!--                        <div class="col-md-6 col-sm-6' col-lg-6 col-xs-6 column-element">-->

<!--                            <h3>Mission</h3>-->
<!--                            <p>Globally scale cross-unit customer service after enterprise methods of empowerment. Progressively procrastinate magnetic strategic theme areas for inexpensive architectures. Dynamically revolutionize multifunctional markets vis-a-vis resource-leveling outsourcing. Compellingly re-engineer client-centered outsourcing vis-a-vis excellent data. Objectively maintain impactful e-commerce without principle-centered deliverables.</p>-->

<!--                            <p>Dynamically revolutionize 2.0 platforms rather than backend data. Competently deploy strategic opportunities without customized communities. Competently innovate alternative data whereas effective data. Collaboratively aggregate wireless vortals through front-end imperatives.</p>-->

<!--                            <p>Holisticly expedite innovative manufactured products after highly efficient ROI. Energistically enhance adaptive results rather than functionalized experiences. Uniquely enhance web-enabled channels for high-payoff convergence. Synergistically myocardinate tactical materials rather than virtual resources. Competently facilitate exceptional sources with high-quality e-business.</p>-->

<!--                            <p>Appropriately disintermediate cost effective users and technically sound methods of empowerment. Conveniently revolutionize client-based experiences whereas standards compliant content. Collaboratively repurpose clicks-and-mortar communities after holistic infrastructures. Uniquely engage vertical paradigms without cross functional users. Phosfluorescently disseminate cutting-edge e-commerce through goal-oriented intellectual capital.</p>-->

<!--                        </div>-->
                   </div>
               </div>
           
           <!--<div class="parallax-out wpb_row vc_row-fluid ihome-parallax txt_bg" style="background: #46b8da">-->
           <div class=" wpb_row vc_row-fluid ihome-parallax txt_bg2" style="
         <?php
           echo "background-image: url(/public/slider"."/".$chose['image'].");";
           ?>
           background-position: center;
           object-fit: cover;
           background-repeat: no-repeat;
           background-size:100%;
       ">
             
               
                   <div id="second" class="upb_row_bg vcpb-hz-jquery" style="background-position: -199px 0px;/* background-image: url(../images/list-background.png); */" data-upb_br_animation="" data-parallax_sense="30" data-bg-override="ex-full">
                   
                         <div class="container">
                             <div class="row">
                                  <div class="bg col-lg-4 col-sm-4 col-md-5 col-xs-12 notViewed wow fadeInUp" data-wow-delay="1.5s" data-wow-offset="200"></div>
                                   <div class="float-right col-lg-7 col-sm-7 col-md-7 col-xs-12">
                                       
                                       <div class="iconlist-wrap" style="color: #414042;">
                                           <div class="subtitle notViewed wow fadeInRight" data-wow-delay="0.5s" data-wow-offset="20"><span class="iconlist-mid-title" style="color: #0c0c0c"> Why  Choose Us</span> </div>
                                               <ul>
                                                   <li class="notViewed wow fadeInDown" data-wow-delay="0.5s" data-wow-offset="50">
                                                   <i class="icon-hospital2 icon-list-icons" style="background: #25a87e;/* font-size: 15px; */"></i>
                                                   <div class="iconlist-content">
                                                       
                                                       <div class="iconlist-title" style=""> Great Infrastructure</div>
                                                       <p class="iconlist-text" style="color: #0c0c0c;font-size: 17px;">24 Hours Service With Best And Great Mental Health Practitioners </p>
                                                   </div>
                                                   
                                                   </li>
                                                   
                                                   <li class="notViewed wow fadeInDown" data-wow-delay="0.5s" data-wow-offset="60">
                                                   <i class="fa fa-user-md icon-list-icons" style="background: #25a87e; /* font-size: 15px; */"></i>
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

                       <div class="col-md-2 col-sm-2 col-lg-2 col-xs-2 column-element"></div>
                       <div class="col-md-8 col-sm-8 col-lg-8 col-xs-8 column-element">

                                                       <h3 class="white-text">Vision</h3>
<!--                            <p style="-->
<!--    font-size: 16px;-->
<!--">Competently leverage other's ethical networks vis-a-vis equity invested bandwidth. Intrinsicly evisculate distinctive process improvements with team building web-readiness. Dramatically deploy stand-alone results and value-added markets. Efficiently enhance leveraged.</p>-->

<!--                            <p style="-->
<!--    font-size: 16px;-->
<!--">Competently evisculate strategic testing procedures without intermandated channels. Energistically streamline bleeding-edge users without cooperative opportunities. Compellingly recaptiualize cross-platform opportunities through fully tested growth strategies. Holisticly enable seamless interfaces via diverse platforms. Conveniently reintermediate B2B systems with cooperative catalysts for change.</p>-->

<!--                            <p style="-->
<!--    font-size: 16px;-->
<!--">Seamlessly build turnkey functionalities vis-a-vis ubiquitous data. Efficiently negotiate effective schemas before high standards in users. Holisticly restore performance based e-markets for alternative testing procedures. Interactively whiteboard global relationships after technically sound initiatives. Monotonectally envisioneer mission-critical models rather than efficient communities.</p>-->

                           <p class="v-text">To provide affordable and easily accessible online mental health services for all remote areas particularly in Pakistan and outside, enhance the general understanding regarding therapy & help to remove stigma.
                           </p>

                           <!--                            <p>MindXperts is designed to fulfill this gap among mental health practitioner with general public.-->
                           <!--                                This service is easily accessible and affordable for all the people who are having stress related-->
                           <!--                                or any psychological issue. It has also been designed to facilitate-->
                           <!--                                and promote the level of psychologists for having their own forum to provide help and therapy. </p>-->

                           <!--                            <p>Our selecting board mainly focused on those psychologists who are professional, experienced and wholehearted as well to help people in need. MindXperts is consisting on a board of such professionals who are dedicated, devoted and focused.</p>-->

<!--                            <p style="-->
<!--    font-size: 16px;-->
<!--">MindXperts is glad to share that we have also got international collaboration from many different states such as USA, UK, India, Ireland, the professionals of these states have joint hands to help and cure people from mental health issues. .</p>-->

                       </div>
                       <div class="col-md-2 col-sm-2 col-lg-2 col-xs-2 column-element"></div>
                       

                       <!--                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 column-element">-->

                       <!--                            <h3>Vision</h3>-->
                       <!--                            <p>Competently leverage other's ethical networks vis-a-vis equity invested bandwidth. Intrinsicly evisculate distinctive process improvements with team building web-readiness. Dramatically deploy stand-alone results and value-added markets. Efficiently enhance leveraged.</p>-->

                       <!--                            <p>Competently evisculate strategic testing procedures without intermandated channels. Energistically streamline bleeding-edge users without cooperative opportunities. Compellingly recaptiualize cross-platform opportunities through fully tested growth strategies. Holisticly enable seamless interfaces via diverse platforms. Conveniently reintermediate B2B systems with cooperative catalysts for change.</p>-->

                       <!--                            <p>Seamlessly build turnkey functionalities vis-a-vis ubiquitous data. Efficiently negotiate effective schemas before high standards in users. Holisticly restore performance based e-markets for alternative testing procedures. Interactively whiteboard global relationships after technically sound initiatives. Monotonectally envisioneer mission-critical models rather than efficient communities.</p>-->

                       <!--                        </div>-->

                       <!--                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 column-element">-->

                       <!--                            <h3>Mission</h3>-->
                       <!--                            <p>Globally scale cross-unit customer service after enterprise methods of empowerment. Progressively procrastinate magnetic strategic theme areas for inexpensive architectures. Dynamically revolutionize multifunctional markets vis-a-vis resource-leveling outsourcing. Compellingly re-engineer client-centered outsourcing vis-a-vis excellent data. Objectively maintain impactful e-commerce without principle-centered deliverables.</p>-->

                       <!--                            <p>Dynamically revolutionize 2.0 platforms rather than backend data. Competently deploy strategic opportunities without customized communities. Competently innovate alternative data whereas effective data. Collaboratively aggregate wireless vortals through front-end imperatives.</p>-->

                       <!--                            <p>Holisticly expedite innovative manufactured products after highly efficient ROI. Energistically enhance adaptive results rather than functionalized experiences. Uniquely enhance web-enabled channels for high-payoff convergence. Synergistically myocardinate tactical materials rather than virtual resources. Competently facilitate exceptional sources with high-quality e-business.</p>-->

                       <!--                            <p>Appropriately disintermediate cost effective users and technically sound methods of empowerment. Conveniently revolutionize client-based experiences whereas standards compliant content. Collaboratively repurpose clicks-and-mortar communities after holistic infrastructures. Uniquely engage vertical paradigms without cross functional users. Phosfluorescently disseminate cutting-edge e-commerce through goal-oriented intellectual capital.</p>-->

                       <!--                        </div>-->
                   </div>
               </div>
          
               <div class="container">

                   <div class="row">

                       <!--About-us top-content-->

                       <div class="col-md-2 col-sm-2 col-lg-2 col-xs-2 column-element"></div>
                       <div class="col-md-8 col-sm-8 col-lg-8 col-xs-8 column-element">

                                                       <h3 class="white-text">Mission</h3>
<!--                            <p style="-->
<!--    font-size: 16px;-->
<!--">Competently leverage other's ethical networks vis-a-vis equity invested bandwidth. Intrinsicly evisculate distinctive process improvements with team building web-readiness. Dramatically deploy stand-alone results and value-added markets. Efficiently enhance leveraged.</p>-->

<!--                            <p style="-->
<!--    font-size: 16px;-->
<!--">Competently evisculate strategic testing procedures without intermandated channels. Energistically streamline bleeding-edge users without cooperative opportunities. Compellingly recaptiualize cross-platform opportunities through fully tested growth strategies. Holisticly enable seamless interfaces via diverse platforms. Conveniently reintermediate B2B systems with cooperative catalysts for change.</p>-->

<!--                            <p style="-->
<!--    font-size: 16px;-->
<!--">Seamlessly build turnkey functionalities vis-a-vis ubiquitous data. Efficiently negotiate effective schemas before high standards in users. Holisticly restore performance based e-markets for alternative testing procedures. Interactively whiteboard global relationships after technically sound initiatives. Monotonectally envisioneer mission-critical models rather than efficient communities.</p>-->

                           <p class="v-text">To spread the knowledge and awareness regarding mental health issues and providing a platform to promote the standards of psychologists and their understandings regarding mental health issues.
                           </p>

                           <!--                            <p>MindXperts is designed to fulfill this gap among mental health practitioner with general public.-->
                           <!--                                This service is easily accessible and affordable for all the people who are having stress related-->
                           <!--                                or any psychological issue. It has also been designed to facilitate-->
                           <!--                                and promote the level of psychologists for having their own forum to provide help and therapy. </p>-->

                           <!--                            <p>Our selecting board mainly focused on those psychologists who are professional, experienced and wholehearted as well to help people in need. MindXperts is consisting on a board of such professionals who are dedicated, devoted and focused.</p>-->

<!--                            <p style="-->
<!--    font-size: 16px;-->
<!--">MindXperts is glad to share that we have also got international collaboration from many different states such as USA, UK, India, Ireland, the professionals of these states have joint hands to help and cure people from mental health issues. .</p>-->

                       </div>
                       <div class="col-md-2 col-sm-2 col-lg-2 col-xs-2 column-element"></div>


                       <!--                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 column-element">-->

                       <!--                            <h3>Vision</h3>-->
                       <!--                            <p>Competently leverage other's ethical networks vis-a-vis equity invested bandwidth. Intrinsicly evisculate distinctive process improvements with team building web-readiness. Dramatically deploy stand-alone results and value-added markets. Efficiently enhance leveraged.</p>-->

                       <!--                            <p>Competently evisculate strategic testing procedures without intermandated channels. Energistically streamline bleeding-edge users without cooperative opportunities. Compellingly recaptiualize cross-platform opportunities through fully tested growth strategies. Holisticly enable seamless interfaces via diverse platforms. Conveniently reintermediate B2B systems with cooperative catalysts for change.</p>-->

                       <!--                            <p>Seamlessly build turnkey functionalities vis-a-vis ubiquitous data. Efficiently negotiate effective schemas before high standards in users. Holisticly restore performance based e-markets for alternative testing procedures. Interactively whiteboard global relationships after technically sound initiatives. Monotonectally envisioneer mission-critical models rather than efficient communities.</p>-->

                       <!--                        </div>-->

                       <!--                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 column-element">-->

                       <!--                            <h3>Mission</h3>-->
                       <!--                            <p>Globally scale cross-unit customer service after enterprise methods of empowerment. Progressively procrastinate magnetic strategic theme areas for inexpensive architectures. Dynamically revolutionize multifunctional markets vis-a-vis resource-leveling outsourcing. Compellingly re-engineer client-centered outsourcing vis-a-vis excellent data. Objectively maintain impactful e-commerce without principle-centered deliverables.</p>-->

                       <!--                            <p>Dynamically revolutionize 2.0 platforms rather than backend data. Competently deploy strategic opportunities without customized communities. Competently innovate alternative data whereas effective data. Collaboratively aggregate wireless vortals through front-end imperatives.</p>-->

                       <!--                            <p>Holisticly expedite innovative manufactured products after highly efficient ROI. Energistically enhance adaptive results rather than functionalized experiences. Uniquely enhance web-enabled channels for high-payoff convergence. Synergistically myocardinate tactical materials rather than virtual resources. Competently facilitate exceptional sources with high-quality e-business.</p>-->

                       <!--                            <p>Appropriately disintermediate cost effective users and technically sound methods of empowerment. Conveniently revolutionize client-based experiences whereas standards compliant content. Collaboratively repurpose clicks-and-mortar communities after holistic infrastructures. Uniquely engage vertical paradigms without cross functional users. Phosfluorescently disseminate cutting-edge e-commerce through goal-oriented intellectual capital.</p>-->

                       <!--                        </div>-->
                   </div>
               </div>
               </div>
           </div>     
<!--                <div class="container">-->

<!--                    <div class="row">-->

<!--                        &lt;!&ndash;About-us top-content&ndash;&gt;-->

<!--&lt;!&ndash;                        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 column-element">&ndash;&gt;-->

<!--&lt;!&ndash;                            <h3>Introduction</h3>&ndash;&gt;-->
<!--&lt;!&ndash;                            <p>MindXperts is a multidimensional and a visionary initiative to provide help and facilitation to the masses through online counseling and psychotherapy. The idea was created after having a thorough observation that our society lacks general understanding and awareness regarding mental health issues and their easy access to psychologists/Clinical Psychologists/mental health practitioners.&ndash;&gt;-->
<!--&lt;!&ndash;                            </p>&ndash;&gt;-->

<!--&lt;!&ndash;                            <p>MindXperts is designed to fulfill this gap among mental health practitioner with general public.&ndash;&gt;-->
<!--&lt;!&ndash;                                This service is easily accessible and affordable for all the people who are having stress related&ndash;&gt;-->
<!--&lt;!&ndash;                                or any psychological issue. It has also been designed to facilitate&ndash;&gt;-->
<!--&lt;!&ndash;                                and promote the level of psychologists for having their own forum to provide help and therapy. </p>&ndash;&gt;-->

<!--&lt;!&ndash;                            <p>Our selecting board mainly focused on those psychologists who are professional, experienced and wholehearted as well to help people in need. MindXperts is consisting on a board of such professionals who are dedicated, devoted and focused.</p>&ndash;&gt;-->

<!--&lt;!&ndash;                            <p>MindXperts is glad to share that we have also got international collaboration from many different states such as USA, UK, India, Ireland, the professionals of these states have joint hands to help and cure people from mental health issues. .</p>&ndash;&gt;-->

<!--&lt;!&ndash;                        </div>&ndash;&gt;-->


<!--                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 column-element">-->

<!--                            <h3>Vision</h3>-->
<!--                            <p>Competently leverage other's ethical networks vis-a-vis equity invested bandwidth. Intrinsicly evisculate distinctive process improvements with team building web-readiness. Dramatically deploy stand-alone results and value-added markets. Efficiently enhance leveraged.</p>-->

<!--                            <p>Competently evisculate strategic testing procedures without intermandated channels. Energistically streamline bleeding-edge users without cooperative opportunities. Compellingly recaptiualize cross-platform opportunities through fully tested growth strategies. Holisticly enable seamless interfaces via diverse platforms. Conveniently reintermediate B2B systems with cooperative catalysts for change.</p>-->

<!--                            <p>Seamlessly build turnkey functionalities vis-a-vis ubiquitous data. Efficiently negotiate effective schemas before high standards in users. Holisticly restore performance based e-markets for alternative testing procedures. Interactively whiteboard global relationships after technically sound initiatives. Monotonectally envisioneer mission-critical models rather than efficient communities.</p>-->

<!--                        </div>-->

<!--                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 column-element">-->

<!--                            <h3>Mission</h3>-->
<!--                            <p>Globally scale cross-unit customer service after enterprise methods of empowerment. Progressively procrastinate magnetic strategic theme areas for inexpensive architectures. Dynamically revolutionize multifunctional markets vis-a-vis resource-leveling outsourcing. Compellingly re-engineer client-centered outsourcing vis-a-vis excellent data. Objectively maintain impactful e-commerce without principle-centered deliverables.</p>-->

<!--                            <p>Dynamically revolutionize 2.0 platforms rather than backend data. Competently deploy strategic opportunities without customized communities. Competently innovate alternative data whereas effective data. Collaboratively aggregate wireless vortals through front-end imperatives.</p>-->

<!--                            <p>Holisticly expedite innovative manufactured products after highly efficient ROI. Energistically enhance adaptive results rather than functionalized experiences. Uniquely enhance web-enabled channels for high-payoff convergence. Synergistically myocardinate tactical materials rather than virtual resources. Competently facilitate exceptional sources with high-quality e-business.</p>-->

<!--                            <p>Appropriately disintermediate cost effective users and technically sound methods of empowerment. Conveniently revolutionize client-based experiences whereas standards compliant content. Collaboratively repurpose clicks-and-mortar communities after holistic infrastructures. Uniquely engage vertical paradigms without cross functional users. Phosfluorescently disseminate cutting-edge e-commerce through goal-oriented intellectual capital.</p>-->

<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->

           
           <!--Purchase Box-->
           
           <div class="purchase-wrap-blue ipurchase-wrap" style="
    background-image: url(images/bar_pic1.png);
">
               <div class="container">
               <div class="row">
                   <div class="col-xs-12 col-sm-12 col-md-12 pull-right"> 
                       <div class="purchase-strip-blue pull-right col-sm-12 col-md-12 col-xs-12 pull-left notViewed wow fadeInUp" data-wow-delay="0.5s" data-wow-offset="150">
                       <div class="purchase-strip-text" style="
   color: #414042;
">The MindXperts  <span class="ipurcahse-strip-text" style="
   color: #414042;
">PROFESSIONALS</span>  FOR YOU !</div>
                       <div class="color-4">
                           <p class="ipurchase-paragraph">
<!--                                <button class="btn btn-4 btn-4a notViewed wow fadeInUp" data-wow-delay="0.5s" data-wow-offset="150">Register Now</button>-->
                               <a href="contact-3.html" class="btn btn-4 btn-4a notViewed wow fadeInUp" data-wow-delay="0.5s" data-wow-offset="150">Book My Session</a>
                           </p>
                       </div>
                       
                       </div>
                       
                    </div>
                 </div>   
                 </div>
            </div>    
           <!--Purchase Box-->
           
           
            <div class="container">
            <div class="row">
            
            
            <!--Latest Post Start-->
            
<!--                 <div class="col-xs-12 col-sm-12 col-md-6 pull-left">-->
<!--                 -->
<!--                    <div class="latest-post-wrap pull-left wow fadeInLeft" data-wow-delay="0.5s" data-wow-offset="100">-->
<!--                        <div class="subtitle col-xs-12 no-pad col-sm-11 col-md-12 pull-left news-sub">latest news</div>-->
<!--                        -->
<!--                        &lt;!&ndash;Post item&ndash;&gt;-->
<!--                        <div class="post-item-wrap pull-left col-sm-6 col-md-12 col-xs-12">-->
<!--                            <img src="images/news-1.jpg" class="img-responsive post-author-img" alt="" />-->
<!--                            	<div class="post-content1 pull-left col-md-9 col-sm-9 col-xs-8">-->
<!--        	                        <div class="post-title pull-left"><a href="#">Etiam tristique sagittis pulvinar</a></div>-->
<!--        	                        <div class="post-meta-top pull-left">-->
<!--        	                            <ul>-->
<!--        	                            <li><i class="icon-calendar"></i>25 DEC 2013</li>-->
<!--        	                            <li><a href="#"><i class="icon-comments"></i>3</a></li>-->
<!--        	                            </ul>-->
<!--        	                        </div>-->
<!--                                </div>-->
<!--                                <div class="post-content2 pull-left">                   -->
<!--                                	<p>Integer iaculis egestas odio, vel dictum turpis placerat id elle se nisl eget odio eleifend, nec blandit libero porta aliquam vel veh dui nam sit amet ultricies sapien.<br />-->
<!--                                	<span class="post-meta-bottom"><a href="">Continue Reading...</a></span></p>-->
<!--                         		</div>-->
<!--                         </div>-->
<!--                         -->
<!--                         &lt;!&ndash;Post item&ndash;&gt;-->
<!--                        <div class="post-item-wrap pull-left col-sm-6 col-md-12 col-xs-12">-->
<!--                            <img src="images/news-2.jpg" class="img-responsive post-author-img" alt="" />-->
<!--                            <div class="post-content1 pull-left col-md-9 col-sm-9 col-xs-8">-->
<!--        	                        <div class="post-title pull-left"><a href="#">Etiam tristique sagittis pulvinar</a></div>-->
<!--        	                        <div class="post-meta-top pull-left">-->
<!--        	                            <ul>-->
<!--        	                            <li><i class="icon-calendar"></i>25 DEC 2013</li>-->
<!--        	                            <li><a href="#"><i class="icon-comments"></i>3</a></li>-->
<!--        	                            </ul>-->
<!--        	                        </div>-->
<!--                                </div>-->
<!--                                <div class="post-content2 pull-left">                   -->
<!--                                	<p>Integer iaculis egestas odio, vel dictum turpis placerat id elle se nisl eget odio eleifend, nec blandit libero porta aliquam vel veh dui nam sit amet ultricies sapien.<br />-->
<!--                                	<span class="post-meta-bottom"><a href="">Continue Reading...</a></span></p>-->
<!--                         		</div>-->
<!--                         </div>-->
<!--                         -->
<!--                         <a href="#" class="dept-details-butt posts-showall">Show All</a>-->
<!--                            -->
<!--                        </div>-->
<!--                    </div>-->
                  
                
                
                
                <!--Latest Post End-->
            
                <!--Departments Start-->
                
<!--                    <div class="col-xs-12 col-sm-12 col-md-6 pull-right department-wrap wow fadeInRight" data-wow-delay="0.5s" data-wow-offset="100">-->
<!--                    -->
<!--                    <div class="subtitle pull-left">Departments</div>-->
<!--                        -->
<!--                        <div id="imedica-dep-accordion">-->
<!--                            &lt;!&ndash; Accordion Item &ndash;&gt;-->
<!--                            <h3><i class="icon-ambulance dept-icon"></i><span class="dep-txt">Primary Health Care</span></h3>-->
<!--                            <div>-->
<!--                                -->
<!--                                <img src="images/dep-dummy.jpg" class="img-responsive dept-author-img-desk col-md-4" alt="" />-->
<!--                                <div class="dept-content pull-left col-md-7 col-lg-8">-->
<!--                                <div class="dept-title pull-left">Donec scelerisque, leo non eleifend</div> -->
<!--                                <p>Lorem ipsum dolor sit amet, consecte tur adipiscing elitut eu nisl quis augue suscipit dignissim. Duis vulputate nisl sit amet feugiat tincidunt. amet, consecte tur adipiscing elitut eu ni.</p>-->
<!--                                -->
<!--                                -->
<!--                                <a href="#" class="dept-details-butt">Details</a>-->
<!--                                <div class="purchase-strip-blue dept-apponit-butt"><div class="color-4">-->
<!--                                    <p class="ipurchase-paragraph">-->
<!--                                        <button class="icon-calendar btn btn-4 btn-4a notViewed">Appointment</button>-->
<!--                                    </p>-->
<!--                                </div></div>-->
<!--                                -->
<!--                                <div class="vspacer"></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            -->
<!--                            &lt;!&ndash; Accordion Item &ndash;&gt;-->
<!--                            <h3><i class="icon-stethoscope dept-icon"></i><span class="dep-txt">Outpatient Rehab</span></h3>-->
<!--                            <div>-->
<!--                                <img src="images/dept-01.jpg" class="img-responsive dept-author-img-desk col-md-4" alt="" />-->
<!--                                <div class="dept-content pull-left col-md-7 col-lg-8">-->
<!--                                <div class="dept-title pull-left">Donec scelerisque, leo non eleifend</div> -->
<!--                                <p>Lorem ipsum dolor sit amet, consecte tur adipiscing elitut eu nisl quis augue suscipit dignissim. Duis vulputate nisl sit amet feugiat tincidunt. amet, consecte tur adipiscing elitut eu ni.</p>-->
<!--                                -->
<!--                                <a href="#" class="dept-details-butt">Details</a>-->
<!--                                <div class="purchase-strip-blue dept-apponit-butt"><div class="color-4">-->
<!--                                    <p class="ipurchase-paragraph">-->
<!--                                        <button class="icon-calendar btn btn-4 btn-4a notViewed">Appointment</button>-->
<!--                                    </p>-->
<!--                                </div></div>-->
<!--                                <div class="vspacer"></div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            -->
<!--                            &lt;!&ndash; Accordion Item &ndash;&gt;-->
<!--                            <h3><i class="icon-heart dept-icon"></i><span class="dep-txt">Ophthalmology Clinic</span></h3>-->
<!--                            <div>-->
<!--                               <img src="images/dept-02.jpg" class="img-responsive dept-author-img-desk col-md-4" alt="" />-->
<!--                                <div class="dept-content pull-left col-md-7 col-lg-8">-->
<!--                                <div class="dept-title pull-left">Donec scelerisque, leo non eleifend</div> -->
<!--                                <p>Lorem ipsum dolor sit amet, consecte tur adipiscing elitut eu nisl quis augue suscipit dignissim. Duis vulputate nisl sit amet feugiat tincidunt. amet, consecte tur adipiscing elitut eu ni.</p>-->
<!--                                -->
<!--                                <a href="#" class="dept-details-butt">Details</a>-->
<!--                                <div class="purchase-strip-blue dept-apponit-butt"><div class="color-4">-->
<!--                                    <p class="ipurchase-paragraph">-->
<!--                                        <button class="icon-calendar btn btn-4 btn-4a notViewed">Appointment</button>-->
<!--                                    </p>-->
<!--                                </div></div>-->
<!--                                <div class="vspacer"></div>-->
<!--                                </div>                -->
<!--                            </div>-->
<!--                            -->
<!--                            &lt;!&ndash; Accordion Item &ndash;&gt;-->
<!--                            <h3><i class="icon-stethoscope dept-icon"></i><span class="dep-txt">Outpatient Surgery</span></h3>-->
<!--                            <div>-->
<!--                                <img src="images/dept-03.jpg" class="img-responsive dept-author-img-desk col-md-4" alt="" />-->
<!--                                <div class="dept-content pull-left col-md-7 col-lg-8">-->
<!--                                <div class="dept-title pull-left">Donec scelerisque, leo non eleifend Donec scelerisque, leo non eleifend</div> -->
<!--                                <p>Lorem ipsum dolor sit amet, consecte tur adipiscing elitut eu nisl quis augue suscipit dignissim. Duis vulputate nisl sit amet feugiat tincidunt. amet, consecte tur adipiscing elitut eu ni.</p>-->
<!--                                -->
<!--                                <a href="#" class="dept-details-butt">Details</a>-->
<!--                                <div class="purchase-strip-blue dept-apponit-butt"><div class="color-4">-->
<!--                                    <p class="ipurchase-paragraph">-->
<!--                                        <button class="icon-calendar btn btn-4 btn-4a notViewed">Appointment</button>-->
<!--                                    </p>-->
<!--                                </div></div>-->
<!--                                <div class="vspacer"></div>-->
<!--                                </div>                -->
<!--                            </div>-->
<!--                            -->
<!--                            &lt;!&ndash; Accordion Item &ndash;&gt;-->
<!--                            <h3><i class="icon-medkit dept-icon"></i><span class="dep-txt">Cardiac Clinic</span></h3>-->
<!--                            <div>-->
<!--                                <img src="images/dept-04.jpg" class="img-responsive dept-author-img-desk col-md-4" alt="" /> -->
<!--                                <div class="dept-content pull-left col-md-7 col-lg-8">-->
<!--                                <div class="dept-title pull-left">Donec scelerisque, leo non eleifend</div> -->
<!--                                <p>Lorem ipsum dolor sit amet, consecte tur adipiscing elitut eu nisl quis augue suscipit dignissim. Duis vulputate nisl sit amet feugiat tincidunt. amet, consecte tur adipiscing elitut eu ni.</p>-->
<!--                                -->
<!--                                <a href="#" class="dept-details-butt">Details</a>-->
<!--                                <div class="purchase-strip-blue dept-apponit-butt"><div class="color-4">-->
<!--                                    <p class="ipurchase-paragraph">-->
<!--                                        <button class="icon-calendar btn btn-4 btn-4a notViewed">Appointment</button>-->
<!--                                    </p>-->
<!--                                </div></div>-->
<!--                                <div class="vspacer"></div>-->
<!--                                </div>                -->
<!--                            </div>-->
<!--                            -->
<!--                            &lt;!&ndash; Accordion Item &ndash;&gt;-->
<!--                            <h3 class="last-child-ac ilast-child-acc"><i class="icon-heart dept-icon"></i><span class="dep-txt">Primary Health Care</span></h3>-->
<!--                            <div>-->
<!--                               <img src="images/dept-05.jpg" class="img-responsive dept-author-img-desk col-md-4" alt="" />-->
<!--                                <div class="dept-content pull-left col-md-7 col-lg-8">-->
<!--                                <div class="dept-title pull-left">Donec scelerisque, leo non eleifend</div> -->
<!--                                <p>Lorem ipsum dolor sit amet, consecte tur adipiscing elitut eu nisl quis augue suscipit dignissim. Duis vulputate nisl sit amet feugiat tincidunt. amet, consecte tur adipiscing elitut eu ni.</p>-->
<!--                                -->
<!--                                <a href="#" class="dept-details-butt">Details</a>-->
<!--                                <div class="purchase-strip-blue dept-apponit-butt"><div class="color-4">-->
<!--                                    <p class="ipurchase-paragraph">-->
<!--                                        <button class="icon-calendar btn btn-4 btn-4a notViewed">Appointment</button>-->
<!--                                    </p>-->
<!--                                </div></div>-->
<!--                                <div class="vspacer"></div>-->
<!--                                </div>                -->
<!--                            </div>-->
<!--                            -->
<!--                        </div>-->
<!--                        -->
<!--                        -->
<!--                    </div>-->
            
                <!--Departments End-->
                </div>
                </div>
                
       <!--Counter Start-->
           
<!--            <div class="Counter-wrap" id="counters">-->
<!--            -->
<!--            <div id="third" class="back-color-holder">-->
<!--            	<div class="container">-->
<!--                <div class="row">-->
<!--                -->
<!--                -->
<!--                <div class="banner-bottom-text2 no-pad col-xs-12 wow fadeInDown" data-wow-delay="0.5s" data-wow-offset="100">-->
<!--                -->
<!--                <div class="subtitle">About The MindXperts</div>-->
<!--        	    -->
<!--        	    </div>-->
<!--                -->
<!--                -->
<!--                	&lt;!&ndash;Counter Box&ndash;&gt;-->
<!--                    <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">-->
<!--                    	<div class="counter-box">-->
<!--                        	-->
<!--                            <div class="counter-style" id="myTargetElement"></div>-->
<!--                            <div class="counter-lable">Doctors</div>-->
<!--                            -->
<!--                        </div>-->
<!--                    </div>-->
<!--                    -->
<!--                    &lt;!&ndash;Counter Box&ndash;&gt;-->
<!--                    <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">-->
<!--                    	<div class="counter-box">-->
<!--                        	-->
<!--                            <div class="counter-style" id="myTargetElement2"></div>-->
<!--                            <div class="counter-lable">Clinic Rooms</div>-->
<!--                            -->
<!--                        </div>-->
<!--                    </div>-->
<!--                    -->
<!--                    &lt;!&ndash;Counter Box&ndash;&gt;-->
<!--                    <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">-->
<!--                    	<div class="counter-box">-->
<!--                        	-->
<!--                            <div class="counter-style" id="myTargetElement3"></div>-->
<!--                            <div class="counter-lable">Awards</div>-->
<!--                            -->
<!--                        </div>-->
<!--                    </div>-->
<!--                    -->
<!--                    &lt;!&ndash;Counter Box&ndash;&gt;-->
<!--                    <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">-->
<!--                    	<div class="counter-box">-->
<!--                        	-->
<!--                            <div class="counter-style" id="myTargetElement4"></div>-->
<!--                            <div class="counter-lable">Happy Patients</div>-->
<!--                            -->
<!--                        </div>-->
<!--                    </div>-->
<!--                    -->
<!--                    -->
<!--                </div>-->
<!--                </div>-->
<!--                </div>-->
<!--            -->
<!--            </div>-->
           
       <!--Counter End-->
       <!--Testimonail Wrap-->
<!--            <div class="testimonial-wrap ihome-testi-wrap">-->
<!--            -->
<!--            -->
<!--            	<div class="container">-->
<!--                <div class="row">-->
<!--                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pull-left client-logo-flexx wow fadeInUp" data-wow-delay="0.3s" data-wow-offset="100">-->
<!--                     <i class="fa fa-quote-right testi-quote"></i>-->
<!--                     <div class="testimonial-content top"></div>-->
<!--                     -->
<!--                     <ul id="home-testimonials">-->
<!--                        &lt;!&ndash;Testimonial Item&ndash;&gt;    -->
<!--                        <li>-->
<!--                        <a class="testi-one testi-1" data-toggle="popover" data-placement="top" data-original-title='' data-content='<span class="testi-client-name">Jhon Snow</span> <br> <span class="testi-client-pos">Creative Officer</span>'>-->
<!--                        <img src="images/client-img-testi.png" alt="" class="img-responsive client-logo-img" />-->
<!--                        </a>-->
<!--                        </li>-->
<!--                        &lt;!&ndash;Testimonial Item&ndash;&gt;    -->
<!--                        <li>-->
<!--                        <a class="testi-one" data-toggle="popover" data-placement="top" data-original-title='.' data-content='Rob Stark <br> Creative Officer'>-->
<!--                        <img src="images/home-img-testi-03.png" alt="" class="img-responsive client-logo-img" />-->
<!--                        </a>-->
<!--                        </li>-->
<!--                        &lt;!&ndash;Testimonial Item&ndash;&gt;-->
<!--                        <li>-->
<!--                        <a class="testi-one" data-toggle="popover" data-placement="top" data-original-title='' data-content='Jhon Snow <br> Creative Officer'>-->
<!--                        <img src="images/home-img-testi-01.png" alt="" class="img-responsive client-logo-img" />-->
<!--                        </a>-->
<!--                        </li>-->
<!--                        &lt;!&ndash;Testimonial Item&ndash;&gt;-->
<!--                        <li>-->
<!--                        <a class="testi-one" data-toggle="popover" data-placement="top" data-original-title='.' data-content='Rob Stark <br> Creative Officer'>-->
<!--                        <img src="images/home-img-testi-04.png" alt="" class="img-responsive client-logo-img" />-->
<!--                        </a>-->
<!--                        </li>-->
<!--                        &lt;!&ndash;Testimonial Item&ndash;&gt;-->
<!--                        <li>-->
<!--                        <a class="testi-one" data-toggle="popover" data-placement="top" data-original-title='.' data-content='Jhon Snow <br> Creative Officer'>-->
<!--                        <img src="images/home-img-testi-05.png" alt="" class="img-responsive client-logo-img" />-->
<!--                        </a>-->
<!--                        </li>-->
<!--                        &lt;!&ndash;Testimonial Item&ndash;&gt;-->
<!--                        <li>-->
<!--                        <a class="testi-one" data-toggle="popover" data-placement="top" data-original-title='.' data-content='Rob Stark <br> Creative Officer'>-->
<!--                        <img src="images/home-img-testi-02.png" alt="" class="img-responsive client-logo-img" />-->
<!--                        </a>-->
<!--                        </li>-->
<!--                        &lt;!&ndash;Testimonial Item&ndash;&gt;-->
<!--                        <li>-->
<!--                        <a class="testi-one" data-toggle="popover" data-placement="top" data-original-title='.' data-content='Jhon Snow <br> Creative Officer'>-->
<!--                        <img src="images/home-img-testi-01.png" alt="" class="img-responsive client-logo-img" />-->
<!--                        </a>-->
<!--                        </li>-->
<!--                        &lt;!&ndash;Testimonial Item&ndash;&gt;-->
<!--                        <li>-->
<!--                        <a class="testi-one" data-toggle="popover" data-placement="top" data-original-title='.' data-content='Rob Stark <br> Creative Officer'>-->
<!--                        <img src="images/home-img-testi-06.png" alt="" class="img-responsive client-logo-img" />-->
<!--                        </a>-->
<!--                        </li>-->
<!--                        </ul>  -->
<!--                     -->
<!--                     </div>-->
<!--                        -->
<!--                </div>-->
<!--                </div>-->
<!--            -->
<!--            </div>-->
           
       <!--Testimonail Wrap-->
                
                
<!--                 <div class="cl-wrap icl-wrap">-->
<!--                 <div class="container">-->
<!--                 -->
<!--                 <div class="row">		-->
<!--                     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pull-left client-logo-flex wow fadeInUp" data-wow-delay="0.5s" data-wow-offset="100">-->
<!--                     -->
<!--        	             <ul id="clients-carousel" class="icl-carousel">-->
<!--        	                <li><a href="#"><img src="images/nlogo1.png" alt="" class="img-responsive client-logo-img"/></a></li>-->
<!--        	                <li><a href="#"><img src="images/nlogo2.png" alt="" class="img-responsive client-logo-img"/></a></li>-->
<!--        	                <li><a href="#"><img src="images/nlogo3.png" alt="" class="img-responsive client-logo-img"/></a></li>-->
<!--        	                <li><a href="#"><img src="images/nlogo4.png" alt="" class="img-responsive client-logo-img"/></a></li>-->
<!--        	                <li><a href="#"><img src="images/nlogo5.png" alt="" class="img-responsive client-logo-img"/></a></li>-->
<!--        	                <li><a href="#"><img src="images/nlogo6.png" alt="" class="img-responsive client-logo-img"/></a></li>-->
<!--        	            </ul>   -->
<!--                     -->
<!--                     </div>-->
<!--                 </div>    -->
<!--                     -->
<!--                 </div></div>-->
           
              <!--Footer Start-->
           
           </div>
           <div>
<!--            <div id="settings">-->
<!--                    <div class="colors">-->
<!--                    <div class="panel-title">Style Switcher</div> -->
<!--                    <div class="panel-color-title">Color Schemes</div>    -->
<!--                        <ul>-->
<!--                            <li><a title="maroon" class="color1 color-switch"><i class="fa fa-check"></i></a></li>-->
<!--                            <li><a title="grey" class="color2 color-switch"><i class="fa fa-check"></i></a></li>-->
<!--                            <li><a title="green" class="color3 color-switch"><i class="fa fa-check"></i></a></li>-->
<!--                            <li><a title="orange" class="color4 color-switch"><i class="fa fa-check"></i></a></li>-->
<!--                            <li><a title="red" class="color5 color-switch"><i class="fa fa-check"></i></a></li>-->
<!--                            <li><a title="blue" class="color6 color-switch selected"><i class="fa fa-check"></i></a></li>-->
<!--                            -->
<!--                            -->
<!--                        </ul>-->
<!--                    </div>-->
<!--                    <a href="javascript:void(0);" class="settings_link showup"><i class="fa fa-cog"></i></a>-->
<!--                </div>-->
</div>

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
