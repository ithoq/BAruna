@extends('front.layout.app')

@section('meta')
    <title>Profil Ir. I Ketut Gede Juarta Sabudi | {{ $company_data[0]['name'] }}</title>
    <meta property="og:locale" content="id_ID" />
    <meta name="description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}">
    <meta name="author" content="{{ $company_data[0]['name'] }}">
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Tentang Kami | {{ $company_data[0]['name'] }}" />
    <meta property="og:description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}" />
    <meta property="og:image" content="{{ route('home.show') }}/{{ $company_data[0]['logo'] }}" />
    <meta property="og:image:width" content="480" />
    <meta property="og:image:height" content="480" />
    <meta property="og:site_name" content="{{ $company_data[0]['name'] }}" />
    <meta property="og:url" content="{{ route('about.show') }}"/>
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}" />
    <meta name="twitter:title" content="Tentang Kami | {{ $company_data[0]['name'] }}" />
    <meta name="twitter:image" content="{{ route('home.show') }}/{{ $company_data[0]['logo'] }}" />
@endsection

@section('body')
<div class="wrapper-body">
    <section class="p-title" style="background:url('{{ route('home.show') }}/img/breadcumb/{{ $images[$random_image] }}') no-repeat; background-size: cover; background-position: center top;">
        <div class="black-mask"></div>
        <div class="container-fluid">
            <div class="the-title">
                <h2 style="visibility: hidden;">Pengajuan <span>Kredit</span></h2>
                <p style="visibility: hidden;">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque consequat lectus ut suscipit maximus. Maecenas leo tellus, pharetra id lorem quis, accumsan tempus magna. Ut congue diam augue, sit amet porttitor ex eleifend ut. Nunc in auctor velit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
                </p>
            </div>
        </div>
    </section>
    <section class="breadcumb">
        <div class="container-fluid">
            <ul>
                <li><a href="{{ route('home.show') }}">beranda</a></li>
                <li><i class="arrow"></i></li>
                <li><a href="{{ route('profile.id', 'i-ketut-gede-juarta-sabudi') }}">Profil Ir. I Ketut Gede Juarta Sabudi</a></li>
            </ul>
        </div>
    </section>
    <section class="title">
        <div class="container-fluid">
            <h2>Profil <span>Ir. I Ketut Gede Juarta Sabudi</span></h2>
        </div>
    </section>
    <section class="pages-detail">
        <div class="container-fluid">
            <div class="row">
                <div class="p-detail">
                  <div class="row">
                    <div class="col-lg-3">
                      <a href="#">
                        <img src="http://demo.bpraruna.com/img/komisaris-2.jpg" alt="image profile" class="l-profile-image">
                      </a>
                    </div>
                    <div class="col-lg-6">
                      <h5 style="margin-top: 0px;color:#4ea0ff;font-size: 2.25rem;font-family: 'Roboto', sans-serif;">Komisaris<br><small style="color:#4ea0ff;font-size: 80%;font-weight: 400;">Menjabat sebagai Komisaris.<br>Sejak tanggal 19 Maret 2018.</small></h5>
                      <!--<p style="font-family: 'Roboto', sans-serif;">Gelar Insinyur dari Fakultas Peternakan, Universitas Udayana, Denpasar.</p>-->
                      <!-- <p style="font-family: 'Roboto', sans-serif;">Address: Namapara, Trishal, Mymensingh</p> -->
                    </div>
                    <!--<div class="col-md-3 text-center">-->
                    <!--  <span class="number" style="font-size:18px">Phone:<strong>+620813229080</strong></span>-->
                      <!--<div class="button" style="padding-top:18px">-->
                      <!--  <a href="#" class="btn btn-email">Kirim Email</a>-->
                      <!--</div>-->
                    <!--  <div class="team" style="border-top:0;padding-top:0;">-->
                    <!--    <div class="row">-->
                    <!--      <div class="l-team" style="width:100%;">-->
                    <!--        <div class="wrapper-team" style="box-shadow:none;">-->
                    <!--          <div class="desc" style="border:0;padding:0;">-->
                    <!--            <div class="sosmed" style="text-align:center;">-->
                    <!--                <a href="" class="i-sosmed linked" style="margin-left:0;"><i class="fa fa-linkedin" aria-hidden="true"></i></a>-->
                    <!--                <a href="" class="i-sosmed fb"><i class="fa fa-facebook" aria-hidden="true"></i></a>-->
                    <!--                <a href="" class="i-sosmed tw"><i class="fa fa-twitter" aria-hidden="true"></i></a>-->
                    <!--            </div>-->
                    <!--          </div>-->
                    <!--        </div>-->
                    <!--      </div>-->
                    <!--    </div>-->
                    <!--  </div>-->
                    <!--</div>-->
                  </div>
                  <div class="row">
                    <div class="about-us col-lg-12">
                      <h3 style="color:#4ea0ff;font-family: 'Roboto', sans-serif;font-size:3.25rem;">Biografi</h3>
                      <p>Lahir di Tabanan, Bali pada tanggal 29 Juli 1963. Beliau memperoleh gelar Insinyur dari Fakultas Peternakan, Universitas Udayana, Denpasar.
                        Beliau menjabat sebagai Komisaris PT. BPR Aruna Nirmaladuta sejak tanggal 19 Maret 2018. Beliau memiliki pengalaman selama lebih dari 28 tahun dalam industri perbankan. Karier beliau dimulai sebagai tenaga marketing di Bank Dagang Bali pada tahun 1989. Selanjutnya, beliau pernah bekerja di Tamara Bank sebagai Kepala Bagian Kredit, bekerja di PT. BPR Sinar Kuta sebagai Direktur Utama dan terakhir bekerja di PT. BPR Aruna Nirmaladuta sebagai Direktur.
                      </p>
                    </div>
                  </div>
                </div>
                <div class="p-widget">
                    <div class="widget-side">
                        <div class="wrapper-widget">
                            <div class="tables">
                                <div class="t-head">
                                    Suku Bunga
                                </div>
                                @if(count($rate) > 0)
                                    @foreach($rate as $rates)
                                        <div class="t-body">
                                            <div class="t-8">
                                                {{ $rates['name'] }}
                                            </div>
                                            <div class="t-2">
                                                {{ $rates['description'] }}
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="t-footer">
                                    <div class="t-8">
                                        Deposito
                                    </div>
                                    <div class="t-2">
                                        LPS
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wrapper-widget">
                            <div class="title">customer care</div>
                            <div class="customer-care">
                                @if(count($company_data) > 0)
                                    <div class="l-care">
                                        <i class="fa fa-building" aria-hidden="true"></i>
                                        <p>{{ $company_data[0]['address'] }}</p>
                                    </div>
                                    <div class="l-care">
                                        <i class="fa fa-envelope-open-o" aria-hidden="true"></i> <a href="" target="_blank">{{ $company_data[0]['email'] }}</a>
                                    </div>
                                    <div class="l-care">
                                        <i class="fa fa-phone" aria-hidden="true"></i> {{ $company_data[0]['phone'] }}
                                    </div>
                                    <div class="l-care">
                                        <i class="fa fa-phone" aria-hidden="true"></i> {{ $company_data[0]['tlp'] }}
                                    </div>
                                @endif
                                @foreach($social_media as $sosmed)
                                    @foreach($sosmed['value'] as $social)
                                        @if($social['url'] != NULL)
                                            <div class="l-care">
                                                <i class="fa fa-{{ $social['code'] }}" aria-hidden="true"></i> <a href="{{ $social['url'] }}" target="_blank">@bpraruna</a>
                                            </div>
                                        @endif
                                    @endforeach
                                @endforeach
                            </div>
                        </div>

                        <div class="wrapper-widget">
                            <div class="title">berita terbaru</div>
                            <div class="list-one-cols">
                                @if(count($recent_blogs) > 0)
                                    @foreach($recent_blogs as $recent_blog)
                                        <article class="list">
                                            <a href="">
                                                <img src="{{ $recent_blog['image_thumb'] }}" alt="{{ $recent_blog['name'] }}">
                                                <div class="text">
                                                    <p>
                                                        {{ $recent_blog['name'] }}
                                                    </p>
                                                </div>
                                            </a>
                                        </article>
                                    @endforeach
                                @else
                                    <h4 class="recent-title mb10">Tidak ada Berita Terbaru</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
