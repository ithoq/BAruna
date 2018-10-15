@extends('front.layout.app')

@section('meta')
    <title>Tentang Kami | {{ $company_data[0]['name'] }}</title>
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
                <li><a href="{{ route('about.show') }}">tentang kami</a></li>
            </ul>
        </div>
    </section>
    <section class="title">
        <div class="container-fluid">
            <h2>Tentang <span>Kami</span></h2>
        </div>
    </section>
    <section class="pages-detail">
        <div class="container-fluid">
            <div class="row">
                <div class="p-detail">
                    <div class="about-us">
                        <p style="display:block;box-sizing:content-box;">
                            <span class="img-cols">
                                <img src="img/tentang-kami.jpg">
                                <!-- <i class="text"></i> -->
                            </span>
                            <p>PT. BPR Aruna Nirmaladuta (“BPR Aruna”) adalah bank perkreditan rakyat yang didirikan pada tahun 1992 dan berlokasi di Jalan Darma Giri No. 99 Gianyar – Bali, dengan nomor telepon 0361 – 8958344, faximile 0361 – 8958365, dan alamat email di info@bpraruna.com, Sejak awal berdirinya hingga saat ini, BPR Aruna Aruna secara konsisten terus berperan aktif dalam membantu dan meningkatkan usaha yang ditekuni oleh masyarakat khususnya untuk jenis usaha yang tergolong dalam Usaha Mikro Kecil Menengah (UMKM). Hal ini sejalan dengan Visi, Misi, dan Tagline BPR Aruna yaitu:</p>
                        </p>
                        <div style="clear:both;"></div>
                        <p>
                            <div class="quote">
                                <h3><b>Visi, Misi & Tagline</b></h3>
                                <blockquote>
                                    <p>Menjadi Bank Perkreditan Rakyat Yang Tangguh Dalam Memberikan Pelayanan Kepada Usaha Mikro.</p>
                                    <p>Membudayakan Disiplin KerjaGuna Menjadi BPR Yang Berkualitas Dalam Mendorong Pertumbuhan Perekonomian.</p>
                                    <p>Berkarya Dengan Komitmen.</p>
                                </blockquote>
                            </div>
                            <p >Komitmen penuh mulai dari para pemegang saham, direksi, dewan komisaris, dan seluruh karyawan terhadap pemahaman dan pelaksanaan visi, misi, dan tagline ini menjadikan BPR Aruna tetap tumbuh dan berkembang secara sehat hingga saat ini. Dari tahun ke tahun, secara konsisten BPR Aruna menunjukan kinerja yang positif. Per 31 Desember 2017 aset bank tercatat Rp. 134 milyar; dana pihak ketiga Rp. 48 milyar; total kredit Rp. 100 milyar; dan rasio kredit bermasalah (NPL) sebesar 1,78%. Sesuai dengan Rencana Bisnis BPR dalam jangka pendek (1 tahun), jangka menengah (3 tahun) dan jangka panjang (5 tahun), BPR Aruna akan tetap meningkatkan fungsi intermediasi melalui rekrutmen, pelatihan, menjaga hubungan baik dengan nasabah existing, meningkatkan kegiatan penjualan dan pemasaran melalui kegiatan promosi produk yang dimiliki baik produk simpanan maupun produk pinjaman. Saat ini produk simpanan yang dimiliki oleh BPR Aruna terdiri dari Tabungan Umum, Taxi Aruna, Tamba Plus, Tabunganku, dan Deposito. Sementara itu di produk pinjaman, BPR Aruna memiliki Kredit Modal Kerja, Kredit Konsumtif, dan Kredit Investasi. Sejalan dengan komitmen untuk tumbuh dan berkembang secara sehat, BPR Aruna senantiasa meningkatkan penerapan tata kelola yang baik (Good Corporate Governance/GCG) dan penerapan manajemen risiko BPR sesuai ketentuan yang disyaratkan otoritas dengan cara membangun budaya kerja, budaya kepatuhan, memperkuat kerangka manajemen risiko termasuk memperkuat pengendalian internal (internal control) dan audit internal, meningkatkan pengawasan Direksi, Komisaris serta komite yang ada termasuk untuk memastikan transparansi dan akurasi dalam pelaporan, memastikan kecukupan kebijakan dan prosedur, serta terpenuhinya kepatuhan dan komitmen Bank. Rencana pengembangan sumber daya manusia dan rencana pengembangan teknologi informasi juga menjadi rencana penting yang akan dilakukan untuk menjadikan BPR Aruna sebagai BPR yang terbaik di Gianyar dan Bali.</p>
                        </p>
                    </div>

                    <div class="team">
                        <div class="title">KOMISARIS BPR ARUNA</div>
                        <div class="row col-md-offset-2 col-md-8 col-sm-12 col-xs-12">
                            <article class="l-team m-team">
                                <div class="wrapper-team">
                                    <div class="thumb">
                                        <img src="img/komisaris-1.jpg">
                                    </div>
                                    <div class="desc">
                                        <a href="{{ route('profile.id', 'i-nyoman-sumertha') }}"><h2>I Nyoman Sumertha</h2></a>
                                        <p>Komisaris Utama</p>
                                        <div class="sosmed">
                                            <a href="{{ route('profile.id', 'i-nyoman-sumertha') }}" class="i-sosmed linked">Profil Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <article class="l-team m-team">
                                <div class="wrapper-team">
                                    <div class="thumb">
                                        <img src="img/komisaris-2.jpg">
                                    </div>
                                    <div class="desc">
                                        <a href="{{ route('profile.id', 'i-ketut-gede-juarta-sabudi') }}"><h2>Ir. I Ketut Gede Juarta Sabudi</h2></a>
                                        <p>Komisaris</p>
                                        <div class="sosmed">
                                            <a href="{{ route('profile.id', 'i-ketut-gede-juarta-sabudi') }}" class="i-sosmed linked">Profil Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="title row col-sm-12 col-xs-12">DIREKSI BPR ARUNA</div>
                        <div class="row">
                            <article class="l-team">
                                <div class="wrapper-team">
                                    <div class="thumb">
                                        <img src="img/Direktur-utama.jpg">
                                    </div>
                                    <div class="desc">
                                        <a href="{{ route('profile.id', 'i-ketut-gede-mardawa') }}"><h2>I Ketut Gede Mardawa</h2></a>
                                        <p>Direktur Utama</p>
                                        <div class="sosmed">
                                            <a href="{{ route('profile.id', 'i-ketut-gede-mardawa') }}" class="i-sosmed linked">Profil Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <article class="l-team">
                                <div class="wrapper-team">
                                    <div class="thumb">
                                        <img src="img/Direksi-1.jpg">
                                    </div>
                                    <div class="desc">
                                        <a href="{{ route('profile.id', 'raka-erna-dwajayanti') }}"><h2>A. A. Raka Erna Dwajayanti</h2></a>
                                        <p>Direktur Bisnis</p>
                                        <div class="sosmed">
                                            <a href="{{ route('profile.id', 'raka-erna-dwajayanti') }}" class="i-sosmed linked">Profil Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <article class="l-team">
                                <div class="wrapper-team">
                                    <div class="thumb">
                                        <img src="img/Direksi-3.jpg">
                                    </div>
                                    <div class="desc">
                                        <a href="{{ route('profile.id', 'gede-ari-wibawa-putra-sedana') }}"><h2>Gede Ari Wibawa Putra Sedana</h2></a>
                                        <p>Direktur Kepatuhan</p>
                                        <div class="sosmed">
                                            <a href="{{ route('profile.id', 'gede-ari-wibawa-putra-sedana') }}" class="i-sosmed linked">Profil Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
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