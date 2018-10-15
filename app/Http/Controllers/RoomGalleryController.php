<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RoomGallery;
use App\Images;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use App\Room;
use Image;
use DB;
use AppHelper;
use Storage;

class RoomGalleryController extends Controller
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

        if ($request->get('room_id')){
            return response()->json(RoomGallery::where('company_id','=',$company_id)
                ->where('room_id','=',$request->get('room_id'))->get(),200, [], JSON_NUMERIC_CHECK);
        }
        return response()->json(RoomGallery::where('company_id','=',$company_id)->get(),200, [], JSON_NUMERIC_CHECK);
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


            $room=Room::find($request->get('room_id'));
            $company=Company::find($request->get('company_id'));
            $sql="select count(id) as total
            from room_gallery where company_id='".$company->id."'
            and room_id='".$room->id."'";
            $total=1;
            $data_total=DB::select($sql);
            if (sizeof($data_total)>0){
                $total=$data_total[0]->total+1;
            }
            $image=AppHelper::upload_image($request,$room->slug.'-'.$total,$company->code,true);
            if ($image['error']==true){
                 return response()->json(array(
                        'error' => true,
                        'message' => $image['message']),
                        200
                        );
            }else{
                $obj = new RoomGallery;
                $id=Uuid::generate();
                $obj->id=$id;
                if ($request->get('room_id')){
                    $obj->room_id=$request->get('room_id');
                }
                $obj->company_id=$request->get('company_id');
                // $obj->title=$request->json('title');
                $obj->image=$image['image'];
                $obj->image_thumb=$image['image_thumb'];
                $obj->created_by = JWTAuth::parseToken()->toUser()->username;
                $obj->save();

                 $roomGallery=RoomGallery::find($id->__toString());


                 if (is_object($room)){
                    if ($room->image_id==null){
                        $room->image_id=$roomGallery->id;
                        $room->save();
                    }
                 }
                  DB::commit();

            }

             return response()->json(array(
                        'error' => false,
                        'data' => $roomGallery,
                        'message' => 'RoomGallery created'),
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
        $obj =RoomGallery::find($id);
        $obj->title=$request->json('title');
        $obj->updated_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();
        return response()->json(array(
                            'error' => false,
                            'id' => $id,
                            'message' => 'RoomGallery created'),
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
        $obj = RoomGallery::find($id);
         //delete imae from aws s3
            // if(Storage::disk('s3')->has(str_replace(env('S3_URL'),'',$obj->image)))
            // {
            //     Storage::disk('s3')->delete([str_replace(env('S3_URL'),'',$obj->image),
            //     str_replace(env('S3_URL'),'',$obj->image_thumb)]);
            // }


        if ($obj->delete()) {
            return response()->json(array('success' => TRUE));
        }
    }
}
