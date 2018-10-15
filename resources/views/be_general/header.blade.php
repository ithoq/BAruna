<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{$company_profile->name}}</title>

  <!-- Bootstrap -->
  <link href="{{ URL::asset('green/dist/css/bootstrap.css') }}" rel="stylesheet" media="screen">
  <link href="{{ URL::asset('green/assets/css/custom.css') }}" rel="stylesheet" media="screen">

  <!-- Fonts -->
  <link href='http://fonts.googleapis.com/css?family=Lato:400,100,100italic,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:700,400,300,300italic' rel='stylesheet' type='text/css'>
  <!-- Font-Awesome -->
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('green/assets/css/font-awesome.css') }}" media="screen" />


  <!-- end -->

</head>
<body id="top" class="thebg" >



  <div class="navbar-wrapper2">
    <div class="container">
      <div class="navbar mtnav">

        <div class="container offset-3">
          <!-- Navigation-->
          <div class="navbar-header">
            <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a href="index.html" class="navbar-brand">
              <h3 class="lh1">{{$company_profile->name}}</h3>
              <img src="{{ URL::asset('green/images/smallrating-5.png') }}" alt=""/>
            </a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
              <li><a href="#">Menu 1</a></li>
              <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">Dropdown<b class="lightcaret mt-2"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Sample Link 1</a></li>
                  <li><a href="#">Sample Link 2</a></li>
                </ul>
              </li>
            </ul>
          </div>
          <!-- /Navigation-->
        </div>

      </div>
    </div>
  </div>

  @yield('container')

  <!-- FOOTER -->

  <div class="footerbgblack">
    <div class="container">

      <div class="col-md-3">
        <span class="ftitleblack">Let's socialize</span>
        <div class="scont">
          <a href="#" class="social1b"><img src="{{ URL::asset('green/images/icon-facebook.png') }}" alt=""/></a>
          <a href="#" class="social2b"><img src="{{ URL::asset('green/images/icon-twitter.png') }}" alt=""/></a>
          <a href="#" class="social3b"><img src="{{ URL::asset('green/images/icon-gplus.png') }}" alt=""/></a>
          <a href="#" class="social4b"><img src="{{ URL::asset('green/images/icon-youtube.png') }}" alt=""/></a>
          <br/><br/><br/>
          <a href="#"><img src="{{ URL::asset('green/images/logosmal2.png') }}" alt="" /></a><br/>
          <span class="grey2">&copy; 2013  |  <a href="#">Privacy Policy</a><br/>
            All Rights Reserved </span>
            <br/><br/>

          </div>
        </div>
        <!-- End of column 1-->

        <div class="col-md-3">
          <span class="ftitleblack">Travel Specialists</span>
          <br/><br/>
          <ul class="footerlistblack">
            <li><a href="#">Golf Vacations</a></li>
            <li><a href="#">Ski & Snowboarding</a></li>
            <li><a href="#">Disney Parks Vacations</a></li>
            <li><a href="#">Disneyland Vacations</a></li>
            <li><a href="#">Disney World Vacations</a></li>
            <li><a href="#">Vacations As Advertised</a></li>
          </ul>
        </div>
        <!-- End of column 2-->

        <div class="col-md-3">
          <span class="ftitleblack">Travel Specialists</span>
          <br/><br/>
          <ul class="footerlistblack">
            <li><a href="#">Weddings</a></li>
            <li><a href="#">Accessible Travel</a></li>
            <li><a href="#">Disney Parks</a></li>
            <li><a href="#">Cruises</a></li>
            <li><a href="#">Round the World</a></li>
            <li><a href="#">First Class Flights</a></li>
          </ul>
        </div>
        <!-- End of column 3-->

        <div class="col-md-3 grey">
          <span class="ftitleblack">Newsletter</span>
          <div class="relative">
            <input type="email" class="form-control fccustom2black" id="exampleInputEmail1" placeholder="Enter email">
            <button type="submit" class="btn btn-default btncustom">Submit<img src="{{ URL::asset('green/images/arrow.png') }}" alt=""/></button>
          </div>
          <br/><br/>
          <span class="ftitleblack">Customer support</span><br/>
          <span class="pnr">1-866-599-6674</span><br/>
          <span class="grey2">office@travel.com</span>
        </div>
        <!-- End of column 4-->


      </div>
    </div>

    <div class="footerbg3black">
      <div class="container center grey">
        <a href="#">Home</a> |
        <a href="#">About</a> |
        <a href="#">Last minute</a> |
        <a href="#">Early booking</a> |
        <a href="#">Special offers</a> |
        <a href="#">Blog</a> |
        <a href="#">Contact</a>
        <a href="#top" class="gotop scroll"><img src="{{ URL::asset('green/images/spacer.png') }}" alt=""/></a>
      </div>
    </div>

    <!-- jQuery-->
    <script src="{{ URL::asset('green/assets/js/jquery.v2.0.3.js') }}"></script>

    <!-- Bootstrap-->
    <script src="{{ URL::asset('green/dist/js/bootstrap.min.js') }}"></script>

    <!-- Custom functions -->
    <script src="{{ URL::asset('green/assets/js/main.js') }}"></script>

  </body>
  </html>
