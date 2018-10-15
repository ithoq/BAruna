<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Log;
use DB;

class MenuController extends Controller
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

       $size = $request->get('size', 10);
        $criteria_key = $request->get('criteria_key');
        $criteria_value = $request->get('criteria_value');
 
      

        if ($request->get('role_id')){
            return response()->json(DB::select( DB::raw("select menu.* ,
                                       coalesce(role_list.id,0) as menu_id,
                                        case when coalesce(role_list.id,'0') = '0' then
                                            0
                                        else  
                                            1
                                        end as is_checked
                                from  menu
                                left join role_list on  role_list.menu_id=menu.id and role_list.role_id='".$request->get('role_id') ."' 
                                where menu.deleted_at is null
                                order by menu.name asc") ), 200, [], JSON_NUMERIC_CHECK);
        }
       
         return  response()->json(Menu::where($criteria_key,"like","%".$criteria_value."%")->paginate($size) , 200, [], JSON_NUMERIC_CHECK) ; 
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
        $row=Menu::where('code','=',$request->json('code'))
                       ->first();
        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Code already exist!'),
                    200
                    );
        }


        $obj = new Menu;
        $obj->id=Uuid::generate();
        $obj->name=$request->json('name');
        $obj->code=$request->json('code');
        $obj->created_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();   
        
        return response()->json(array(
                            'error' => false,
                            'message' => 'Menu created'),
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
        return response()->json(Menu::find($id), 200, [], JSON_NUMERIC_CHECK);
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
        
        //Validate Duplicate Row
        $row=Menu::where('code','=',$request->json('code'))
                         ->where('id','<>',$id)
                         ->first();
        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Code already exist!'),
                    200
                    );
        }
        $obj=Menu::find($id);
        $obj->name=$request->json('name');
        $obj->code=$request->json('code');
        $obj->updated_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();   
        
        return response()->json(array(
                                        'error' => false,
                                        'message' => 'Menu updated'),
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
        $obj = Menu::find($id);
       
       $obj->deleted_by = JWTAuth::parseToken()->toUser()->username;
        $obj->update();   
        if ($obj->delete()) {
           
            return response()->json(array('success' => TRUE));
        }
    }


    private function makeLog($request,$company,$user,$action){
        Log::info("=> {\"IP\":\"".$request->ip()."\",\"AGENT\":\"".$request->server('HTTP_USER_AGENT')."\",\"COMPANY\":\"".$company->name."\",\"user\":\"".$user."\",\"ACTION\":\"".$action."\"}");
    }
}
