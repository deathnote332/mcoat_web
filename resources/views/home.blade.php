
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf_token" content="{{csrf_token()}}" />
    <!-- Google Font -->
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <base href="/">
    @stack('metas')

    <title>MCOAT - @yield('title')</title>
    <link type="text/css" rel="stylesheet" href="{{url('vendor/font-awesome/4.7.0/css/font-awesome.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{url('vendor/bootstrap/3.3.7/css/bootstrap.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{url('vendor/home/style.css')}}">


    <script src="{{url('vendor/jquery/3.2.1/jquery.min.js')}}"></script>
    <script src="{{url('vendor/home/custom.js')}}"></script>
    <script src="{{url('vendor/home/easing.js')}}"></script>
    <script src="{{url('vendor/home/morphext.js')}}"></script>
    <script src="{{url('vendor/home/wow.js')}}"></script>
    <script src="{{url('vendor/home/superfish.js')}}"></script>
    <script src="{{url('vendor/home/sticky.js')}}"></script>

</head>
    <body>
    <div id="preloader"></div>
    <style>

    </style>
    <!--==========================
      Hero Section
    ============================-->
    <section id="hero">
        <div class="hero-container">
            <div class="wow fadeIn">
                <div class="hero-logo">
                    <img class="" src="{{ url('img/mcoat-png.png') }}" alt="Imperial">
                </div>

                <h1>Welcome to MCOAT</h1>
                <h2>We mix <span class="rotating"> beautiful colors, your desired colors</span></h2>
                <div class="actions">
                    <a href="#about" class="btn-get-started">Get Strated</a>
                    <a href="#services" class="btn-services">Our Services</a>
                </div>
            </div>
        </div>
    </section>

    <!--==========================
      Header Section
    ============================-->
    <header id="header">
        <div class="container">

            <div id="logo" class="pull-left">
                <a href="#hero"><img src="{{ url('img/mcoat-png.png') }}" alt="" title="" /></a>
                <!-- Uncomment below if you prefer to use a text image -->
                <!--<h1><a href="#hero">Header 1</a></h1>-->
            </div>

            <nav id="nav-menu-container">
                <ul class="nav-menu">
                    <li class="menu-active"><a href="#hero">Home</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                    @auth
                        @if(Auth::user()->user_type == 1)
                        <li><a href="{{url('/admin/dashboard')}}">Go to page</a></li>
                        @else
                        <li><a href="{{url('/user/dashboard')}}">Go to page</a></li>
                        @endif
                    @else
                    <li><a href="{{url('login')}}">Sign in</a></li>
                    @endauth
                </ul>
            </nav><!-- #nav-menu-container -->
        </div>
    </header><!-- #header -->

    <!--==========================
      About Section
    ============================-->
    <section id="about">
        <div class="container wow fadeInUp">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="section-title">About Us</h3>
                    <div class="section-title-divider"></div>
                </div>
            </div>
        </div>
        <div class="container about-container wow fadeInUp">
            <div class="row">
                <div class="col-md-6 about-content">
                    <h2 class="about-title">We mix color you want</h2>
                    <p class="about-text">
                        M-Coat Paint Commercial and General Merchandise is one of the paint merchandiser in the Philippines. Located in 185 R. Jabson St. Bambang, Pasig City.

                    </p>
                    <p class="about-text">
                        Our main products are house and car paints. <b>We also mix color based on the customer color.</b> You can check our portfoloi below.
                    </p>
                    <p>We have also our <b>9 branch</b> located in the Philippines.</p>
                    <p class="about-text">

                        <b>Store Hours:</b>  7:30AM-5:00PM (Monday - Saturday) and 7:30AM - 12:00NN (Sunday)
                    </p>
                </div>
                <div class="col-md-4 col-md-offset-1 about-content">
                    <img class="img-responsive " src="../images/mcoat-bg.jpg">
                </div>
            </div>
        </div>
    </section>

    <!--==========================
      Services Section
    ============================-->
    <section id="services">
        <div class="container wow fadeInUp">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="section-title">Our Services</h3>
                    <div class="section-title-divider"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-md-offset-1 service-item">
                    <div class="service-icon"><i class="fa fa-money"></i></div>
                    <h4 class="service-title"><a>Budget Friendly</a></h4>
                    <p class="service-description">We retail and whole sale a budget friendly paint and hardware products.</p>
                </div>
                <div class="col-md-4 col-md-offset-2 service-item">
                    <div class="service-icon"><i class="fa fa-home"></i></div>
                    <h4 class="service-title"><a>House Paints</a></h4>
                    <p class="service-description">We provide quality paints for your house needs.</p>
                </div>
                <div class="col-md-4 col-md-offset-1 service-item">
                    <div class="service-icon"><i class="fa fa-car"></i></div>
                    <h4 class="service-title"><a>Car Paints</a></h4>
                    <p class="service-description">We provide quality paints for your cars maintenance and needs.</p>
                </div>
                <div class="col-md-4  col-md-offset-2 service-item">
                    <div class="service-icon"><i class="fa fa-truck"></i></div>
                    <h4 class="service-title"><a>Truck Delivery</a></h4>
                    <p class="service-description">We deliver your products at your home/store for bulk orders around metro manila.</p>
                </div>

            </div>
        </div>
    </section>

    <!--==========================
      Subscrbe Section
    ============================-->
    <section id="subscribe">
        <div class="container wow fadeInUp">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="subscribe-title">Subscribe For Updates</h3>
                    <p class="subscribe-text">Join our 1000+ subscribers and get access to the latest tools, freebies, product announcements and much more!</p>
                </div>
                <div class="col-md-4 subscribe-btn-container">
                    <a class="subscribe-btn" href="#">Subscribe Now</a>
                </div>
            </div>
        </div>
    </section>

    <!--==========================
      Porfolio Section
    ============================-->
    <section id="portfolio">
        <div class="container wow fadeInUp">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="section-title">Portfolio</h3>
                    <div class="section-title-divider"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <a class="portfolio-item" style="background-image: url(../images/mcoat-bg.jpg);" href="">
                        <div class="details">
                            <h4>Portfolio 1</h4>
                            <span>Alored dono par</span>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a class="portfolio-item" style="background-image: url(../images/mcoat-bg.jpg);" href="">
                        <div class="details">
                            <h4>Portfolio 2</h4>
                            <span>Alored dono par</span>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a class="portfolio-item" style="background-image: url(../images/mcoat-bg.jpg);" href="">
                        <div class="details">
                            <h4>Portfolio 3</h4>
                            <span>Alored dono par</span>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a class="portfolio-item" style="background-image: url(../images/mcoat-bg.jpg);" href="">
                        <div class="details">
                            <h4>Portfolio 4</h4>
                            <span>Alored dono par</span>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a class="portfolio-item" style="background-image: url(../images/mcoat-bg.jpg);" href="">
                        <div class="details">
                            <h4>Portfolio 5</h4>
                            <span>Alored dono par</span>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a class="portfolio-item" style="background-image: url(../images/mcoat-bg.jpg);" href="">
                        <div class="details">
                            <h4>Portfolio 6</h4>
                            <span>Alored dono par</span>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a class="portfolio-item" style="background-image: url(../images/mcoat-bg.jpg);" href="">
                        <div class="details">
                            <h4>Portfolio 7</h4>
                            <span>Alored dono par</span>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a class="portfolio-item" style="background-image: url(../images/mcoat-bg.jpg);" href="">
                        <div class="details">
                            <h4>Portfolio 8</h4>
                            <span>Alored dono par</span>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{--<!--==========================--}}
    {{--Testimonials Section--}}
    {{--============================-->--}}
    {{--<section id="testimonials">--}}
    {{--<div class="container wow fadeInUp">--}}
    {{--<div class="row">--}}
    {{--<div class="col-md-12">--}}
    {{--<h3 class="section-title">Testimonials</h3>--}}
    {{--<div class="section-title-divider"></div>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--<div class="row">--}}
    {{--<div class="col-md-3">--}}
    {{--<div class="profile">--}}
    {{--<div class="pic"><img src="img/client-1.jpg" alt=""></div>--}}
    {{--<h4>Saul Goodman</h4>--}}
    {{--<span>Lawless Inc</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-md-9">--}}
    {{--<div class="quote">--}}
    {{--<b><img src="img/quote_sign_left.png" alt=""></b> Proin iaculis purus consequat sem cure  digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper. <small><img src="img/quote_sign_right.png" alt=""></small>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--<div class="row">--}}
    {{--<div class="col-md-9">--}}
    {{--<div class="quote">--}}
    {{--<b><img src="img/quote_sign_left.png" alt=""></b> Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis architecto beatae. <small><img src="img/quote_sign_right.png" alt=""></small>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-md-3">--}}
    {{--<div class="profile">--}}
    {{--<div class="pic"><img src="img/client-2.jpg" alt=""></div>--}}
    {{--<h4>Sara Wilsson</h4>--}}
    {{--<span>Odeo Inc</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--</div>--}}
    {{--</section>--}}

    <!--==========================
      Contact Section
    ============================-->
    <section id="contact">
        <div class="container wow fadeInUp">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="section-title">Contact Us</h3>
                    <div class="section-title-divider"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div id="map"></div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-4">
                    <div class="info">
                        <div>
                            <i class="fa fa-map-marker"></i>
                            <p>185 R. Jabson St. Bambang, Pasig City</p>
                        </div>

                        <div>
                            <i class="fa fa-envelope"></i>
                            <p>cdjpaintcenter@gmail.com</p>
                        </div>

                        <div>
                            <i class="fa fa-phone"></i>
                            <p>509-3387</p>

                        </div>

                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form">
                        <div id="sendmessage">Your message has been sent. Thank you!</div>
                        <div id="errormessage"></div>
                        <form action="" method="post" role="form" class="contactForm">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message"></textarea>
                                <div class="validation"></div>
                            </div>
                            <div class="text-center"><button type="submit">Send Message</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--==========================
      Footer
    ============================-->
    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="copyright">
                        &copy; Copyright <strong>MCOAT Paint Commercial & Gen. Mdse.</strong> All Rights Reserved
                    </div>
                    <div class="credits">
                        <!--
                          All the links in the footer should remain intact.
                          You can delete the links only if you purchased the pro version.
                          Licensing information: https://bootstrapmade.com/license/
                          Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Imperial
                        -->
                        {{--Bootstrap Themes by <a href="https://bootstrapmade.com/">BootstrapMade</a>--}}
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- #footer -->

    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
    <script>
        function initMap() {
            var uluru = {lat: 14.5568771, lng: 121.0757283};
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 16,
                center: uluru
            });
            var marker = new google.maps.Marker({
                position: uluru,
                map: map
            });
        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDeZK89ItLNZTIQLtYfsa59cTyto50RTfc&callback=initMap">
    </script>
    </body>
</head>

</html>
