<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use Mail;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['status'] = NULL;
    	$data['class_status'] = NULL;
    	
        return view('front/contact');
    }
    
    public function sendMail(Request $request){
        $email  = $request->input('email');
        $name  = $request->input('name');
        $subject  = $request->input('subject');
        $mail = $request->input('message');

        $messages = [
            'required' => ':attribute harus terisi'
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'name' => 'required',
            'subject' => 'required',
            'message' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return redirect('/kontak')
                ->withErrors($validator)
                ->withInput();
        }else{
        	$user = (object) ['email' => $email, 'name' => $name, 'subject' => $subject, 'message' => $mail];

	        $send = Mail::send(['text' => 'front/email'], ['user' => $user], function($message) use ($user){
	        	$message->from($user->email, $user->name);

	    		$message->to('info@bpraruna.com', 'Admin BPR Aruna');
	        });

	        if ($send == TRUE) {
	        	return redirect()->back()->withErrors(['success']);
	        }else{
	        	return redirect()->back()->withErrors(['Terjadi kesalahan di sistem! Mohon mencoba kembali nanti']);
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
