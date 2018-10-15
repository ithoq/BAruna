<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RoomTags;
use App\Tags;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Log;
class RoomTagsController extends Controller
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
             return response()->json(RoomTags::with('tags')->where('company_id','=',$company_id)
            ->where('room_id','=',$request->get('room_id'))->get(), 200, [], JSON_NUMERIC_CHECK);
        }
        return response()->json(RoomTags::with('tags')->where('company_id','=',$company_id)->get(), 200, [], JSON_NUMERIC_CHECK);
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

        $tag=$request->json('name');
        $tags=explode(",", $tag);
        $data=[];
        foreach ($tags as $key => $value) {
            $name=trim($value);
             $row=Tags::where('name','=',$name)
                           ->where('company_id','=',$company->id)->first();
            if (!is_object($row)){
                    $tags = new Tags;
                    $id=Uuid::generate();
                    $tags->id=$id;
                    $tags->name=$name;
                    // $tags->title=$name;
                    $tags->company_id = $company->id;
                    $tags->created_by = JWTAuth::parseToken()->toUser()->username;
                    $tags->save();
                    $row=Tags::find($id->__toString());
            }

            $obj =RoomTags::where('company_id','=',$company->id)
                                ->where('room_id','=',$request->json('room_id'))
                                ->where('tags_id','=',$row->id)->first();

            if (!is_object($obj)){
                $obj = new RoomTags;
                $id=Uuid::generate();
                $obj->id=$id;
                $obj->room_id=$request->json('room_id');
                $obj->tags_id=$row->id;
                $obj->company_id = $company->id;
                $obj->created_by = JWTAuth::parseToken()->toUser()->username;
                $obj->save();
                $obj->id=$id->__toString();


                  $data[] = RoomTags::with('tags')->find($id->__toString());
            }


        }



        return response()->json(array(
                            'error' => false,
                            'data' => $data,
                            'message' => 'Room tags created'),
                            200
                            );
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $obj = RoomTags::find($id);
        if ($obj->delete()) {
            return response()->json(array('success' => TRUE));
        }
    }




}
