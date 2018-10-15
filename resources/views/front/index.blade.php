@extends('front.layout.app')

@section('meta')
    <title>Beranda | {{ $company_data[0]['name'] }}</title>
    <meta property="og:locale" content="id_ID" />
    <meta name="description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}">
    <meta name="author" content="{{ $company_data[0]['name'] }}">
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Beranda | {{ $company_data[0]['name'] }}" />
    <meta property="og:description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}" />
    <meta property="og:image" content="{{ route('home.show') }}/{{ $company_data[0]['logo'] }}" />
    <meta property="og:image:width" content="480" />
    <meta property="og:image:height" content="480" />
    <meta property="og:site_name" content="{{ $company_data[0]['name'] }}" />
    <meta property="og:url" content="{{ route('home.show') }}"/>
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}" />
    <meta name="twitter:title" content="Beranda | {{ $company_data[0]['name'] }}" />
    <meta name="twitter:image" content="{{ route('home.show') }}/{{ $company_data[0]['logo'] }}" />
@endsection

@section('body')
<div class="wrapper-body">
    <section class="banner-heroes">
        <div class="container-fluid">                   
            <div class="heroes-slider swiper-container">
                <div class="swiper-wrapper">
                    @if(count($Slider) > 0)
                        @foreach($Slider as $slider)
                                <div class="swiper-slide">
                                    <img src="{{ $slider->image }}" class="img-desktop">
                                    <img src="{{ $slider->image }}" class="img-mobile">
                                </div>
                        @endforeach()
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="p-layanan">
        <div class="container-fluid">
            <h2>PT. BPR Aruna Nirmaladuta</h2>
            <p>
                PT. BPR Aruna Nirmaladuta (“BPR Aruna”) adalah bank perkreditan rakyat yang didirikan pada tahun 1992. Sejak awal berdirinya hingga saat ini, BPR Aruna Aruna secara konsisten terus berperan aktif dalam membantu dan meningkatkan usaha yang ditekuni oleh masyarakat khususnya untuk jenis usaha yang tergolong dalam Usaha Mikro Kecil Menengah (UMKM).
            </p>

            <div class="list-layanan">
                <div class="wrapper-list owl-carousel">
                    @if(isset($categorys))
                        @foreach($categorys as $category)
                            <article class="list">
                                <div class="content-list">
                                    <div class="thumbnail">
                                        <a href="{{ route('product.category', ['id' => strtolower($category['slug'])]) }}"><img src="{{ $category['thumb'] }}" alt="{{ $category['name'] }}"></a>
                                    </div>
                                    <div class="description">
                                        <h3>
                                            <span>{{ $category['name'] }}</span>
                                        </h3>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    @endif
                </div>

                <a href="javascript:void(0)" id="prev-layanan"><i class="layanan"></i></a>
                <a href="javascript:void(0)" id="next-layanan"><i class="layanan"></i></a>
            </div>
        </div>
    </section>

    <section class="call-us">
        <div class="mask"></div>
    </section>

    <section class="us">
        <div class="container-fluid">
            <div class="row">
                <div class="banner">
                    <a href="{{ route('credit.show') }}">
                        <img src="img/banner.jpg">
                    </a>
                </div>
                <div class="simulasi-kredit">
                    <div class="wrapper-content">
                        <h3>Simulasi <span>Kredit</span></h3>
                        
                        <form method="post" action="{{ route('simulasi.kredit') }}">
                          @if(count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                          @endif
                          <div class="box"> 
                            <input name="pinjaman" type="text" placeholder="Jumlah Pinjaman" required="">
                          </div>
                          <div class="box">
                            <input name="bunga" type="text" placeholder="Bunga per Tahun" required="">
                          </div>
                          <div class="box">
                            <input name="waktu" type="text" placeholder="Jangka Waktu (Bulan)" required="">
                          </div>
                          <div class="box">
                            <select name="perhitunganbunga" class="select">
                              <option value="ANUITAS">Anuitas</option>
                              <option value="FLAT">Flat</option>
                              <option value="EFEKTIF">Efektif</option>
                            </select>
                          </div>
                          <div class="box">
                            <input type="submit" name="" class="btn-submit" value="hitung">
                          </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="text-us">
                <h3>Berkarya Dengan <span>Komitmen</span></h3>
                <p>
                    Komitmen penuh mulai dari para pemegang saham, direksi, dewan komisaris, dan seluruh karyawan terhadap pemahaman dan pelaksanaan visi, misi, dan tagline ini menjadikan BPR Aruna tetap tumbuh dan berkembang secara sehat hingga saat ini. Dari tahun ke tahun, secara konsisten BPR Aruna menunjukan kinerja yang positif.
                </p>
                <!--<ul>-->
                <!--    <li>Deposito dengan bunga tinggi</li>-->
                <!--    <li>Pelayanan yang ramah</li>-->
                <!--    <li>Bagi kami, kepuasan anda adalah yang utama</li>-->
                <!--    <li>Keamanan dan kenyaman menjadi prioritas</li>-->
                <!--</ul>-->
            </div>

            <div class="img-us">
                <div class="photo">
                    <img src="img/thank-you.jpg">

                    <div class="blue-text">
                        <p>
                            kepuasan anda adalah <br />
                            <b>senyum kami</b>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('script')
<script> 
    //js heroes banner
    var heroes = new Swiper('.heroes-slider', {

        // Optional parameters
        direction: 'horizontal',
        loop: true,
        speed: 1000,
        spaceBetween: 0,
        autoplay: 4000,
        effect: 'slide', //'slide', 'fade', 'cube', 'coverflow', 'flip'

        // If we need pagination
        pagination: '.heroes-paginaion',
        paginationClickable: true,

        // Navigation arrows
        //nextButton: '.swiper-button-next.desktop',
        //prevButton: '.swiper-button-prev.desktop',
    });

    //js layanan
    var owl = $(".wrapper-list");           
    
    owl.owlCarousel({
        loop: false,
        margin: 0,
        responsiveClass:true,
        responsive:{
            0:{
                items: 1,
                nav: false
            },
            480:{
                items: 1,
                nav: false
            },
            554:{
                items: 1,
                nav: false
            },
            768:{
                items: 2,
                nav: false
            },
            992:{
                items: 2,
                nav: false
            },
            1020:{
                items: 2,
                nav: false
            },
            1200: {
                items: 3,
                nav: false
            }
        },
    }); 

    //custom button
    $("#prev-layanan").click(function(){
        owl.trigger('prev.owl.carousel');
    })

    $("#next-layanan").click(function(){
        owl.trigger('next.owl.carousel');
    })
</script>
@endsection