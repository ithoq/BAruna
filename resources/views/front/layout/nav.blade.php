<header id="header" class="relative">
    <div class="container-fluid">
        <div class="wrapper-header">
            <div class="logo">
                <a href="{{ route('home.show') }}" style="background: url({{ route('home.show') }}/{{ $company_data[0]['logo'] }}) no-repeat;">BPR Aruna</a>
            </div>  

            <!-- menu desktop -->
            <div class="nav">
                <ul>
                    <li><a href="{{ route('home.show') }}">Beranda</a></li>
                    <li><a href="{{ route('about.show') }}">tentang kami</a></li>
                    <li>
                        <a href="{{ route('product.show') }}">layanan & produk</a>
                        <ul>
                            @if(isset($categorys))
                                @foreach($categorys as $category)
                                    <li class="h-title"><a href="javascript:void(0)">{{ $category['name'] }}</a></li>
                                        @foreach($products_menu as $product)
                                            @if($category['name'] == $product['category']['name'])
                                                <li><a href="{{ route('product.id', ['id' => strtolower(str_replace(' ', '-', $product['name']))]) }}" title="{{ $product['name'] }}">{{ $product['name'] }}</a></li>
                                            @endif
                                        @endforeach
                                @endforeach
                            @endif
                        </ul>
                    </li>
                    <li>
                        <a>Simulasi</a>
                            <ul>
                                <li>
                                    <a href="{{ route('simulasi.deposito') }}">Simulasi Deposito</a>
                                </li>
                                <li>
                                    <a href="{{ route('simulasi.kredit_show') }}">Simulasi Kredit</a>
                                </li>
                                <li>
                                    <a href="{{ route('simulasi.tabungan') }}">Simulasi Tabungan</a>
                                </li>
                            </ul>
                        </li>
                    <li><a href="{{ route('blog.show') }}">berita</a></li>
                    <li>
                        <a style="cursor: default;">laporan keuangan</a>
                        <ul>
                            @if(isset($ReportCategory))
                                @foreach($ReportCategory as $report)
                                    <li><a href="{{ route('report.id', ['id' => strtolower(str_replace(' ', '-', $report['name']))]) }}" title="{{ $report['name'] }}">{{ $report['name'] }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </li>
                    <li><a href="{{ route('home.show') }}/halaman/karir">karir</a></li>
                    <li><a href="{{ route('contact.show') }}">kontak</a></li>
                </ul>
            </div>


            <!-- btn click nav mobile -->
            <div class="btn-nav-mobile">
                <div class="wrapper-btn-nm">
                    <input id="btn-nm" class="btn-nm" type="checkbox" />
                    <label for="btn-nm">
                        <!-- menu mobile -->
                        <div class="nav-mobile" id="nav-mobile">
                            <div class="nav-mobile-wrapper">
                                <!-- home -->
                                <input type="radio" name="nav-mobile" id="beranda">
                                <label for="beranda">
                                    <span class="def"><a href="{{ route('home.show') }}">beranda</a></span>
                                </label>

                                <!-- tentang kami -->
                                <input type="radio" name="nav-mobile" id="us">
                                <label for="us">
                                    <span class="def"><a href="{{ route('about.show') }}">tentang kami</a></span>
                                </label>

                                <!-- tabungan -->
                                <input type="radio" name="nav-mobile" id="tabungan">
                                <label for="tabungan" class="child-9">
                                    <span>tabungan</span>

                                    <!-- menu dropdown -->
                                    <div class="nav-mobile-dropdown">
                                        <ul>
                                            @foreach($products_menu as $product)
                                                <li><a href="{{ route('product.id', ['id' => strtolower(str_replace(' ', '-', $product['name']))]) }}" title="{{ $product['name'] }}">{{ $product['name'] }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </label>

                                <!-- Simulasi -->
                                <input type="radio" name="nav-mobile" id="simulasi">
                                <label for="simulasi" class="child-9">
                                    <span>Simulasi</span>

                                    <!-- menu dropdown -->
                                    <div class="nav-mobile-dropdown">
                                        <ul>
                                            <li>
                                                <a href="{{ route('simulasi.deposito') }}">Simulasi Deposito</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('simulasi.kredit_show') }}">Simulasi Kredit</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('simulasi.tabungan') }}">Simulasi Tabungan</a>
                                            </li>
                                        </ul>
                                    </div>
                                </label>
                                
                                <!-- berita -->
                                <input type="radio" name="nav-mobile" id="berita">
                                <label for="berita">
                                    <span class="def"><a href="{{ route('blog.show') }}">berita</a></span>
                                </label>

                                <!-- laporan keuangan -->
                                <input type="radio" name="nav-mobile" id="money">
                                <label for="money" class="child-9">
                                    <span>laporan keuangan</span>

                                    <!-- menu dropdown -->
                                    <div class="nav-mobile-dropdown">
                                        <ul>
                                            @if(isset($ReportCategory))
                                                @foreach($ReportCategory as $report)
                                                    <li><a href="{{ route('report.id', ['id' => strtolower(str_replace(' ', '-', $report['name']))]) }}" title="{{ $report['name'] }}">{{ $report['name'] }}</a></li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </label>
                                
                                <!-- Page -->
                                <input type="radio" name="nav-mobile" id="karir">
                                <label for="karir">
                                    <span class="def"><a href="{{ route('home.show') }}/halaman/karir">Karir</a></span>
                                </label>
                                
                                <!-- kontak -->
                                <input type="radio" name="nav-mobile" id="kontak">
                                <label for="kontak">
                                    <span class="def"><a href="{{ route('contact.show') }}">kontak</a></span>
                                </label>
                            </div>
                        </div>
                    </label>    
                </div>                      
            </div>
        </div>
    </div>
</header>