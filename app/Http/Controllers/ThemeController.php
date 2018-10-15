<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Theme;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use Image;
use Storage;
use JWTAuth;
use Log;
use AppHelper;
class ThemeController extends Controller
{

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        if ($request->get('company_id')){
            $company=Company::find($request->get('company_id'));
            if ($request->get('type')){
                return response()->json(Theme::where('type','=',$request->get('type'))
                ->where('business_type','=',$company->business_type)->get(), 200, [], JSON_NUMERIC_CHECK);            
            }    
        }
        

        return response()->json(Theme::get(), 200, [], JSON_NUMERIC_CHECK);

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
        $row=Theme::where('code','=',$request->json('code'))
                    ->first();
        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Code already exist!'),
                    200
                    );
        }


        $obj = new Theme;
        $id=Uuid::generate();
        $obj->id=$id;
        $obj->code=$request->json('code');
        $obj->name=$request->json('name');
        $obj->description=$request->json('description');
        $obj->setting=$request->json('setting');
        $obj->type=$request->json('type');
        $obj->business_type=$request->json('business_type');
        $obj->demo_url=$request->json('demo_url');
        if ($request->json('active')==true){
            $obj->active=1;
        }else{
            $obj->active=0;
        }

        $obj->save();   
        return response()->json(array(
                            'error' => false,
                            'id' => $id->__toString(),
                            'message' => 'Theme created'),
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
        return response()->json(Theme::find($id), 200, [], JSON_NUMERIC_CHECK);
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
        
        //Validate Duplicate Row
        $row=Theme::where('code','=',$request->json('code'))
                         ->where('id','<>',$id)
                         ->first();
        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Code already exist!'),
                    200
                    );
        }
        $obj=Theme::find($id);
        $obj->code=$request->json('code');
        $obj->name=$request->json('name');
        $obj->description=$request->json('description');
        $obj->demo_url=$request->json('demo_url');
        $obj->setting=$request->json('setting');
        $obj->type=$request->json('type');
        $obj->business_type=$request->json('business_type');
        if ($request->json('active')==true){
            $obj->active=1;
        }else{
            $obj->active=0;
        }
        $obj->save();   
        return response()->json(array(
                                        'error' => false,
                                        'id' => $id,
                                        'message' => 'Theme updated'),
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
        $obj = Theme::find($id);
        
        if ($obj->delete()) {
            return response()->json(array('success' => TRUE));
        }
    }



    public function upload(Request $request){
        try{
            $id=$request->get('id');

            if ($id){
                $obj = Theme::find($id);
                $image_result=AppHelper::upload_image($request,Uuid::generate(),'theme',true);
                if ($image_result['error']==true){
                     return response()->json(array(
                            'error' => true,
                            'message' => $image_result['message']),
                            200
                            );
                }else{

                    
                    $obj->image=$image_result['image'];
                    $obj->image_thumb=$image_result['image_thumb'];
                    $obj->save();

                }

            }

            

            return response()->json(array(
            'error' => false,
            'message' => 'Change  Image success'),
            200
            );

             } catch (\Exception $e) {
                   return response()->json(array(
                        'error' => true,
                        'message' => $e->getMessage()),
                        200
                        );
            }


        }

    
}
