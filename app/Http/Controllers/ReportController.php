<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use App\ReportCategory;
use App\Company;
use App\Http\Requests;
use Image;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Log;
use DB;
use PdfHelper;
use AppHelper;
use Storage;

class ReportController extends Controller
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
                return response()->json(Report::with('report_category')->where('company_id','=',$company_id)
                    ->orderBy('created_at','desc')
                    ->paginate($size), 200, [], JSON_NUMERIC_CHECK);
        }

        if ($request->get('status')){

           return response()->json(Report::with('report_category')
            ->where('company_id','=',$company_id)
            ->where('status','=',$request->get('status'))
            ->orderBy('created_at','desc')
            ->get(), 200, [], JSON_NUMERIC_CHECK);
        }

        return response()->json(Report::with('post_category')
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

        $row=Report::where('name','=',$request->json('name'))
                       ->where('company_id','=',$request->json('company_id'))
                       ->whereMonth('date', '=', $request->json('date'))->first();
        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Name already exist!'),
                    200
                    );
        }
        $username=JWTAuth::parseToken()->toUser()->username;

        $obj = new Report;
        $id=Uuid::generate();
        $obj->id=$id;
        $obj->name=$request->json('name');
         if ($request->json('status')==true){
            $obj->status=1;
        }else{
            $obj->status=0;
        }

        $obj->report_category_id=$request->json('report_category_id');
        if ($request->json('report_category') > 0) {
            $slug = ReportCategory::find($request->json('report_category'));
            $obj->slug = $slug[0]['slug'];
        }
        $obj->date = $request->json('date');
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
        return response()->json(Report::find($id), 200, [], JSON_NUMERIC_CHECK);
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
        $row=Report::where('name','=',$request->json('name'))
                         ->where('id','<>',$id)
                         ->where('company_id','=',$company->id)
                         ->whereMonth('date', '=', $request->json('date'))
                         ->first();

        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Code already exist!'),
                    200
                    );
        }

        $obj=Report::find($id);
        $obj->name=$request->json('name');
        $obj->status=$request->json('status');
        $obj->report_category_id=$request->json('report_category_id');
        if ($request->json('report_category') > 0) {
            $slug = ReportCategory::find($request->json('report_category'));
            $obj->slug = $slug[0]['slug'];
        }
        $obj->date = $request->json('date');
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
        $obj = Report::find($id);
        $company=Company::find($obj->company_id);
        $obj->update();
        if ($obj->delete()) {
            // $this->makeLog($request,$company,JWTAuth::parseToken()->toUser()->username,$obj->type.' was deleted');
            return response()->json(array('success' => TRUE));
        }
    }



    public function upload_pdf(Request $request){
        try{
            $report_id=$request->get('post_id');
              $obj = Report::find($report_id);
              if (is_object($obj)){
                  $company=Company::find($request->get('company_id'));
                  $image=PdfHelper::upload_image($request,$obj->name,$company->code,true);
                  if ($image['error']==true){
                       return response()->json(array(
                              'error' => true,
                              'message' => $image['message']),
                              200
                              );
                  }else{
                      $obj->pdf=$image['image'];
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

        public function upload_image(Request $request){
        try{
            $report_id=$request->get('post_id');
              $obj = Report::find($report_id);
              if (is_object($obj)){
                  $company=Company::find($request->get('company_id'));
                  $image=AppHelper::upload_image($request,$obj->name,$company->code,true);
                  if ($image['error']==true){
                       return response()->json(array(
                              'error' => true,
                              'message' => $image['message']),
                              200
                              );
                  }else{
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
