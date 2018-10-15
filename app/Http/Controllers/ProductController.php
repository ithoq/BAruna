<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Company;
use App\Category;
use App\ProductFacilities;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Log;
use DB;
class ProductController extends Controller
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

        if ($request->get('size')){
                $size = $request->get('size', 10);
                $criteria_key = $request->get('criteria_key','name');
                $criteria_value = $request->get('criteria_value','');
                return response()->json(Product::with('category','product_gallery')->where('company_id','=',$company_id)
                    ->where($criteria_key,"like","%".$criteria_value."%")
                    ->orderBy('created_at','desc')
                    ->paginate($size), 200, [], JSON_NUMERIC_CHECK);
        }


        return response()->json(Product::with('category','product_gallery')
            ->where('company_id','=',$company_id)
            ->orderBy('created_at','desc')
            ->get(), 200, [], JSON_NUMERIC_CHECK);
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
        
        $row=Product::where('name','=',$request->json('name'))
                       ->where('company_id','=',$request->json('company_id'))->first();
        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Name already exist!'),
                    200
                    );
        }
        $username=JWTAuth::parseToken()->toUser()->username;

        $obj = new Product;
        $id=Uuid::generate();
        $obj->id=$id;
        $obj->name=$request->json('name');
        $obj->address=$request->json('address');
        $obj->description=$request->json('description');
        $obj->category_id=$request->json('category_id');
        $obj->meta_title=$request->json('meta_title');
        $obj->meta_keyword=$request->json('meta_keyword');
        $obj->meta_description=$request->json('meta_description');
        $obj->created_by = $username;
        $obj->company_id=$request->json('company_id');
        $obj->save();   

        if ($request->json('facilities')){
            foreach ($request->json('facilities') as $key => $value) {
                    if($value['is_checked']==1 || $value['is_checked']==true){
                        $property_facilities =  new ProductFacilities;
                        $property_facilities->id=Uuid::generate();
                        $property_facilities->product_id=$id->__toString();
                        $property_facilities->facilities_id=$value['id'];
                        $property_facilities->created_by = $username;
                        $property_facilities->company_id = $request->json('company_id');
                        $property_facilities->save();
                    }
                    
                }
        }

        
        return response()->json(array(
                            'error' => false,
                            'id' =>  $id->__toString(),
                            'message' => 'Product created'),
                            200
                            );
    }


    public function update_main_image(Request $request,$id){
        $product=Product::find($id);
        $product->image_id=$request->json('image_id');
        $product->save();
         return response()->json(array(
                            'error' => false,
                            'message' => 'Main Images updated'),
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
        return response()->json(Product::find($id), 200, [], JSON_NUMERIC_CHECK);
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
         $username=JWTAuth::parseToken()->toUser()->username;
        $company=Company::find($request->json('company_id'));
        //Validate Duplicate Row
        $row=Product::where('name','=',$request->json('name'))
                         ->where('id','<>',$id)
                         ->where('company_id','=',$company->id)
                         ->first();
        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Code already exist!'),
                    200
                    );
        }
        $obj=Product::find($id);
        $obj->name=$request->json('name');
        $obj->address=$request->json('address');
        $obj->image_id=$request->json('image_id');
        $obj->description=$request->json('description');
        $obj->category_id=$request->json('category_id');
        $obj->meta_title=$request->json('meta_title');
        $obj->meta_keyword=$request->json('meta_keyword');
        $obj->meta_description=$request->json('meta_description');
        $obj->updated_by = $username;
        $obj->company_id=$request->json('company_id');
        $obj->save();   

          $product_facilities_old =  ProductFacilities::where('product_id','=', $id)->get();
            foreach ($product_facilities_old as $list_delete) {
                $list_delete->delete();
            }

        if ($request->json('facilities')){
            foreach ($request->json('facilities') as $key => $value) {
                    if($value['is_checked']==1 || $value['is_checked']==true){
                        $property_facilities =  new ProductFacilities;
                        $property_facilities->id=Uuid::generate();
                        $property_facilities->product_id=$id;
                        $property_facilities->facilities_id=$value['id'];
                        $property_facilities->created_by = $username;
                        $property_facilities->company_id = $request->json('company_id');
                        $property_facilities->save();
                    }
                    
                }
        }

        
        $obj->company_id = $company->id;
        $obj->updated_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();   

        return response()->json(array(
                                        'error' => false,
                                        'message' => 'Product updated'),
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
        $obj = Product::find($id);
        $company=Company::find($obj->company_id);
        $obj->deleted_by = JWTAuth::parseToken()->toUser()->username;
        $obj->update();   
        if ($obj->delete()) {
            
            return response()->json(array('success' => TRUE));
        }
    }


}
