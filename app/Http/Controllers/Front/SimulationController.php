<?php

namespace App\Http\Controllers\Front;

use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\InterestRate;
use App\Post;
use App\Company;

class SimulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data["_active_menu"] = "simulasi_kredit";
        $data["_title"] = "Simulasi Kredit :: BRP SEWU";

        $data['pinjaman']  = $request->input('pinjaman');
        $data['bunga']  = $request->input('bunga');
        $data['waktu']  = $request->input('waktu');
        $data['perhitunganbunga']  = $request->input('perhitunganbunga');

        $messages = [
            'required' => ':attribute harus terisi',
            'integer' => ':attribute harus beirisikan angka saja'
        ];

        $validator = Validator::make($request->all(), [
            'pinjaman' => 'required|integer',
            'bunga' => 'required|numeric',
            'waktu' => 'required|integer',
            'perhitunganbunga' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return redirect('/')
                ->withErrors($validator)
                ->withInput();
        }else{
            $date=strtotime(date("M-Y"));
            $sisa_saldo = $data['pinjaman'];
            for ($row = 0; $row < $data['waktu']; $row++) {
                $date=strtotime("+1 months",$date);
                $data['simulasi_kredit_list'][$row][0] = $row+1;
                $data['simulasi_kredit_list'][$row][1] = "01-".date("M-Y", $date);

                if ($data['perhitunganbunga']=="FLAT") {
                    $sisa_saldo = $sisa_saldo - ($data['pinjaman']/$data['waktu']);
                    $data['simulasi_kredit_list'][$row][4] = ($data['pinjaman']*($data['bunga']/100))/$data['waktu'];
                    $data['simulasi_kredit_list'][$row][2] = $data['simulasi_kredit_list'][$row][4]+($data['pinjaman']/$data['waktu']);
                    $data['simulasi_kredit_list'][$row][3] = $data['pinjaman']/$data['waktu'];
                    $data['simulasi_kredit_list'][$row][5] =$sisa_saldo;
                }else if ($data['perhitunganbunga']=="EFEKTIF") {
                    $data['simulasi_kredit_list'][$row][4] = ($sisa_saldo*($data['bunga']/100)*(30/360));
                    $sisa_saldo = $sisa_saldo - ($data['pinjaman']/$data['waktu']);
                    $data['simulasi_kredit_list'][$row][2] = $data['simulasi_kredit_list'][$row][4]+($data['pinjaman']/$data['waktu']);
                    $data['simulasi_kredit_list'][$row][3] = $data['pinjaman']/$data['waktu'];
                    $data['simulasi_kredit_list'][$row][5] =$sisa_saldo;
                } else if ($data['perhitunganbunga']=="ANUITAS") {
                    $angusuran_bulanan = $data['pinjaman'] * (($data['bunga']/100)/12) * (exp($data['waktu'] * log(1+ (($data['bunga']/100)/12)))/(exp($data['waktu'] * log(1+ (($data['bunga']/100)/12))) - 1));
                    $data['simulasi_kredit_list'][$row][4] = ($sisa_saldo*($data['bunga']/100)*(30/360));
                    $data['simulasi_kredit_list'][$row][3] = $angusuran_bulanan - ($sisa_saldo*($data['bunga']/100)*(30/360));
                    $sisa_saldo = $sisa_saldo - ($angusuran_bulanan - ($sisa_saldo*($data['bunga']/100)*(30/360)));
                    $data['simulasi_kredit_list'][$row][2] = $angusuran_bulanan;
                    $data['simulasi_kredit_list'][$row][5] =$sisa_saldo;
              }
          }
            $company= Company::all('id');
            $company_id = $company[0]['id'];
            $data['rate'] = InterestRate::where('company_id', '=', $company_id)->get();
            $type = $request->get('type','BLOG');
            $posts = Post::with('post_category')
                ->where('company_id','=', $company_id)
                ->where('status','=', 1)
                ->where('type','=',$type)
                ->orderBy('created_at','desc');
            $data['recent_blogs'] = $posts->offset(0)->limit(3)->get();
          
          return view('front/simulation')->with($data);
        }
    }
    
    public function kredit_show(Request $request)
    {
        $company= Company::all('id');
        $company_id = $company[0]['id'];
        $data['rate'] = InterestRate::where('company_id', '=', $company_id)->get();
        $type = $request->get('type','BLOG');
        $posts = Post::with('post_category')
            ->where('company_id','=', $company_id)
            ->where('status','=', 1)
            ->where('type','=',$type)
            ->orderBy('created_at','desc');
        $data['recent_blogs'] = $posts->offset(0)->limit(3)->get();
        
        return view('front/simulation')->with($data);
    }

    public function deposito(Request $request)
    {
        $data['_title'] = '';
        if ($_SERVER['REQUEST_URI'] == "/simulasi-tabungan") {
            $data['_title'] = 'Simulasi Tabungan';
            $data['_route'] = 'simulasi.tabungan_show';
        }else{
            $data['_title'] = 'Simulasi Deposito';
            $data['_route'] = 'simulasi.deposito_show';
        }
        
        $company= Company::all('id');
        $company_id = $company[0]['id'];
        $data['rate'] = InterestRate::where('company_id', '=', $company_id)->get();
        $type = $request->get('type','BLOG');
        $posts = Post::with('post_category')
            ->where('company_id','=', $company_id)
            ->where('status','=', 1)
            ->where('type','=',$type)
            ->orderBy('created_at','desc');
        $data['recent_blogs'] = $posts->offset(0)->limit(3)->get();

        return view('front/simulation_deposito')->with($data);
    }

    public function deposito_show(Request $request)
    {
        $data['_title'] = '';
        if ($_SERVER['REQUEST_URI'] == "/simulasi-tabungan") {
            $data['_title'] = 'Simulasi Tabungan';
            $data['_route'] = 'simulasi.tabungan_show';
            
            $data['nominal']  = $request->input('nominal');
            $data['setoran']  = $request->input('setoran');
            $data['bulan'] = 2;
            
            if($request->input('suku_bunga') == NULL){
                $data['suku_bunga']  = 7.75;
            }else{
                $data['suku_bunga']  = $request->input('suku_bunga');
            }
            $data['jangka_waktu']  = $request->input('jangka_waktu');

            $messages = [
                'required' => ':attribute harus terisi',
                'integer' => ':attribute harus beirisikan angka saja',
                'numeric' => ':attribute harus beirisikan angka saja'
            ];

            $validator = Validator::make($request->all(), [
                'nominal' => 'required|integer',
                'suku_bunga' => 'numeric',
                'jangka_waktu' => 'required|integer'
            ], $messages);

            if ($validator->fails()) {
                return redirect(route('simulasi.deposito'))
                    ->withErrors($validator)
                    ->withInput();
            }else{
                if ($data['nominal'] >= 500000) {
                    $data['pajak_bunga'] = 20;
                    $bunga = ($data['suku_bunga']/100)/12;
                    $data['pajak_suku_bunga'] = $bunga;
                    $setoran_awal = $data['nominal'];
                    $nominal = $data['setoran'];
                    $data['variabel'] = array();
                    for ($i=0; $i < $data['jangka_waktu']; $i++) {
                        $cari_bunga = $nominal*$bunga;
                        $sisa_saldo = $cari_bunga;
                        $setoran = $data['nominal'];
                        $bunga_per_bulan = $sisa_saldo;
                        $potongan_bunga = ($sisa_saldo*20)/100;
                        $nominal = $nominal + $sisa_saldo + $setoran - $potongan_bunga; 
                        $data['variabel'][$i] = array('saldo' => $nominal, 'bunga' => $bunga_per_bulan, 'pajak' => $potongan_bunga);
                        
                        $pendapatan = array_sum(array_map(function($item){return $item['bunga'];}, $data['variabel']));
                        $data['pendapatan'] = $pendapatan - end($data['variabel'])['bunga'];

                    }
                    $count = count($data['variabel']) - 2;
                    $data['total'] = $data['variabel'][$count];
                }else{
                    $data['pajak_bunga'] = 20;
                    $bunga = ($data['suku_bunga']/100)/12;
                    $data['pajak_suku_bunga'] = $bunga;
                    $setoran_awal = $data['nominal'];
                    $nominal = $data['setoran'];
                    $data['variabel'] = array();
                    for ($i=0; $i < $data['jangka_waktu']; $i++) {
                        $cari_bunga = $nominal*$bunga;
                        $sisa_saldo = $cari_bunga;
                        $setoran = $data['nominal'];
                        $bunga_per_bulan = $sisa_saldo;
                        $nominal = $nominal + $sisa_saldo + $setoran; 
                        $data['variabel'][$i] = array('saldo' => $nominal, 'bunga' => $bunga_per_bulan);
                        
                        $pendapatan = array_sum(array_map(function($item){return $item['bunga'];}, $data['variabel']));
                        $data['pendapatan'] = $pendapatan - end($data['variabel'])['bunga'];

                    }
                    $count = count($data['variabel']) - 2;
                    $data['total'] = $data['variabel'][$count];
                }
                
                $company= Company::all('id');
                $company_id = $company[0]['id'];
                $data['rate'] = InterestRate::where('company_id', '=', $company_id)->get();
                $type = $request->get('type','BLOG');
                $posts = Post::with('post_category')
                    ->where('company_id','=', $company_id)
                    ->where('status','=', 1)
                    ->where('type','=',$type)
                    ->orderBy('created_at','desc');
                $data['recent_blogs'] = $posts->offset(0)->limit(3)->get();
                
              return view('front/simulation_deposito_show')->with($data);
            }
        }elseif($_SERVER['REQUEST_URI'] == "/simulasi-deposito"){
            $data['_title'] = 'Simulasi Deposito';
            $data['_route'] = 'simulasi.deposito_show';

            $data['nominal']  = $request->input('nominal');
            if($request->input('suku_bunga') == NULL){
                $data['suku_bunga']  = 7.75;
            }else{
                $data['suku_bunga']  = $request->input('suku_bunga');
            }
            $data['jangka_waktu']  = $request->input('jangka_waktu');

            $messages = [
                'required' => ':attribute harus terisi',
                'integer' => ':attribute harus beirisikan angka saja',
                'numeric' => ':attribute harus beirisikan angka saja'
            ];

            $validator = Validator::make($request->all(), [
                'nominal' => 'required|integer',
                'suku_bunga' => 'numeric',
                'jangka_waktu' => 'required|integer'
            ], $messages);

            if ($validator->fails()) {
                return redirect(route('simulasi.deposito'))
                    ->withErrors($validator)
                    ->withInput();
            }else{
                if ($data['nominal'] >= 10000000) {
                    $data['pajak_bunga'] = 20;
                    $sisa_saldo = $data['nominal'];
                    $pendapatan = (($sisa_saldo/100)*$data['suku_bunga'])/12;
                    $total = $sisa_saldo+($pendapatan*$data['jangka_waktu']);
                    $data['pajak_suku_bunga']  = ((($sisa_saldo/100)*$data['suku_bunga'])/12) - ((($sisa_saldo/100)*(($data['suku_bunga']/100)*80))/12);
                    $data['bunga_per_bulan'] = $pendapatan;
                    $data['pendapatan'] = $pendapatan*$data['jangka_waktu'];
                    $data['total'] = $total;
                }else{
                    $data['pajak_bunga'] = 0;
                    $data['pajak_suku_bunga']  = 0;
                    $sisa_saldo = $data['nominal'];
                    $pendapatan = (($sisa_saldo/100)*$data['suku_bunga'])/12;
                    $total = $sisa_saldo+($pendapatan*$data['jangka_waktu']);
                    $data['bunga_per_bulan'] = $pendapatan;
                    $data['pendapatan'] = $pendapatan*$data['jangka_waktu'];
                    $data['total'] = $total;
                }
                
                $company= Company::all('id');
                $company_id = $company[0]['id'];
                $data['rate'] = InterestRate::where('company_id', '=', $company_id)->get();
                $type = $request->get('type','BLOG');
                $posts = Post::with('post_category')
                    ->where('company_id','=', $company_id)
                    ->where('status','=', 1)
                    ->where('type','=',$type)
                    ->orderBy('created_at','desc');
                $data['recent_blogs'] = $posts->offset(0)->limit(3)->get();
                
              return view('front/simulation_deposito_show')->with($data);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
