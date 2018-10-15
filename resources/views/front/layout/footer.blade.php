<footer id="footer">
    <div class="top-footer">
        <a href="">
            <img src="{{ asset('img/ic-ayokebank.jpg') }}">
        </a>
        <a href="">
            <img src="{{ asset('img/ic-lps.jpg') }}">
        </a>
        <a href="">
            <img src="{{ asset('img/ic-ojk.jpg') }}">
        </a>
    </div>
    <div class="bottom-footer">
        <div class="wrapper-footer-bottom">
            <div class="container-fluid">                   
                <div class="row">
                    <div class="bottom-about">
                        <div class="logo-bottom">
                            <a href="">
                                <img src="{{ asset('img/ic-ayokebank.jpg') }}">
                            </a>
                            <a href="">
                                <img src="{{ asset('img/ic-lps.jpg') }}">
                            </a>
                            <a href="">
                                <img src="{{ asset('img/ic-ojk.jpg') }}">
                            </a>
                            <a href="">
                                <img src="{{ asset('img/logo-bpr.jpg') }}">
                            </a>
                        </div>
                        <p>
                            BPR Aruna Terdaftar dan Diawasi OJK
                        </p>
                    </div>
                    <div class="bottom-menu">
                        <ul>
                            <li><a href="{{ route('home.show') }}">Beranda</a></li>
                            <li><a href="{{ route('about.show') }}">Tentang Kami</a></li>
                            <li><a href="{{ route('product.show') }}">Layanan & Produk</a></li>
                            <li><a href="{{ route('blog.show') }}">Berita</a></li>
                            <li><a href="{{ route('report.id', ['id' => strtolower(str_replace(' ', '-', 'Laporan Tahunan'))]) }}" title="Laporan Tahunan">Laporan Tahunan</a></li>
                            <li><a href="{{ route('contact.show') }}">Kontak</a></li>
                            <li><a href="{{ route('report.id', ['id' => strtolower(str_replace(' ', '-', 'Laporan Publikasi'))]) }}" title="Laporan Publikasi">Laporan Publikasi</a></li>
                            <li><a href="{{ route('home.show') }}/halaman/karir">Karir</a></li>
                        </ul>
                    </div>
                    <div class="sosmed-bottom">
                        <ul>
                            @foreach($social_media as $sosmed)
                                @foreach($sosmed['value'] as $social)
                                    @if($social['url'] != NULL)
                                        <li><a href="{{ $social['url'] }}"><i class="fa fa-{{ $social['code'] }}" aria-hidden="true"></i> {{ $social['name'] }}</a></li>
                                    @endif
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>