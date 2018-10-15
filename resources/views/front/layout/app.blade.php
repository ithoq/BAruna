<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <link rel="icon" href="{{ asset('img/favicon.png') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    @yield('meta')
    @include('front.layout.style')
</head>

<body>
    <div id="preloader">
        <div id="status"></div>
    </div>
    <div id="main">
        @include('front.layout.nav')
        <div class="share-place">
    	    <a href="https://api.whatsapp.com/send?phone=623618958344" title="Whatsapp" class="share-button share-wa" target="_blank">
                <img src="/img/wa.png" alt="whatsapp" ;="" style="padding: 5px;width: 50px;float: right;">
            </a>
            <a href="https://www.facebook.com/bpraruna" title="Facebook" class="share-button share-fb" target="_blank">
                <img src="/img/fb.png" alt="Facebook" ;="" style="padding: 5px;width: 50px;float: right;">
            </a>
            <a href="https://www.instagram.com/bpraruna" title="Instagram" class="share-button share-ig" target="_blank">
                <img src="/img/ig.png" alt="Instagram" ;="" style="padding: 5px;width: 50px;float: right;">
            </a>
            <!--<a href="https://www.youtube.com/" title="Youtube" class="share-button share-yt" target="_blank">-->
            <!--    <img src="/img/yt.png" alt="Youtube" ;="" style="padding: 5px;width: 50px;float: right;">-->
            <!--</a>-->
    	</div>
        @yield('body')
    </div>
    @include('front.layout.footer')
    @include('front.layout.script')
    <!--<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5aed491996e6fc00110b3020&product=inline-share-buttons' async='async'></script>-->
</body>

</html>
