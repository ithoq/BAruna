<?php

namespace App\Http\Controllers\Front;

use Validator;
use Illuminate\Http\Request;
use App\AddCreditCategory;
use App\AddCredit;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Log;
use AppHelper;

class AddCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company= Company::all('id');
        $company_id = $company[0]['id'];
        $credit_category_id = AddCreditCategory::where('company_id','=',$company_id)->get();
        return view('front/addcredit', compact('credit_category_id'));
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
        $messages = [
            'type.required' => 'Tipe Kredit harus di pilih',
            'plafond.required' => 'Plafond harus di isi',
            'plafond.numeric' => 'Plafond harus merupakan sebuah angka',
            'penggunaan.required' => 'Tujuan Penggunaan harus di isi',
            'penggunaan.min' => 'Tujuan Penggunaan tidak boleh kurang dari :min',
            'nama.required' => 'Nama harus di isi',
            'nama.string' => 'Nama harus berupa huruf',
            'tanggal_lahir.required' => 'Tanggal Lahir harus di isi',
            'tanggal_lahir.date_format' => 'Tanggal Lahir harus sesuai dengan format tahun-bulan-tanggal',
            'hp.numeric' => 'No Handphone harus berisikan angka saja',
            'hp.max' => 'No Handphone tidak boleh melebihi dari :max angka',
            'ktp.required' => 'No KTP harus di isi',
            'ktp.max' => 'No KTP tidak boleh melebihi dari :max angka',
            'ktp.numeric' => 'No KTP harus merupakan sebuah angka',
            'npwp.max' => 'No NPWP tidak boleh melebihi dari :max angka',
            'npwp.required' => 'No NPWP harus di isi',
            'npwp.numeric' => 'No NPWP harus merupakan sebuah angka',
            'hp.min' => 'No Handphone tidak boleh kurang dari :min angka',
            'hp.required' => 'No Handphone harus di isi',
            'hp.numeric' => 'No Handphone harus merupakan sebuah angka',
            'hp.max' => 'No Handphone tidak boleh melebihi dari :max angka',
            'pekerjaan.required' => 'Pekerjaan harus di cantumkan',
            'pekerjaan.string' => 'Pekerjaan harus berupa huruf',
            'email.required' => 'Email harus di isi',
            'email.email' => 'Format Email salah atau email tidak benar',
            'alamat_ktp.required' => 'Alamat KTP harus di isi',
            'alamat_ktp.min' => 'Alamat KTP harus melebihi :min angka',
            'referensi.string' => 'Referensi harus berupa huruf saja',
            'lokasi_agunan.required' => 'Lokasi Agunan harus di isi',
            'lokasi_agunan.string' => 'Lokasi Agunan harus berupa huruf saja',
            'jaminan.required' => 'Jamin harus di pilih salah satu',
            'file.required' => 'Foto KTP harus di cantumkan',
            'file.size' => 'Foto KTP tidak boleh melebihi ukuran dari :size MB',
            'file.image' => 'Foto KTP yang di unggah harus berformat gambar',
            'verify.accepted' => 'Centang untuk menyetujui pengajuan kredit'
        ];

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'plafond' => 'required|numeric',
            'penggunaan' => 'required|min:20',
            'nama' => 'required|string',
            'tanggal_lahir' => 'required|date_format:"Y-m-d"',
            'ktp' => 'required|numeric|digits_between:15,16',
            'npwp' => 'required|numeric|digits_between:14,15',
            'pekerjaan' => 'required|string',
            'hp' => 'required|numeric|digits_between:7,12',
            'email' => 'required|email',
            'alamat_ktp' => 'required|min:20',
            'referensi' => 'string',
            'lokasi_agunan' => 'required|string',
            'jaminan' => 'required',
            'file' => 'required|image|max:1024',
            'verify' => 'accepted',
        ], $messages);

        if ($validator->fails()) {
            return redirect('/formulir-pengajuan-kredit-online')
                ->withErrors($validator)
                ->withInput();
        }else{
            // Company
            $company= Company::all();
            $company_id = $company[0]['id'];
            $company_code = $company[0]['code'];
            // Slug
            $slug = str_replace(' ', '-', $request->input('nama'));
            $slug = strtolower($slug);
            // Insert Data
            $obj = new AddCredit;
            $id=Uuid::generate();
            $obj->id=$id;
            $obj->add_credit_type=$request->input('type');
            $obj->plafond=$request->input('plafond');
            $obj->utilization=$request->input('penggunaan');
            $obj->name=$request->input('nama');
            $obj->date_of_birth=$request->input('tanggal_lahir');
            $obj->no_ktp=$request->input('ktp');
            $obj->no_npwp=$request->input('npwp');
            $obj->employment=$request->input('pekerjaan');
            $obj->no_hp=$request->input('hp');
            $obj->email=$request->input('email');
            $obj->address=$request->input('alamat_ktp');
            $obj->reference=$request->input('referensi');
            $obj->location_collateral=$request->input('lokasi_agunan');
            $obj->guarantee=$request->input('jaminan');
            $image=AppHelper::upload_image($request,$slug,$company_code,true);
            if ($image['error']==true){
                return redirect('/form-credit')
                    ->withErrors($image['message']);
            }else{
                $obj->images_ktp=$image['image'];
            }
            $obj->company_id = $company_id;
            // Save
            $obj->save();

            return redirect('/form-credit')->with('msg', 'Pengajuan Kredit anda telah berhasil di kirim');
        }
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
