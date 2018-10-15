@extends('front.layout.app')

@section('meta')
    <title>{{ $post[0]['name'] }} | {{ $company_data[0]['name'] }}</title>
    <meta property="og:locale" content="id_ID" />
    <meta name="description" content="{{ str_limit($post[0]['meta_description'], 200) }}">
    <meta name="author" content="{{ $company_data[0]['name'] }}">
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $post[0]['name'] }} | {{ $company_data[0]['name'] }}" />
    <meta property="og:description" content="{{ str_limit($post[0]['meta_description'], 200) }}" />
    <meta property="og:image" content="{{ route('home.show') }}{{ $post[0]['image'] }}" />
    <meta property="og:image:width" content="480" />
    <meta property="og:image:height" content="480" />
    <meta property="og:site_name" content="{{ route('page.id', ['id' => strtolower(str_replace(' ', '-', $post[0]['name']))]) }}" />
    <meta property="og:url" content="{{ route('page.id', ['id' => strtolower(str_replace(' ', '-', $post[0]['name']))]) }}"/>
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description" content="{{ str_limit($post[0]['meta_description'], 200) }}" />
    <meta name="twitter:title" content="{{ $post[0]['name'] }} | {{ $company_data[0]['name'] }}" />
    <meta name="twitter:image" content="{{ route('home.show') }}{{ $post[0]['image'] }}" />
@endsection

@section('body')
<div class="wrapper-body">
    <section class="p-title" style="background:url('{{ route('home.show') }}/img/breadcumb/{{ $images[$random_image] }}') no-repeat; background-size: cover; background-position: center top;">
        <div class="black-mask"></div>
        <div class="container-fluid">
            <div class="the-title">
                <h2 style="visibility: hidden;">Detail page <span>title here</span></h2>
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
                <li><a href="{{ $_SERVER['REQUEST_URI'] }}">{{ $post[0]['name'] }}</a></li>
            </ul>
        </div>
    </section>
    <section class="title">
        <div class="container-fluid">
            <h2>{{ $post[0]['name'] }}</h2>
        </div>
    </section>
    <section class="pages-detail">
        <div class="container-fluid">
            <div class="row">
                <div class="p-detail">
                    <div class="b-detail">
                        @foreach($post as $blog)
                            <div class="wrapper-b-detail">
                                <img src="{{ $blog['image'] }}" alt="{{ $blog['name'] }}">
                                {!! $blog['description'] !!}
                            </div>
                        @endforeach
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
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection