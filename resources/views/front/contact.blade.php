@extends('front.layout.app')

@section('meta')
    <title>Kontak Kami | {{ $company_data[0]['name'] }}</title>
    <meta property="og:locale" content="id_ID" />
    <meta name="description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}">
    <meta name="author" content="{{ $company_data[0]['name'] }}">
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Kontak Kami | {{ $company_data[0]['name'] }}" />
    <meta property="og:description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}" />
    <meta property="og:image" content="{{ route('home.show') }}/{{ $company_data[0]['logo'] }}" />
    <meta property="og:image:width" content="480" />
    <meta property="og:image:height" content="480" />
    <meta property="og:site_name" content="{{ $company_data[0]['name'] }}" />
    <meta property="og:url" content="{{ route('contact.show') }}"/>
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}" />
    <meta name="twitter:title" content="Kontak Kami | {{ $company_data[0]['name'] }}" />
    <meta name="twitter:image" content="{{ route('home.show') }}/{{ $company_data[0]['logo'] }}" />
@endsection

@section('body')
<div class="wrapper-body">
    <section class="contact-us">
        <div class="maps">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7891.017684874025!2d115.3088926!3d-8.5469691!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd2166c7bc06089%3A0xd8bc339676604f1d!2sBPR+Aruna+Nirmaladuta!5e0!3m2!1sen!2sid!4v1528040409118" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
    </section>
    <section class="find-us">
        <div class="container-fluid">
            <div class="title">
                <h2>Kontak Kami</h2>
                <p>
                    Terima kasih telah mengunjungi website BPR Aruna, jika ada pertanyaan seputar produk dan layanan kami silahkan hubungi kontak berikut atau bisa menggunakan form kontak untuk mengirimkan pesan.
                </p>
            </div>
            <div class="wrapper-find-us">
                <div class="row">
                    <div class="address">
                        <ul>
                            @if(count($company_data) > 0)
                                <li><i class="fa fa-university" aria-hidden="true"></i><p>{{ $company_data[0]['address'] }}</p></li>
                                <li><i class="fa fa-phone" aria-hidden="true"></i> <p>{{ $company_data[0]['phone'] }}</p></li>
                                <li><i class="fa fa-phone" aria-hidden="true"></i> <p>{{ $company_data[0]['tlp'] }}</p></li>
                                <li><i class="fa fa-envelope-o" aria-hidden="true"></i> <p><a href="">{{ $company_data[0]['email'] }}</a></p></li>
                            @endif
                            @foreach($social_media as $sosmed)
                                @foreach($sosmed['value'] as $social)
                                    @if($social['url'] != NULL)
                                        <li><i class="fa fa-{{ $social['code'] }}" aria-hidden="true"></i> <p><a href="{{ $social['url'] }}">{{ $social['name'] }}</a></p></li>
                                    @endif
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                    <div class="form-contact">
                        <form method="post" action="{{ route('contact.send') }}">
                            @if(count($errors) > 0)
                                @foreach($errors->all() as $error)
                                    @if($error == 'success')
                                        <div style="color: #3c763d; background-color: #dff0d8; border-color: #d6e9c6; padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px;">
                                            <ul>
                                                <li>Pesan anda telah berhasil dikirim</li>
                                            </ul>
                                        </div>
                                    @else
                                        <div style="color: #a94442; background-color: #f2dede; border-color: #ebccd1; padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px;">
                                            <ul>
                                                <li>{{ $error }}</li>
                                            </ul>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                            <div class="form-input">
                                <label class="important">your name</label>
                                <input type="text" name="name" class="txt-input">
                                <p class="error" style="display: block;"></p>
                            </div>
                            <div class="form-input">
                                <label class="important">your email</label>
                                <input type="text" name="email" class="txt-input">
                                <p class="error" style="display: block;"></p>
                            </div>
                            <div class="form-input">
                                <label>your subject</label>
                                <input type="text" name="subject" class="txt-input">
                            </div>
                            <div class="form-input">
                                <label>your message</label>
                                <textarea class="txt-area" name="message"></textarea>
                            </div>
                            <div class="form-input">
                                <input type="submit" name="" class="btn-submit" value="submit">
                                <a href="" class="btn-cencel">cencel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('script')
<script>
    function initMap() {
        var myLatLng = {
            lat: -8.671977,
            lng: 115.2382722
        };

        var map = new google.maps.Map(document.getElementById('googleMap'), {
            zoom: 16,
            center: myLatLng,
            scrollwheel: false,

        });
        var image = 'images/map-pin.png';
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: image,
            title: 'Hello World!'

        });
    }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDaWKAeXEqr0sHQoV65s2AwKMDZ8hI4hSk&callback=initMap"
  type="text/javascript"></script>
@endsection