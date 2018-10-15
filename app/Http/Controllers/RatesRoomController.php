<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RatesRoom;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Log;
use AppHelper;

class RatesRoomController extends Controller
{

    private $module = 'RATESROOM';
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
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
        //Validate Duplicate Row
        $company=Company::find($request->json('company_id'));
        $username=JWTAuth::parseToken()->toUser()->username;
        $obj = new RatesRoom;
        $id=Uuid::generate();
        $obj->id=$id;
        $obj->rates_id=$request->json('rates_id');
        $obj->room_type_id=$request->json('room_type_id');
        $obj->rates=$request->json('rates');
        $obj->company_id = $company->id;
        $obj->created_by = $username;
        $obj->save();   
        $obj->id=$id->__toString();
        AppHelper::makeLog($request,$company->id,$username,'success',$this->module,json_encode($obj), 'Create room rates ','rates',$obj->rates_id);
        
        return response()->json(array(
                            'error' => false,
                            'id' =>$obj->id,
                            'message' => 'Rates Room created'),
                            200
                            );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(RatesRoom::find($id), 200, [], JSON_NUMERIC_CHECK);
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
        $company=Company::find($request->json('company_id'));
        $username=JWTAuth::parseToken()->toUser()->username;
        //Validate Duplicate Row
       
        $obj=RatesRoom::find($id);
        $obj->rates_id=$request->json('rates_id');
        $obj->room_type_id=$request->json('room_type_id');
        $obj->rates=$request->json('rates');
        $obj->company_id = $company->id;
        $obj->updated_by = $username;
        $obj->save();   
        AppHelper::makeLog($request,$company->id,$username,'success',$this->module,json_encode($obj), 'Update room rates ','rates',$obj->rates_id);
        return response()->json(array(
                                        'error' => false,
                                        'id' => $id,
                                        'message' => 'Rates Room updated'),
                                        200
                                        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $obj = RatesRoom::find($id);
        $company=Company::find($obj->company_id);
        $username=JWTAuth::parseToken()->toUser()->username;
        $obj->deleted_by = $username;
        $obj->update();   
        if ($obj->delete()) {
            AppHelper::makeLog($request,$company->id,$username,'success',$this->module,json_encode($obj), 'Delete room rates','rates',$obj->rates_id); 
            return response()->json(array('success' => TRUE));
        }
    }


   
}
