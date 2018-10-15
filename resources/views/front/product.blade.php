@extends('front.layout.app')

@section('meta')
    <title>{{ $products[0]['name'] }} | {{ $company_data[0]['name'] }}</title>
    <meta property="og:locale" content="id_ID" />
    <meta name="description" content="{{ str_limit($products[0]['meta_description'], 200) }}">
    <meta name="author" content="{{ $company_data[0]['name'] }}">
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ $products[0]['name'] }} | {{ $company_data[0]['name'] }}" />
    <meta property="og:description" content="{{ str_limit($products[0]['meta_description'], 200) }}" />
    <meta property="og:image" content="{{ route('home.show') }}{{ $products[0]->product_gallery['image'] }}" />
    <meta property="og:image:width" content="480" />
    <meta property="og:image:height" content="480" />
    <meta property="og:site_name" content="{{ $company_data[0]['name'] }}" />
    <meta property="og:url" content="{{ route('product.id', ['id' => strtolower(str_replace(' ', '-', $products[0]['name']))]) }}"/>
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description" content="{{ str_limit($products[0]['meta_description'], 200) }}" />
    <meta name="twitter:title" content="{{ $products[0]['name'] }} | {{ $company_data[0]['name'] }}" />
    <meta name="twitter:image" content="{{ route('home.show') }}{{ $products[0]->product_gallery['image'] }}" />
@endsection

@section('body')
<div class="wrapper-body">
    <section class="p-title" style="background:url('{{ route('home.show') }}/img/breadcumb/{{ $images[$random_image] }}') no-repeat; background-size: cover; background-position: center top;">
        <div class="black-mask"></div>
        <div class="container-fluid">
            <div class="the-title">
                <h2 style="visibility: hidden;">Detail <span>produk</span></h2>
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
                <li><a href="{{ route('product.show') }}">layanan & produk</a></li>
                <li><i class="arrow"></i></li>
                <li><a href="{{ route('product.category', ['id' => strtolower(str_replace(' ', '-', $products[0]->category['name']))]) }}">{{ $products[0]->category['name'] }}</a></li>
                <li><i class="arrow"></i></li>
                <li><a href="{{ route('product.id', ['id' => strtolower(str_replace(' ', '-', $products[0]['name']))]) }}">{{ $products[0]['name'] }}</a></li>
            </ul>
        </div>
    </section>
    <section class="title">
        <div class="container-fluid">
            <h2>{{ $products[0]['name'] }}</h2>
        </div>
    </section>
    <section class="pages-detail">
        <div class="container-fluid">
            <div class="row">
                <div class="p-detail">
                    <div class="t-detail">
                        <div class="wrapper-b-detail">
                            <div class="about-us">
                                <span class="img-cols">
                                    <img src="{{ $products[0]->product_gallery['image'] }}" alt="{!! $products[0]['name'] !!}" style="display:  block;margin: 0 auto;width: 50%;">
                                    <!-- <i class="text"></i> -->
                                </span>
                                <p>
                                    {!! $products[0]['description'] !!}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="list-3-cols">
						<div class="title">layanan & produk lainnya</div>
						<div class="row">
							<div class="list-layanan">
								<div class="wrapper-list owl-carousel">
								    @if(count($product) > 0)
                                        @foreach($product as $slider)
        									<article class="list">
        										<div class="content-list">
        											<div class="thumbnail">
        												<a href="{{ route('product.id', ['id' => strtolower(str_replace(' ', '-', $slider['name']))]) }}"><img src="{{ $slider->product_gallery['image'] }}"></a>
        											</div>
        											<div class="description">
        												<h3>
        													<span style="width: 100%;text-overflow: ellipsis-word;white-space: nowrap;overflow: hidden;">{{ $slider['name'] }}</span>
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
                                            <a href="{{ route('product.id', ['id' => strtolower($recent_blog['name'])]) }}">
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
@section('script')
<script>

  //js layanan
  var owl = $(".wrapper-list");

  owl.owlCarousel({
    loop: true,
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