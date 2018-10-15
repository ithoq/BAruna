<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RoomType;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Log;
use AppHelper;

class RoomTypeController extends Controller
{

    private $module = 'ROOMTYPE';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->get('size')){
                $size = $request->get('size', 10);
                $criteria_key = $request->get('criteria_key','name');
                $criteria_value = $request->get('criteria_value','');
                return response()->json(RoomType::with('income_account')
                                        ->where('company_id','=',$request->get('company_id'))
                                        ->where($criteria_key,"like","%".$criteria_value."%")
                                                    ->paginate($size), 200, [], JSON_NUMERIC_CHECK);
        }

        return response()->json(RoomType::where('company_id','=',$request->get('company_id'))->get(), 200, [], JSON_NUMERIC_CHECK);
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
        $username= JWTAuth::parseToken()->toUser()->username;
        $row=RoomType::where('name','=',$request->json('name'))
                       ->where('company_id','=',$company->id)->first();
        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Name already exist!'),
                    200
                    );
        }


        $obj = new RoomType;
        $id=Uuid::generate();
        $obj->id=$id;
        $obj->name=$request->json('name');
        $obj->income_account_id=$request->json('income_account_id');
        $obj->company_id = $company->id;
        $obj->created_by = $username;
        $obj->save();   
        $obj->id=$id->__toString();
        AppHelper::makeLog($request,$company->id,$username,'success',$this->module,json_encode($obj), 'Create room type '.$obj->name,'room_type',$obj->id);

        return response()->json(array(
                            'error' => false,
                            'message' => 'RoomType created'),
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
        return response()->json(RoomType::find($id), 200, [], JSON_NUMERIC_CHECK);
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
        $username= JWTAuth::parseToken()->toUser()->username;
        //Validate Duplicate Row
        $row=RoomType::where('name','=',$request->json('name'))
                         ->where('id','<>',$id)
                         ->where('company_id','=',$company->id)
                         ->first();
        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Name already exist!'),
                    200
                    );
        }
        $obj=RoomType::find($id);
        $obj->name=$request->json('name');
        $obj->income_account_id=$request->json('income_account_id');
        $obj->company_id = $company->id;
        $obj->updated_by = $username;
        $obj->save();   
        AppHelper::makeLog($request,$company->id,$username,'success',$this->module,json_encode($obj), 'Update room type '.$obj->name,'room_type',$id); 
        return response()->json(array(
                                        'error' => false,
                                        'message' => 'RoomType updated'),
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
        $obj = RoomType::find($id);
        $company=Company::find($obj->company_id);
        $username=JWTAuth::parseToken()->toUser()->username;
        $obj->deleted_by = $username;
        $obj->update();   
        if ($obj->delete()) {
            AppHelper::makeLog($request,$company->id,$username,'success',$this->module,json_encode($obj), 'Delete room type '.$obj->name,'room_type',$id); 
            return response()->json(array('success' => TRUE));
        }
    }


    
}
