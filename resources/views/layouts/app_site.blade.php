<!DOCTYPE HTML>
<!--[if gt IE 8]> <html class="ie9" lang="en"> <![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml" class="ihome">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <style>



.txt_bg3{

    background-image: url(../images/w-8.jpg);
    /* background-image: url(../images/w-2.jpeg); */
   /* background-image: url(../images/16.jpeg); */
   background-position: center;
   object-fit: cover;
   background-repeat: no-repeat;
   background-size:100%;

}
.txt_bg2 {

   background-image: url(public/images/wcu.jpeg);
   /* background-image: url(../images/w-9.jpg); */
   background-position: center;
   object-fit: cover;
   background-repeat: no-repeat;
   background-size:100% 100%;


               }
    </style>
<?php
$styles = [


    'site-assets/css/jquery-ui-1.10.3.custom.css',
    'site-assets/css/animate.css',
    'site-assets/css/font-awesome.min.css',
    'site-assets/css/green.css',
    'site-assets/css/style.css',
    'site-assets/rs-plugin/css/settings.min.css',
    'site-assets/images/favicon.png',
    'site-assets/css/slides.css',
    'site-assets/css/inline.min.css',
];
style($styles);
?>
@yield('styles')

<?php
$scripts = [
    // 'js/vendor/jquery-3.3.1.min.js'
];
script($scripts);
?>
    <title>TheMindXperts</title>

    <link href='http://fonts.googleapis.com/css?family=Metal+Mania:400,700,300' rel='stylesheet' type='text/css'>
<!--    <link href='http://fonts.googleapis.com/css?family=Metal+Mania:400,700,300' rel='stylesheet' type='text/css'>-->
    <link href='http://fonts.googleapis.com/css?family=Metal+Mania:400,700,400italic' rel='stylesheet' type='text/css'>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    {{-- <link href="css/jquery-ui-1.10.3.custom.css" rel="stylesheet" /> --}}
    {{-- <link href="css/animate.css" rel="stylesheet" /> --}}
    {{-- <link href="css/font-awesome.min.css" rel="stylesheet" /> --}}
    {{-- <link rel="stylesheet" type="text/css" href="css/green.css" id="style-switch" /> --}}
    {{-- <link rel="stylesheet" type="text/css" href="css/style.css" id="style-switch" /> --}}
    <!-- REVOLUTION BANNER CSS SETTINGS -->
    {{-- <link rel="stylesheet" type="text/css" href="rs-plugin/css/settings.min.css" media="screen" /> --}}
    <!--[if IE 9]>
    	<link rel="stylesheet" type="text/css" href="css/ie9.css" />
    <![endif]-->
    {{-- <link rel="icon" type="image/png" href="images/fevicon.png"> --}}

    {{-- <link rel="stylesheet" type="text/css" href="css/slides.css" /> --}}
    {{-- <link rel="stylesheet" type="text/css" href="css/inline.min.css" /> --}}

</head>
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/5ee9ead94a7c6258179ac64d/default';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
    <body>
    		<div id="loader-overlay"><img src="images/loader.gif" alt="Loading" /></div>

            <header>

            <div class="header-bg">

            <div id="search-overlay">
            <div class="container">
        						<div id="close">X</div>

        						<input id="hidden-search" type="text" placeholder="Start Typing..." autofocus autocomplete="off"  /> <!--hidden input the user types into-->
        						<input id="display-search" type="text" placeholder="Start Typing..." autofocus autocomplete="off" /> <!--mirrored input that shows the actual input value-->
        					</div></div>


                    <!--Topbar-->
<!--                    <div class="topbar-info no-pad">                    -->
<!--                        <div class="container">                     -->
<!--                            <div class="social-wrap-head col-md-2 no-pad">-->
<!--                                <ul>-->
<!--                                <li><a href="#"><i class="icon-facebook head-social-icon" id="face-head" data-original-title="" title=""></i></a></li>-->
<!--                                <li><a href="#"><i class="icon-social-twitter head-social-icon" id="tweet-head" data-original-title="" title=""></i></a></li>-->
<!--                                <li><a href="#"><i class="icon-google-plus head-social-icon" id="gplus-head" data-original-title="" title=""></i></a></li>-->
<!--                                <li><a href="#"><i class="icon-linkedin head-social-icon" id="link-head" data-original-title="" title=""></i></a></li>-->
<!--                                <li><a href="#"><i class="icon-rss head-social-icon" id="rss-head" data-original-title="" title=""></i></a></li>-->
<!--                                </ul>-->
<!--                            </div>                            -->
<!--                            <div class="top-info-contact pull-right col-md-6">Call Us Today! +123 455 755  |    contact@imedica.com  <div id="search" class="fa fa-search search-head"></div>-->
<!--                            </div>                      -->
<!--                        </div>-->
<!--                    </div>-->


                <!--Topbar-info-close-->





                    <div id="headerstic">

                    <div class=" top-bar container">
                    	<div class="row">
                            <nav class="navbar navbar-default" role="navigation">
                              <div class="container-fluid">
                                <!-- Brand and toggle get grouped for better mobile display -->
                                <div class="navbar-header">

                          <button type="button" class="navbar-toggle icon-list-ul" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                          </button>
<!--                          <button type="button" class="navbar-toggle icon-rocket" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">-->
<!--                            <span class="sr-only">Toggle navigation</span>-->
<!--                          </button>-->

                          <a href="index.html">
                              <div class="logo_new">
                                {{-- <img class="img-fluid" src="{{url('public/images/Psyche&TheMindXperts.png')}}"> --}}
                                <img class="img-fluid" src="images/Psyche&TheMindXperts.png">
                              </div>
                          </a>
                        </div>

                                <!-- Collect the nav links, forms, and other content for toggling -->
                                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


                                  <ul class="nav navbar-nav navbar-right">
                            <li class="active"><a href="{{url('/')}}"><i class="icon-home"></i>Home</a></li>
                                  <li><a href="{{url('book-my-session')}}">Book My Session</a></li>
                                  <li><a href="{{url('join-us')}}">Join Us</a></li>
                                  <li><a href="{{url('about-us')}}">About Us</a></li>
                                      <li><a href="{{url('blogs')}}">Blogs</a></li>
                                      {{-- <li><a href="{{url('book-my-session')}}">Access</a></li> --}}
                                      <li><a href="{{url('contact-us')}}">Contact </a></li>

<!--                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i>Features<b class="icon-angle-down"></b></a>-->
<!--                                <ul class="dropdown-menu">-->
<!--                                    <li><a href="page-elements.html">Page Elements</a></li>-->
<!--                                    <li><a href="typography.html">Typography</a></li>-->
<!--                                    <li><a href="columns.html">Columns</a></li>-->
<!--                                    <li><a href="price-table.html">Pricing Tables</a></li>-->
<!--                                    <li><a href="fbox.html">Flip Box</a></li>-->
<!--                                    -->
<!--                                    -->
<!--                                  </ul>-->
<!--                            </li>-->

<!--                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-file"></i>Pages<b class="icon-angle-down"></b></a>-->
<!--                                <ul class="dropdown-menu">-->
<!--                                    <li><a href="about-us.html">About Us</a></li>-->
<!--                                    <li><a href="services.html">Services</a></li>-->
<!--                                    <li><a href="department.html">Departments</a></li>-->
<!--                                    -->
<!--                                    <li class="dropdown-submenu"><a href="meet-our-doctors.html">Our Doctors 3 Columns</a>-->
<!--                                        &lt;!&ndash;<ul class="dropdown-menu">-->
<!--                                            -->
<!--                                            <li><a href="meet-our-doctors.html">Doctors </a></li>-->
<!--                                        </ul>&ndash;&gt;-->
<!--                                    </li>-->
<!--                                    -->
<!--                                    <li><a href="Testimonials.html">Testimonials</a></li>-->
<!--                                    <li><a href="faq.html">FAQs</a></li>-->
<!--                                    -->
<!--                                    -->
<!--                                  </ul>-->
<!--                            </li>-->

<!--                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-camera"></i>Gallery<b class="icon-angle-down"></b></a>-->
<!--                                <ul class="dropdown-menu">-->
<!--                                    <li><a href="gallery-carousel.html">Gallery Style 1  </a></li>-->
<!--                                    <li><a href="gallery-3cols.html">Gallery 3 Columns</a></li>-->
<!--                                    -->
<!--                                    -->
<!--                                  </ul>-->
<!--                            </li>-->

<!--                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-pencil"></i>Blog<b class="icon-angle-down"></b></a>-->
<!--                                <ul class="dropdown-menu">-->
<!--                                    <li><a href="blog-full.html">Blog Style 1</a></li>-->
<!--                                    <li><a href="blog-side.html">Blog Left Sidebar</a></li>-->
<!--                                    <li><a href="blog-right-side.html">Blog Right Sidebar</a></li> -->
<!--                                  </ul>-->
<!--                            </li>-->

<!--                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-envelope"></i>Contact Us<b class="icon-angle-down"></b></a>-->
<!--                                <ul class="dropdown-menu">-->
<!--                                    <li><a href="contact-2.html">Contact Version 1</a></li>-->
<!--                                    <li><a href="contact-3.html">Contact Version 2</a></li>-->
<!--                                    -->
<!--                                    -->
<!--                                  </ul>-->
<!--                            </li>-->


                          </ul>
                                </div><!-- /.navbar-collapse -->


                                <div class="hide-mid collapse navbar-collapse option-drop" id="bs-example-navbar-collapse-2">


                                  <ul class="nav navbar-nav navbar-right other-op">
                                    <li><i class="icon-phone2"></i>+91 9028556688</li>
                                    <li><i class="icon-mail"></i><a href="#" class="mail-menu">contact@themindxperts.com</a></li>

                                    <li><i class="icon-globe"></i>
                                        <a href="#" class="mail-menu"><i class="icon-facebook"></i></a>
                                        <a href="#" class="mail-menu"><i class="icon-google-plus"></i></a>
                                        <a href="#" class="mail-menu"><i class="icon-linkedin"></i></a>
                                        <a href="#" class="mail-menu"><i class="icon-social-twitter"></i></a>
                                    </li>
                                    <li><i class="icon-search"></i>
                                    <div class="search-wrap"><input type="text" id="search-text" class="search-txt" name="search-text">
                                    <button id="searchbt" name="searchbt" class="icon-search search-bt"></button></div>
                                    </li>

                                  </ul>
                                </div><!-- /.navbar-collapse -->

                                <div class="hide-mid collapse navbar-collapse cart-drop" id="bs-example-navbar-collapse-3">


                                  <ul class="nav navbar-nav navbar-right">
                                    <li><a href="#"><i class="icon-cart"></i>0 item(s) - $0.00</a></li>
                                    <li><a href="#"><i class="icon-user"></i>My Account</a></li>
                                  </ul>
                                </div><!-- /.navbar-collapse -->



                              </div><!-- /.container-fluid -->
                            </nav>
                    	</div>
                    </div><!--Topbar End-->
                	</div>







              </div>
            </header>


           @yield('content')

            <div class="complete-footer">
            <footer id="footer" style="
    background: #ffffff;
">

            	<div class="container">
                	<div class="row">
                    	<!--Foot widget-->
                    	<div class="col-xs-12 col-sm-6 col-md-3 foot-widget">
                        <a href="#">
                            <div class="col-xs-12 no-pad">
                                <div class="logo_new">

                                </div>
                            </div>
                        </a>

                        <address class="foot-address">
                        	<div class="col-xs-12 no-pad" style="
    color: #020202; font-weight: bold;
">
<!--                                <i class="icon-globe address-icons"></i>The MindXperts-->
<!--                                <br />-->
                                <br />
                            </div>
                            <div class="col-xs-12 no-pad" style="
    color: #020202;font-weight: bold;
"><i class="icon-file address-icons" style="
    color: #333;
"></i>+92 333 07 22 296</div>
                            <div class="col-xs-12 no-pad" style="
    color: #020202;font-weight: bold;
"><i class="icon-file address-icons" style="
    color: #333;
"></i>+ 92 320 411 8330</div>
                            <div class="col-xs-12 no-pad" style="
    color: #020202;font-weight: bold;
"><i class="icon-mail address-icons" style="
    color: #333;
"></i>info@themindxperts.com</div>
                            <div class="col-xs-12 no-pad" style="
    color: #020202;font-weight: bold;
"><i class="icon-mail address-icons" style="
    color: #333;
"></i>access@themindxperts.com</div>
                        </address>
                        </div>

                        <!--Foot widget-->
<!--                        <div class="col-xs-12 col-sm-6 col-md-3 recent-post-foot foot-widget">-->
<!--                        	<div class="foot-widget-title">Recent Posts</div>-->
<!--                        	<ul>-->
<!--                            	<li><a href="#">Consecte tur adipiscing elit ut eunt<br /><span class="event-date">3 days ago</span></a></li>-->
<!--                                <li><a href="#">Fusce vel tempus augue nunc<br /><span class="event-date">5 days ago</span></a></li>-->
<!--                                <li><a href="#">Lorem nulla, vitae eleifend leo tincidunt<br /><span class="event-date">7 days ago</span></a></li>-->
<!--                            </ul>-->
<!--                        </div>-->

                         <!--Foot widget-->
<!--                        <div class="col-xs-12 col-sm-6 col-md-3 recent-tweet-foot foot-widget">-->
<!--                        	<div class="foot-widget-title">Recent News</div>-->
<!--                        	<ul>-->
<!--                            	<li>Integer iaculis egestas odio. eget: <b>t.co/RTSoououIdg</b><br /><span class="event-date">7 days ago</span></li>-->
<!--                                <li>Integer iaculis egestas odio. eget: <b>t.co/RTSoououIdg</b><br /><span class="event-date">7 days ago</span></li>-->
<!--                            </ul>-->
<!--                        </div>-->

                        <!--Foot widget-->
                        <div class="col-xs-12 col-sm-6 col-md-3 foot-widget">
<!--                        	<div class="foot-widget-title" style="-->
<!--    color: #bcbec0;font-weight: bold;-->
<!--">newsletter</div>-->
                        	<p></p>
<!--                            <div class="news-subscribe"><input type="text" class="news-tb" placeholder="Email Address" /><button class="news-button">Subscribe</button></div>-->
                            <div class="foot-widget-title" style="
    color: #333;
">social media</div>
                            <div class="social-wrap">
                                <ul>
                                {{-- <li><a href="https://www.facebook.com/themindexpert/"><i class="icon-facebook foot-social-icon" id="face-foot" data-toggle="tooltip" data-placement="bottom" title="Facebook"></i></a></li> --}}
                                {{-- <li><a href="https://twitter.com/TheMindXperts1"><i class="icon-social-twitter foot-social-icon" id="tweet-foot" data-toggle="tooltip" data-placement="bottom" title="Twitter"></i></a></li> --}}
<!--                                <li><a href="#"><i class="icon-google-plus foot-social-icon" id="gplus-foot" data-toggle="tooltip" data-placement="bottom" title="Google+"></i></a></li>-->
                                {{-- <li><a href="https://www.linkedin.com/in/the-mind-xperts-9821aa1a9/"><i class="icon-linkedin foot-social-icon" id="link-foot" data-toggle="tooltip" data-placement="bottom" title="Linked in"></i></a></li> --}}
                                {{-- <li><a href="https://www.linkedin.com/in/the-mind-xperts-9821aa1a9/"><i class="icon-instagram foot-social-icon" id="link-foot" data-toggle="tooltip" data-placement="bottom" title="Linked in"></i></a></li> --}}
                                {{-- <li><a href="https://www.linkedin.com/in/the-mind-xperts-9821aa1a9/"><i class="icon-whatsapp foot-social-icon" id="link-foot" data-toggle="tooltip" data-placement="bottom" title="Linked in"></i></a></li> --}}
                                <li><a href="https://api.whatsapp.com/send?phone=923330722296"><i class="fa fa-whatsapp fa-3x foot-social-icon" aria-hidden="true"></i></a></li>
                                <li><a href="https://www.instagram.com/themindxperts/"><i class="fa fa-instagram fa-3x foot-social-icon" aria-hidden="true"></i></a></li>
                                <li><a href="https://www.facebook.com/themindexpert"><i class="fa fa-facebook fa-3x foot-social-icon" aria-hidden="true"></i></a></li>
                                <li><a href="https://www.linkedin.com/in/the-mind-xperts-9821aa1a9/"><i class="fa fa-linkedin fa-3x foot-social-icon" aria-hidden="true"></i></a></li>
                                <li><a href="https://twitter.com/TheMindXperts1"><i class="fa fa-twitter fa-3x foot-social-icon" aria-hidden="true"></i></a></li>
<!--                                <li><a href="#"><i class="icon-rss foot-social-icon" id="rss-foot" data-toggle="tooltip" data-placement="bottom" title="RSS"></i></a></li>-->
                                </ul>
                            </div>
                        </div>

                    </div>
               	 </div>

            </footer>

            <div class="bottom-footer" style="
    background: #fff;
">
            <div class="container">

                <div class="row">
                    <!--Foot widget-->
                    <div class="col-xs-12 col-sm-12 col-md-12 foot-widget-bottom">
                    <p class="col-xs-12 col-md-5 no-pad" style="
    color: #333;font-weight: bold;
">Copyright 2020 The MindXperts | All Rights Reserved | </p>
<!--                    <ul class="foot-menu col-xs-12 col-md-7 no-pad">-->
<!--                    <li><a href="about-us.html">Pages</a></li>-->
<!--                    <li><a href="gallery-3cols.html">Gallery</a></li>-->
<!--                    <li><a href="blog-full.html">Blog</a></li>-->
<!--                    <li><a href="#">Features</a></li>-->
<!--                    <li><a href="contact-2.html">Contact</a></li>-->
<!--                    <li><a href="index.html">home</a></li>-->

<!--                    </ul>-->
                    </div>
                </div>
            </div>
            </div>

            </div>


<?php
$scripts = [
    'site-assets/js/jquery.min.js',
    'site-assets/js/jquery-ui-1.10.3.custom.min.js',
    'site-assets/bootstrap-new/js/bootstrap.min.js',
    'site-assets/rs-plugin/js/jquery.themepunch.tools.min.js',
    'site-assets/rs-plugin/js/jquery.themepunch.revolution.min.js',
    'site-assets/js/jquery.scrollUp.min.js',
    'site-assets/js/jquery.sticky.min.js',
    'site-assets/js/wow.min.js',
    'site-assets/js/jquery.flexisel.min.js',
    'site-assets/js/jquery.imedica.min.js',
    'site-assets/js/custom-imedicajs.min.js',

];
script($scripts);
?>
@yield('scripts')

    <!--JS Inclution-->
    {{-- <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="bootstrap-new/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="rs-plugin/js/jquery.themepunch.tools.min.js"></script>
    <script type="text/javascript" src="rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
    <script type="text/javascript" src="js/jquery.scrollUp.min.js"></script>
    <script type="text/javascript" src="js/jquery.sticky.min.js"></script>
    <script type="text/javascript" src="js/wow.min.js"></script>
    <script type="text/javascript" src="js/jquery.flexisel.min.js"></script>
    <script type="text/javascript" src="js/jquery.imedica.min.js"></script>
    <script type="text/javascript" src="js/custom-imedicajs.min.js"></script> --}}
	<script type='text/javascript'>
		$(window).load(function(){
			$('#loader-overlay').fadeOut(900);
			$("html").css("overflow","visible");
		});
	</script>

    </body>
</html>
