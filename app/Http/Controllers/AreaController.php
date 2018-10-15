<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Area;
use App\Company;
use App\Http\Requests;
use Image;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Log;
use DB;
use AppHelper;
use Storage;

class AreaController extends Controller
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
                return response()->json(Area::where('company_id','=',$company_id)
                    ->where($criteria_key,"like","%".$criteria_value."%")
                    ->orderBy('created_at','desc')
                    ->paginate($size), 200, [], JSON_NUMERIC_CHECK);
        }



        return response()->json(Area::where('company_id','=',$company_id)
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

        $row=Area::where('name','=',$request->json('name'))
                       ->where('company_id','=',$request->json('company_id'))->first();
        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Name already exist!'),
                    200
                    );
        }
        $username=JWTAuth::parseToken()->toUser()->username;

        $obj = new Area;
        $id=Uuid::generate();
        $obj->id=$id;
        $obj->name=$request->json('name');
        $obj->description=$request->json('description');
        $obj->created_by = $username;
        $obj->company_id=$request->json('company_id');
        $obj->save();



        return response()->json(array(
                            'error' => false,
                            'id' =>  $id->__toString(),
                            'message' => 'Area created'),
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
        return response()->json(Area::find($id), 200, [], JSON_NUMERIC_CHECK);
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
        $row=Area::where('name','=',$request->json('name'))
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

        $obj=Area::find($id);
        $obj->name=$request->json('name');
        $obj->description=$request->json('description');
        $obj->created_by = $username;
        $obj->company_id=$request->json('company_id');
        $obj->save();


        $obj->company_id = $company->id;
        $obj->updated_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();

        return response()->json(array(
                                        'error' => false,
                                        'id' =>  $id,
                                        'message' => 'Area updated'),
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
        $obj = Area::find($id);
        $company=Company::find($obj->company_id);
        $obj->deleted_by = JWTAuth::parseToken()->toUser()->username;
        $obj->update();
        if ($obj->delete()) {
            return response()->json(array('success' => TRUE));
        }
    }



    public function change_area_image(Request $request){
        try{
            $area_id=$request->get('area_id');
              $obj = Area::find($area_id);
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



    


}
