<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PostTags;
use App\Tags;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Log;
class PostTagsController extends Controller
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

        if ($request->get('post_id')){
             return response()->json(PostTags::with('tags')->where('company_id','=',$company_id)
            ->where('post_id','=',$request->get('post_id'))->get(), 200, [], JSON_NUMERIC_CHECK);
        }
        return response()->json(PostTags::with('tags')->where('company_id','=',$company_id)->get(), 200, [], JSON_NUMERIC_CHECK);
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
                    $tags->company_id = $company->id;
                    $tags->created_by = JWTAuth::parseToken()->toUser()->username;
                    $tags->save();
                    $row=Tags::find($id->__toString());
            }

            $obj =PostTags::where('company_id','=',$company->id)
                                ->where('post_id','=',$request->json('post_id'))
                                ->where('tags_id','=',$row->id)->first();

            if (!is_object($obj)){
                $obj = new PostTags;
                $id=Uuid::generate();
                $obj->id=$id;
                $obj->post_id=$request->json('post_id');
                $obj->tags_id=$row->id;
                $obj->company_id = $company->id;
                $obj->created_by = JWTAuth::parseToken()->toUser()->username;
                $obj->save();
                $obj->id=$id->__toString();


                  $data[] = PostTags::with('tags')->find($id->__toString());
            }


        }



        return response()->json(array(
                            'error' => false,
                            'data' => $data,
                            'message' => 'Post tags created'),
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
        return response()->json(PostTags::find($id), 200, [], JSON_NUMERIC_CHECK);
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
        $obj = PostTags::find($id);
        if ($obj->delete()) {
            return response()->json(array('success' => TRUE));
        }
    }




}
