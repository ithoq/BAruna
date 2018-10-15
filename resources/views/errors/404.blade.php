<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Page Not Found</title>
    <!-- Bootstrap -->
    <link href="{{ url('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/style.css') }}" rel="stylesheet">
    <link href="{{ url('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/fontello.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('css/animsition.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/jquery-ui.css') }}">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Merriweather:300,300i,400,400i,700,700i" rel="stylesheet">
    <!-- owl Carousel Css -->
    <link href="{{ url('/css/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ url('/css/owl.theme.css') }}" rel="stylesheet">
    <!-- Flaticon -->
    <link href="{{ url('/css/flaticon.css') }}" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="animsition">
<div class=" ">
    <!-- content start -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="wrapper-content bg-white pinside60" style="margin-top: 40px;">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-6 col-sm-12">
                            <div class="error-img mb60">
                                <img src="{{ url('images/error-img.png') }}" class="" alt="">
                            </div>
                            <div class="error-ctn text-center">
                                <h2 class="msg">Sorry</h2>
                                <h1 class="error-title mb40">Page Not Found</h1>
                                <p class="mb40">The webpage you were trying to reach could not be found on the server, or that you typed in the URL incorrectly.</p>
                                <a href="{{ route('home.show') }}" class="btn btn-default text-center">go to homepage</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="{{ url('js/jquery.min.js') }}"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{ url('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ url('js/menumaker.js') }}"></script>
<!-- animsition -->
<script type="text/javascript" src="{{ url('js/animsition.js') }}"></script>
<script type="text/javascript" src="{{ url('js/animsition-script.js') }}"></script>
<!-- sticky header -->
<script type="text/javascript" src="{{ url('js/jquery.sticky.js') }}"></script>
<script type="text/javascript" src="{{ url('js/sticky-header.js') }}"></script>
<!-- slider script -->
<script type="text/javascript" src="{{ url('js/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ url('js/slider-carousel.js') }}"></script>
<script type="text/javascript" src="{{ url('js/service-carousel.js') }}"></script>
<!-- Back to top script -->
<script src="{{ url('js/back-to-top.js') }}" type="text/javascript"></script>
<script src="{{ url('js/jquery-ui.js') }}"></script>
<script>
$(function() {
    $("#slider-range-max").slider({
        range: "max",
        min: 1,
        max: 10,
        value: 2,
        slide: function(event, ui) {
            $("#j").val(ui.value);
        }
    });
    $("#j").val($("#slider-range-max").slider("value"));
});
</script>
@yield('script')
</body>

</html>