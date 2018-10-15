<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GroupCompanyDetail;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use DB;
use JWTAuth;
use Log;
class GroupCompanyDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->get('group_company_id')){

          $criteria_key = $request->get('criteria_key','name');
          $criteria_value = $request->get('criteria_value','');

              $sql="
                    select company.*
                    from group_company_detail
                    join company on company.id=group_company_detail.company_id
                    where group_company_id='".$request->get('group_company_id')."'
                    and ".$criteria_key." like '%".$criteria_value."%'";
                $data=DB::select($sql);

              return response()->json($data, 200, [], JSON_NUMERIC_CHECK);
        }
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

        $obj = new GroupCompanyDetail;
        $group_company_detail_id=Uuid::generate();
        $obj->id=$group_company_detail_id;
        $obj->group_company_id=$request->json('group_company_id');
        $obj->company_id=$request->json('company_id');
        $obj->created_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();

        return response()->json(array(
        'error' => false,
        'id' =>$group_company_detail_id->__toString(),
        'message' => 'Category created'),
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $obj=GroupCompanyDetail::find($id);
        $obj->group_company_id=$request->json('group_company_id');
        $obj->company_id=$request->json('company_id');
        $obj->updated_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();

        return response()->json(array(
        'error' => false,
        'message' => 'Category updated'),
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
       return response()->json(GroupCompanyDetail::find($id), 200, [], JSON_NUMERIC_CHECK);
    }


     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        $obj=GroupCompanyDetail::find($id);
        if ($obj){
           $obj->delete();
        }


        return response()->json(array(
        'error' => false,
        'message' => 'GroupCompanyDetail deleted'),
        200
        );

    }


    private function makeLog($request,$company,$user,$action){
       Log::info("=> {\"IP\":\"".$request->ip()."\",\"AGENT\":\"".$request->server('HTTP_USER_AGENT')."\",\"COMPANY\":\"".$company->name."\",\"user\":\"".$user."\",\"ACTION\":\"".$action."\"}");
    }


}
