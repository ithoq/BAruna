@extends('front.layout.app')

@section('meta')
    <title>{{ $category_name }} | {{ $company_data[0]['name'] }}</title>
    <meta property="og:locale" content="id_ID" />
    <meta name="description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}">
    <meta name="author" content="{{ $company_data[0]['name'] }}">
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $category_name }} | {{ $company_data[0]['name'] }}" />
    <meta property="og:description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}" />
    <meta property="og:image" content="{{ route('home.show') }}/{{ $company_data[0]['logo'] }}" />
    <meta property="og:image:width" content="480" />
    <meta property="og:image:height" content="480" />
    <meta property="og:site_name" content="{{ $company_data[0]['name'] }}" />
    <meta property="og:url" content="{{ route('report.id', ['id' => strtolower(str_replace(' ', '-', $category_name))]) }}"/>
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}" />
    <meta name="twitter:title" content="{{ $category_name }} | {{ $company_data[0]['name'] }}" />
    <meta name="twitter:image" content="{{ route('home.show') }}/{{ $company_data[0]['logo'] }}" />
@endsection

@section('body')
<div class="wrapper-body">
    <section class="p-title" style="background:url('{{ route('home.show') }}/img/breadcumb/{{ $images[$random_image] }}') no-repeat; background-size: cover; background-position: center top;">
        <div class="black-mask"></div>
        <div class="container-fluid">
            <div class="the-title">
                <h2 style="visibility: hidden;">Laporan Tahunan</h2>
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
                @if($category_name == 'Laporan Publikasi' OR $category_name == 'Laporan Tahunan')
                    <li><a href="{{ route('report.id', ['id' => strtolower(str_replace(' ', '-', $category_name))]) }}">{{ $category_name }}</a></li>
                @else
                    <li><a href="{{ route('report.id', ['id' => 'Laporan-Publikasi']) }}">Laporan Publikasi</a></li>
                    <li><i class="arrow"></i></li>
                    <li><a href="{{ route('report.detail', ['id' => 'Laporan-Publikasi', 'detail' => $category_name]) }}">{{ $category_name }}</a></li>
                @endif
            </ul>
        </div>
    </section>
    <section class="title">
        <div class="container-fluid">
            @if($category_name == 'Laporan Publikasi' OR $category_name == 'Laporan Tahunan')
                <h2>{{ $category_name }}</h2>
            @else
                <h2>Laporan Publikasi {{ $category_name }}</h2>
            @endif
        </div>
    </section>
    <section class="pages-detail">
        <div class="container-fluid">
            <div class="row">
                <div class="p-detail">
                    <div class="laporan">
                        @if(isset($products) AND $category_name == 'Laporan Tahunan')
                            <div class="row">
                                <div class="main-laporan l-tahunan">
                                    @foreach($products as $product)
                                        @foreach($product as $report)
                                            <article class="l-main-laporan">
                                                <div class="wrapper-main-laporan">
                                                    <div class="thumb">
                                                        <a href="{{ $report['pdf'] }}">
                                                            <img src="{{ $report['image_thumb'] }}" alt="{{ $report['name'] }}">
                                                        </a>
                                                    </div>
                                                    <div class="desc">
                                                        <h2>{{ $report['name'] }}</h2>
                                                    </div>
                                                </div>
                                            </article>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        @elseif(isset($products) AND $category_name == 'Laporan Publikasi')
                            <?php $currentDate = ''; ?>
                            <div class="row">
                                <div class="main-laporan l-tahunan">
                                    @foreach($products as $product)
                                        @foreach($product as $report)
                                            @if(Carbon\Carbon::parse($report['date'])->format('Y') != $currentDate)
                                                <?php $currentDate = Carbon\Carbon::parse($report['date'])->format('Y'); ?>
                                                <article class="l-main-laporan">
                                                    <div class="wrapper-main-laporan">
                                                        <div class="thumb">
                                                            <a href="{{ route('report.detail', ['id' => 'Laporan-Publikasi', 'detail' => str_limit($report['date'], '4', '')]) }}">
                                                                <img src="{{ asset('img/thumb-tabungan.png') }}">
                                                            </a>
                                                        </div>
                                                        <div class="desc">
                                                            <h2>Laporan Publikasi {{ str_limit($report['date'], '4', '') }}</h2>
                                                        </div>
                                                    </div>
                                                </article>
                                            @endif 
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="laporan-publikasi">
                                <?php $currentMonth = ''; ?>
                                @foreach($products as $product)
                                    <ul>
                                    @foreach($product as $report)
                                        @if(Carbon\Carbon::parse($report['date'])->format('F') != $currentMonth)
                                            <?php $currentMonth = Carbon\Carbon::parse($report['date'])->format('F'); ?>
                                            <h2>Laporan Publikasi {{ Carbon\Carbon::parse($report['date'])->format('F') }} {{ $category_name }}</h2>
                                        @endif
                                        <li><a href="{{ $report['pdf'] }}">{{ $report['name'] }}</a></li>
                                    @endforeach
                                    </ul>
                                @endforeach
                            </div>
                        @endif
                        @if(count($products) == NULL)
                                <div class="post-holder">
                                    <div class="post-block mb40">
                                        <div class="bg-white">
                                            <h1>Belum ada laporan saat ini</h1>
                                        </div>
                                    </div>
                                </div>
                        @endif
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