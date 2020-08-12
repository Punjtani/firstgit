<!-- resources/views/layouts/main.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Uniranks" />
    <meta name="author" content="Mazajnet" />

    <title>@yield('title')</title>

    <link rel="shortcut icon" href={{ asset("assets/favicon.ico") }} />

    <link href={{ asset("assets/css/bootstrap.min.css") }} rel="stylesheet" />
    <link href={{ asset("assets/css/main.css") }} rel="stylesheet" />

    <link href="https://fonts.googleapis.com/css2?family=Mulish&display=swap" rel="stylesheet">

    <script src="https://kit.fontawesome.com/9487d74d1e.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="container-fluid">

      <section class="top_bar d-flex px-5 py-2">
        <div class="d-flex flex-fill">
          <p class="mr-3">Language (En) <i class="fas fa-angle-down light_blue_text"></i></p>
          <p>Currency (USD) <i class="fas fa-angle-down light_blue_text"></i></p>
        </div>
        <div class="d-flex">
          <i class="user_icon fas fa-user mr-1"></i>
          <p class="user_name">Majdi Hussin</p>
        </div>
      </section>

      <nav class="navbar navbar-expand-lg px-5">
        <a class="navbar-brand" href="#"><img class="logo" src={{ asset("assets/img/logos/uniranks.png") }} /></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto pt-4"> <?php // TODO: add active classes ?>
            <li class="nav-item">
              <a class="nav-link" href="#">Ranking</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Discover</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Events</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Prepare</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Apply</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Careers</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Community</a>
            </li>
            <li class="nav-item search_icon">
              <a class="nav-link" href="#">
                <button class="btn btn-outline-secondary search_btn"><i class="fas fa-search"></i></button>
              </a>
            </li>
          </ul>
        </div>
      </nav>

      @yield('content')

      <section class="p-5 light_blue_background">
        <h2 class="text-uppercase text-center">subscribe to our newsletter</h2>
        <div class="d-flex w-75 m-auto">
          <input class="form-control mr-2" type="email" placeholder="Your email address" />
          <button class="btn btn-primary text-uppercase shadow-sm">subscribe</button>
        </div>
      </section>

      <footer class="px-5 pt-5 dark_blue_background">
        <div class="footer_top d-flex justify-content-between pb-4">
          <img class="footer_logo" src={{ asset("assets/img/logos/footer.png") }} />
          <div class="footer_menu d-flex flex-column">
            <h3 class="footer_header">Rankings</h3>
            <hr class="footer_separator" />
            <a class="footer_menu_item" href="#">Rankings Overview</a>
            <a class="footer_menu_item" href="#">USA University Rankings</a>
            <a class="footer_menu_item" href="#">Global MBA Rankings</a>
            <a class="footer_menu_item" href="#">Top 50 in the World</a>
            <a class="footer_menu_item" href="#">Star Rating System</a>
            <a class="footer_menu_item" href="#">QS IGAUGE Rating System</a>
          </div>
          <div class="footer_menu d-flex flex-column">
            <h3 class="footer_header">Events</h3>
            <hr class="footer_separator" />
            <a class="footer_menu_item" href="#">Rankings Overview</a>
            <a class="footer_menu_item" href="#">USA University Rankings</a>
            <a class="footer_menu_item" href="#">Global MBA Rankings</a>
            <a class="footer_menu_item" href="#">Top 50 in the World</a>
            <a class="footer_menu_item" href="#">Star Rating System</a>
            <a class="footer_menu_item" href="#">QS IGAUGE Rating System</a>
          </div>
          <div class="footer_menu d-flex flex-column">
            <h3 class="footer_header">Discover</h3>
            <hr class="footer_separator" />
            <a class="footer_menu_item" href="#">Rankings Overview</a>
            <a class="footer_menu_item" href="#">USA University Rankings</a>
            <a class="footer_menu_item" href="#">Global MBA Rankings</a>
            <a class="footer_menu_item" href="#">Top 50 in the World</a>
            <a class="footer_menu_item" href="#">Star Rating System</a>
            <a class="footer_menu_item" href="#">QS IGAUGE Rating System</a>
          </div>
          <div class="footer_menu d-flex flex-column">
            <h3 class="footer_header">Get in Touch</h3>
            <hr class="footer_separator" />
            <div class="d-flex">
              <a class="footer_menu_item" href="#"><i class="fab fa-facebook-square fa-2x mr-2"></i></a>
              <a class="footer_menu_item" href="#"><i class="fab fa-twitter fa-2x mr-2"></i></a>
              <a class="footer_menu_item" href="#"><i class="fab fa-instagram fa-2x"></i></a>
            </div>
          </div>
        </div>
        <div class="d-flex pt-4 pb-2">
          <p class="flex-fill footer_menu_item">2020 &copy; UNIRANKS. All Right Reserved</p>
          <div class="bottom_menu d-flex justify-content-between">
            <a class="footer_menu_item bottom_menu_item" href="#">FAQ</a>
            <a class="footer_menu_item bottom_menu_item" href="#">Contact</a>
            <a class="footer_menu_item bottom_menu_item" href="#">Privacy</a>
            <a class="footer_menu_item bottom_menu_item" href="#">Cookies</a>
            <a class="footer_menu_item bottom_menu_item" href="#">Terms</a>
            <a class="footer_menu_item bottom_menu_item bottom_menu_item_last" href="#">Partners</a>
          </div>
        </div>
      </footer>
    </div>

    <script src={{ asset("assets/js/jquery.min.js") }}></script>
    <script src={{ asset("assets/js/popper.min.js") }}></script>
    <script src={{ asset("assets/js/bootstrap.min.js") }}></script>
  </body>
</html>
