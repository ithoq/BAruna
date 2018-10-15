<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gallery;
use App\Images;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Image;
use DB;
use AppHelper;
use Storage;
class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company_id=JWTAuth::parseToken()->toUser()->company_id;

        if ($request->get('company_id')){
            $company_id=$request->get('company_id');            
        }
        return response()->json(Gallery::with('gallery_category')->where('company_id','=',$company_id)->get(),200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         
        
    }

    public function store(Request $request)
    {
          DB::beginTransaction();

        try {
           $company=Company::find($request->get('company_id'));
            $image=AppHelper::upload_image($request,Uuid::generate(),$company->code,true);
                  if ($image['error']==true){
                       return response()->json(array(
                              'error' => true,
                              'message' => $image['message']),
                              200
                              );
                  }else{
                            $obj = new Gallery;
                            $id=Uuid::generate();
                            $obj->id=$id;
                            $obj->company_id=$request->get('company_id');
                            $obj->title=$request->json('title');
                            $obj->gallery_category_id=$request->input('gallery_category_id');
                            $obj->image=$image['image'];
                            $obj->image_thumb=$image['image_thumb'];
                            $obj->created_by = JWTAuth::parseToken()->toUser()->username;
                            $obj->save();  

                             $Gallery=Gallery::with('gallery_category')->find($id->__toString());

                              DB::commit(); 
                             return response()->json(array(
                                        'error' => false,
                                        'data' => $Gallery,
                                        'message' => 'Gallery created'),
                                        200
                                        );

                  }

          

           
    

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(array(
                'error' => true,
                'message' => $e->getMessage()),
                200
                );
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
        $obj =Gallery::find($id);
        $obj->title=$request->json('title');
        $obj->gallery_category_id=$request->json('gallery_category_id');
        $obj->updated_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();   
        return response()->json(array(
                            'error' => false,
                            'id' => $id,
                            'message' => 'Gallery created'),
                            200
                            );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = Gallery::find($id);
         //delete imae from aws s3
            // if(Storage::disk('s3')->has(str_replace(env('S3_URL'),'',$obj->image)))
            // {
            //     Storage::disk('s3')->delete([str_replace(env('S3_URL'),'',$obj->image),
            //     str_replace(env('S3_URL'),'',$obj->image_thumb)]);
            // }

        if (count($obj->image) > 0) {
            unlink(public_path($obj->image));
        }
        
        
        if ($obj->delete()) {
            return response()->json(array('success' => TRUE));
        }
    }
}
