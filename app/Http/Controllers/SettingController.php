<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Image;
use DB;
use Storage;

class SettingController extends Controller
{
   

   public function generateId(){
     echo Uuid::generate();
   }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
          $company_id=JWTAuth::parseToken()->toUser()->company_id;

        if ($request->get('company_id')){
            $company_id=$request->get('company_id');            
        }

        if ($request->get('name')){
            return response()->json(Setting::where('company_id','=',$company_id)
                ->where('name','=',$request->get('name'))->get(),200, [], JSON_NUMERIC_CHECK);           
        }

     


         if ($request->get('group')){
            return response()->json(Setting::where('company_id','=',$company_id)
                ->where('group','=',$request->get('group'))->orderBy('created_at','asc')->get(),200, [], JSON_NUMERIC_CHECK);           
        }

        return response()->json(Setting::where('company_id','=',$company_id)->get(),200, [], JSON_NUMERIC_CHECK);
        
    }



    public function show($id){
             return response()->json(Setting::find($id),200, [], JSON_NUMERIC_CHECK);    
    }

      /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $company=Company::find($request->json('company_id'));
        
        $obj=Setting::find($id);
        $obj->name=$request->json('name');
        $obj->value=$request->json('value');
        $obj->company_id = $company->id;
        $obj->updated_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();   
        return response()->json(array(
                                        'error' => false,
                                        'message' => 'Setting updated'),
                                        200
                                        );
    }

    
}
