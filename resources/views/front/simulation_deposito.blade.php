@extends('front.layout.app')

@section('meta')
    <title>{{ $_title }} | {{ $company_data[0]['name'] }}</title>
    <meta property="og:locale" content="id_ID" />
    <meta name="description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}">
    <meta name="author" content="{{ $company_data[0]['name'] }}">
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $_title }} | {{ $company_data[0]['name'] }}" />
    <meta property="og:description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}" />
    <meta property="og:image" content="{{ route('home.show') }}/{{ $company_data[0]['logo'] }}" />
    <meta property="og:image:width" content="480" />
    <meta property="og:image:height" content="480" />
    <meta property="og:site_name" content="{{ $company_data[0]['name'] }}" />
    <meta property="og:url" content="{{ route('simulasi.deposito') }}"/>
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}" />
    <meta name="twitter:title" content="{{ $_title }} | {{ $company_data[0]['name'] }}" />
    <meta name="twitter:image" content="{{ route('home.show') }}/{{ $company_data[0]['logo'] }}" />
@endsection

@section('body')
<div class="wrapper-body">
  <section class="p-title" style="background:url('{{ route('home.show') }}/img/breadcumb/{{ $images[$random_image] }}') no-repeat; background-size: cover; background-position: center top;">
    <div class="black-mask"></div>
    <div class="container-fluid">
      <div class="the-title">
        <h2 style="visibility: hidden;">tabel <span>{{ $_title }}</span></h2>
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
        <li><a>tabel {{ $_title }}</a></li>
      </ul>
    </div>
  </section>
  <section class="title">
        <div class="container-fluid">
            <h2>tabel <span>{{ $_title }}</span></h2>
        </div>
    </section>
  <section class="pages-detail">
    <div class="container-fluid">
      <div class="row">
        <div class="p-simulasi">
          <div class="form-simulasi">
            <form method="post" action="{{ route($_route) }}">
              @if(count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
              @endif
              @if($_title == "Simulasi Deposito")
                  <div class="box"> 
                    <input name="nominal" type="text" placeholder="Setoran" required="">
                  </div>
              @elseif($_title == "Simulasi Tabungan")
                  <div class="box"> 
                    <input name="setoran" type="text" placeholder="Setoran Awal" required="">
                  </div>
                  <div class="box"> 
                    <input name="nominal" type="text" placeholder="Setoran per-bulan" required="">
                  </div>
              @endif
              <div class="box">
                <input name="suku_bunga" type="text" placeholder="Suku Bunga" required="">
              </div>
              <div class="box">
                <input name="jangka_waktu" type="text" placeholder="Jangka Waktu (Bulan)" required="">
              </div>
              <div class="box">
                <input type="submit" name="" class="btn-submit" value="hitung">
              </div>
            </form>
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