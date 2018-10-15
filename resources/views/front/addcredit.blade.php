@extends('front.layout.app')

@section('meta')
    <title>Formulir Pengajuan Credit | {{ $company_data[0]['name'] }}</title>
    <meta property="og:locale" content="id_ID" />
    <meta name="description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}">
    <meta name="author" content="{{ $company_data[0]['name'] }}">
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Formulir Pengajuan Credit | {{ $company_data[0]['name'] }}" />
    <meta property="og:description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}" />
    <meta property="og:image" content="{{ route('home.show') }}/{{ $company_data[0]['logo'] }}" />
    <meta property="og:image:width" content="480" />
    <meta property="og:image:height" content="480" />
    <meta property="og:site_name" content="{{ $company_data[0]['name'] }}" />
    <meta property="og:url" content="{{ route('credit.show') }}"/>
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description" content="{{ str_limit($company_data[0]['meta_description'], 200) }}" />
    <meta name="twitter:title" content="Formulir Pengajuan Credit | {{ $company_data[0]['name'] }}" />
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
                <li><a href="{{ route('credit.show') }}">pengajuan kredit</a></li>
            </ul>
        </div>
    </section>
    <section class="title">
        <div class="container-fluid">
            <h2>Pengajuan <span>Kredit</span></h2>
        </div>
    </section>
    <section class="pengajuan-kredit">
        <div class="container-fluid">
            <div class="wrapper-pengajuan-kredit">
                @if(session()->has('msg'))
                    <div class="alert alert-success">
                        {{ session()->get('msg') }}
                    </div>
                @endif
                <form method="post" action="{{ route('credit.store') }}" enctype="multipart/form-data">                              
                    <div class="row">
                        <div class="form-input">
                            <label class="important">dengan ini saya mengajukan</label>
                            <select class="select">
                                @foreach($credit_category_id as $credit_type)
                                    <option value="{{ $credit_type->id }}">{{ $credit_type->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('type') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-input">
                            <label class="important">jumlah plafond</label>
                            <input id="Plafond" class="txt-input" name="plafond" type="text" value="{{ old('plafond') }}">
                            @if($errors->has('plafond'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('plafond') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-input full-size">
                            <label class="important">tujuan penggunaan</label>
                            <textarea class="txt-area" id="penggunaan" rows="7" name="penggunaan">{{ old('penggunaan') }}</textarea>
                            @if($errors->has('penggunaan'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('penggunaan') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-input full-size">
                            <p><b>Data calon debitur</b></p>
                        </div>
                        <div class="form-input">
                            <label class="important">Nama</label>
                            <input id="nama" name="nama" type="text" class="txt-input" value="{{ old('nama') }}">
                            @if($errors->has('nama'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('nama') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-input">
                            <label class="important">tanggal lahir</label>
                            <input id="datepicker" name="tanggal_lahir" type="text" class="txt-input" value="{{ old('tanggal_lahir') }}">
                            @if($errors->has('tanggal_lahir'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('tanggal_lahir') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-input">
                            <label class="important">no ktp</label>
                            <input id="ktp" name="ktp" type="text" class="txt-input" value="{{ old('ktp') }}">
                            @if($errors->has('ktp'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('ktp') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-input">
                            <label class="important">no npwp</label>
                            <input id="npwp" name="npwp" type="text" class="txt-input" value="{{ old('npwp') }}">
                            @if($errors->has('npwp'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('npwp') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-input b-3">
                            <label class="important">pekerjaan</label>
                            <input id="pekerjaan" name="pekerjaan" type="text" class="txt-input" value="{{ old('pekerjaan') }}">
                            @if($errors->has('pekerjaan'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('pekerjaan') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-input b-3">
                            <label class="important">no hp</label>
                            <input id="hp" name="hp" type="text" class="txt-input" value="{{ old('hp') }}">
                            @if($errors->has('hp'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('hp') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-input b-3">
                            <label class="important">email</label>
                            <input id="email" name="email" type="text" class="txt-input" value="{{ old('email') }}">
                            @if($errors->has('email'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('email') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-input full-size">
                            <label class="important">alamat ktp</label>
                            <textarea class="txt-area alamat" id="alamat_ktp" rows="7" name="alamat_ktp">{{ old('alamat_ktp') }}</textarea>
                            @if($errors->has('alamat_ktp'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('alamat_ktp') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-input full-size">
                            <p><b>jaminan</b></p>
                        </div>
                        <div class="form-input">
                            <label class="important">nama referensi marketing (jika tidak ada cukup input -)</label>
                            <input id="referensi" name="referensi" type="text" class="txt-input" value="{{ old('referensi') }}">
                            @if($errors->has('referensi'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('referensi') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-input">
                            <label class="important">lokasi angunan</label>
                            <input id="lokasi_agunan" name="lokasi_agunan" type="text" class="txt-input" value="{{ old('lokasi_agunan') }}">
                            @if($errors->has('lokasi_agunan'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('lokasi_agunan') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-input">
                            <label class="important">jenis jaminan</label>
                            <select class="select" name="jaminan">
                                <option value="Sertifikat">Sertifikat</option>
                                <option value="BPKB">BPKB</option>
                            </select>
                            @if($errors->has('jaminan'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('jaminan') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-input">
                            <label class="important">FOTO KTP ( MAXSIMAL UPLOAD 1MB )</label>
                            <input id="foto_ktp" name="file" type="file" class="t-file">
                            @if($errors->has('file'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('file') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif
                        </div>
                        <div class="form-input full-size">
                            <input id="chk-box" class="chk" name="verify" type="checkbox">
                            <label for="chk-box">dengan mengisi formulir ini, anda dinyatakan telah mengajukan permohonan kredit</label>
                            <br>
                            @if($errors->has('verify'))
                                <span style="color: #ff2f2f">
                                    @foreach($errors->get('verify') as $message)
                                        *{{ $message }}
                                    @endforeach
                                </span>
                            @endif                                      
                        </div>
                        <div class="form-input">
                            <input type="submit" name="" class="btn-submit" value="kirim">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection