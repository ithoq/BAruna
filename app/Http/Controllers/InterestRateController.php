<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InterestRate;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Log;

class InterestRateController extends Controller
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
                return response()->json(InterestRate::where('company_id','=',$company_id)
                                        ->where($criteria_key,"like","%".$criteria_value."%")
                                                    ->paginate($size), 200, [], JSON_NUMERIC_CHECK);
        }

        return response()->json(InterestRate::where('company_id','=',$company_id)->get(), 200, [], JSON_NUMERIC_CHECK);
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
        $row=InterestRate::where('name','=',$request->json('name'))
                       ->first();
        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Name already exist!'),
                    200
                    );
        }


        $obj = new InterestRate;
        $obj->id=Uuid::generate();
        $obj->name=$request->json('name');
        $obj->description=$request->json('description');
        $obj->company_id = $company->id;
        $obj->created_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();
        $this->makeLog($request,$company,$obj->created_by,'Interest Rate was created');
        return response()->json(array(
                            'error' => false,
                            'message' => 'Interest Rate created'),
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
        return response()->json(InterestRate::find($id), 200, [], JSON_NUMERIC_CHECK);
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
        $company=Company::find($request->json('company_id'));
        //Validate Duplicate Row
        $row=InterestRate::where('name','=',$request->json('name'))
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

        $obj=InterestRate::find($id);
        $obj->name=$request->json('name');
        $obj->description=$request->json('description');
        $obj->company_id = $company->id;
        $obj->updated_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();
        $this->makeLog($request,$company,$obj->updated_by,'Interest Rate was Updated');
        return response()->json(array(
                                        'error' => false,
                                        'message' => 'Interest Rate updated'),
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
        $obj = InterestRate::find($id);
        $company=Company::find($obj->company_id);
        $obj->deleted_by = JWTAuth::parseToken()->toUser()->username;
        $obj->update();
        if ($obj->delete()) {
            $this->makeLog($request,$company,JWTAuth::parseToken()->toUser()->username,'Post Category was deleted');
            return response()->json(array('success' => TRUE));
        }
    }

    private function makeLog($request,$company,$user,$action){
        Log::info("=> {\"IP\":\"".$request->ip()."\",\"AGENT\":\"".$request->server('HTTP_USER_AGENT')."\",\"COMPANY\":\"".$company->name."\",\"user\":\"".$user."\",\"ACTION\":\"".$action."\"}");
    }
}
