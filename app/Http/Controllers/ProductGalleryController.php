<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductGallery;
use App\Images;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use App\Product;
use Image;
use DB;
use AppHelper;
use Storage;

class ProductGalleryController extends Controller
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

        if ($request->get('product_id')){
            return response()->json(ProductGallery::where('company_id','=',$company_id)
                ->where('product_id','=',$request->get('product_id'))->get(),200, [], JSON_NUMERIC_CHECK);       
        }
        return response()->json(ProductGallery::where('company_id','=',$company_id)->get(),200, [], JSON_NUMERIC_CHECK);
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
           

            $product=Product::find($request->get('product_id'));
            $company=Company::find($request->get('company_id'));
            $sql="select count(id) as total 
            from product_gallery where company_id='".$company->id."'
            and product_id='".$product->id."'";
            $total=1;
            $data_total=DB::select($sql);
            if (sizeof($data_total)>0){
                $total=$data_total[0]->total+1;
            }
            $image=AppHelper::upload_image($request,$product->slug.'-'.$total,$company->code,true);
            if ($image['error']==true){
                 return response()->json(array(
                        'error' => true,
                        'message' => $image['message']),
                        200
                        );
            }else{
                $obj = new ProductGallery;
                $id=Uuid::generate();
                $obj->id=$id;
                if ($request->get('product_id')){
                    $obj->product_id=$request->get('product_id');
                }
                $obj->company_id=$request->get('company_id');
                // $obj->title=$request->json('title');
                $obj->image=$image['image'];
                $obj->image_thumb=$image['image_thumb'];
                // $obj->original_image_id=$image['original_image_id'];
                $obj->created_by = JWTAuth::parseToken()->toUser()->username;
                $obj->save();  

                 $ProductGallery=ProductGallery::find($id->__toString());

                 
                 if (is_object($product)){
                    if ($product->image_id==null){
                        $product->image_id=$ProductGallery->id;
                        $product->save();
                    }
                 }
                  DB::commit(); 

            }
            
             return response()->json(array(
                        'error' => false,
                        'data' => $ProductGallery,
                        'dev_data' => $image['dev_data'],
                        'message' => 'ProductGallery created'),
                        200
                        );

           
    

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
        $obj =ProductGallery::find($id);
        $obj->title=$request->json('title');    
        $obj->updated_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();   
        return response()->json(array(
                            'error' => false,
                            'id' => $id,
                            'message' => 'ProductGallery created'),
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
        $obj = ProductGallery::find($id);
         //delete imae from aws s3
            // if(Storage::disk('s3')->has(str_replace(env('S3_URL'),'',$obj->image)))
            // {
            //     Storage::disk('s3')->delete([str_replace(env('S3_URL'),'',$obj->image),
            //     str_replace(env('S3_URL'),'',$obj->image_thumb)]);
            // }
        $image_public_id=$obj->original_image_id;
        $image=AppHelper::deleteimage($image_public_id);
        if ($image['error']==true){
             return response()->json(array(
                    'error' => true,
                    'message' => $image['message']),
                    200
                    );
        }
        else{
            if ($obj->delete()) {
                return response()->json(array('success' => TRUE, 'dev_data' => $image, 'dev_data2' => $image_public_id));
            }
        }
    }

}
