<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Company;
use App\PostCategory;
use App\ProductFacilities;
use App\Http\Requests;
use Image;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Log;
use DB;
use AppHelper;
use PdfHelper;
use Storage;

class PostController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company_id=JWTAuth::parseToken()->toUser()->company_id;
        $type = $request->get('type','BLOG');
        if ($request->get('company_id')){
            $company_id=$request->get('company_id');
        }

        if ($request->get('size')){
                $size = $request->get('size', 10);
                $criteria_key = $request->get('criteria_key','name');
                $criteria_value = $request->get('criteria_value','');
                return response()->json(Post::with('post_category')->where('company_id','=',$company_id)
                    ->where('type','=',$type)
                    ->where($criteria_key,"like","%".$criteria_value."%")
                    ->orderBy('created_at','desc')
                    ->paginate($size), 200, [], JSON_NUMERIC_CHECK);
        }

        if ($request->get('status')){

           return response()->json(Post::with('post_category')
            ->where('company_id','=',$company_id)
            ->where('status','=',$request->get('status'))
            ->where('type','=',$type)
            ->orderBy('created_at','desc')
            ->get(), 200, [], JSON_NUMERIC_CHECK);
        }

        return response()->json(Post::with('post_category')
            ->where('company_id','=',$company_id)
            ->where('type','=',$type)
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

        $row=Post::where('name','=',$request->json('name'))
                    ->where('type','=',$request->json('type'))
                       ->where('company_id','=',$request->json('company_id'))->first();
        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Name already exist!'),
                    200
                    );
        }
        $username=JWTAuth::parseToken()->toUser()->username;

        $obj = new Post;
        $id=Uuid::generate();
        $obj->id=$id;
        $obj->name=$request->json('name');
        $obj->description=$request->json('description');
         if ($request->json('status')==true){
            $obj->status=1;
        }else{
            $obj->status=0;
        }

        $obj->post_category_id=$request->json('post_category_id');
        $obj->type=$request->json('type');
        $obj->meta_title=$request->json('meta_title');
        $obj->meta_keyword=$request->json('meta_keyword');
        $obj->meta_description=$request->json('meta_description');
        $obj->created_by = $username;
        $obj->company_id=$request->json('company_id');
        $obj->save();



        return response()->json(array(
                            'error' => false,
                            'id' =>  $id->__toString(),
                            'message' => $request->json('type').' created'),
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
        return response()->json(Post::find($id), 200, [], JSON_NUMERIC_CHECK);
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
        $row=Post::where('name','=',$request->json('name'))
                         ->where('id','<>',$id)
                         ->where('type','=',$request->json('type'))
                         ->where('company_id','=',$company->id)
                         ->first();

        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Code already exist!'),
                    200
                    );
        }

        $obj=Post::find($id);
        $obj->name=$request->json('name');
        $obj->description=$request->json('description');
        $obj->status=$request->json('status');
        $obj->post_category_id=$request->json('post_category_id');
        $obj->type=$request->json('type');
        $obj->meta_title=$request->json('meta_title');
        $obj->meta_keyword=$request->json('meta_keyword');
        $obj->meta_description=$request->json('meta_description');
        $obj->created_by = $username;
        $obj->company_id=$request->json('company_id');
        $obj->save();


        $obj->company_id = $company->id;
        $obj->updated_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();

        return response()->json(array(
                                        'error' => false,
                                        'id' =>  $id,
                                        'message' => $request->json('type').' updated'),
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
        $obj = Post::find($id);
        $company=Company::find($obj->company_id);
        $obj->deleted_by = JWTAuth::parseToken()->toUser()->username;
        $obj->update();
        if ($obj->delete()) {
            // $this->makeLog($request,$company,JWTAuth::parseToken()->toUser()->username,$obj->type.' was deleted');
            return response()->json(array('success' => TRUE));
        }
    }



    public function change_featured_image(Request $request){
        try{
            $post_id=$request->get('post_id');
              $obj = Post::find($post_id);
              if (is_object($obj)){
                  $company=Company::find($request->get('company_id'));
                  $image=AppHelper::upload_image($request,$obj->slug,$company->code,true);
                  if ($image['error']==true){
                       return response()->json(array(
                              'error' => true,
                              'message' => $image['message']),
                              200
                              );
                  }else{
                      $obj->image=$image['image'];
                      $obj->image_thumb=$image['image_thumb'];
                      $obj->save();

                      return response()->json(array(
                          'error' => false,
                          'message' => 'Change Featured Image success'),
                          200
                          );

                  }

             }
            

            
             return response()->json(array(
                              'error' => true,
                              'message' => 'Process upload error!'),
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


        public function upload_pdf(Request $request){
            try{
                $post_id=$request->get('post_id');
                  $obj = Post::find($post_id);
                  if (is_object($obj)){
                      $company=Company::find($request->get('company_id'));
                      $image=PdfHelper::upload_image($request,$obj->slug,$company->code,true);
                      if ($image['error']==true){
                           return response()->json(array(
                                  'error' => true,
                                  'message' => $image['message']),
                                  200
                                  );
                      }else{
                          $obj->image=$image['image'];
                          $obj->image_thumb=$image['image_thumb'];
                          $obj->save();

                          return response()->json(array(
                              'error' => false,
                              'message' => 'Upload PDF success'),
                              200
                              );

                      }

                 }
                

                
                 return response()->json(array(
                                  'error' => true,
                                  'message' => 'Process upload error!'),
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



        public function images($name,$size){
            try{
                $size = explode('x', $size);
            $cache_image = Image::cache(function($image) use($size, $name){
               return $image->make(public_path().'/images/'.$name);

            }, 10); // cache for 10 minutes
            return response()->make($cache_image, 200, ['Content-Type' => 'image']);
            } catch (\Exception $e) {
                    $cache_image = Image::cache(function($image) use($size){
                       return $image->make(public_path().'/images/no_images.jpg')->resize($size[0], $size[1]);

                    }, 10);
                   return response()->make($cache_image, 200, ['Content-Type' => 'image']);
            }


        }



}
