<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Menu;
use App\Company;
use App\RoleList;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Log;
class RoleController extends Controller
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
                return response()->json(Role::with('role_list')->company($company_id)
                                        ->where($criteria_key,"like","%".$criteria_value."%")
                                                    ->paginate($size), 200, [], JSON_NUMERIC_CHECK);
        }


       return  response()->json( Role::with('company')->company($company_id)->get() , 200, [], JSON_NUMERIC_CHECK) ;   
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
        $row=Role::where('name','=',$request->json('name'))
                       ->where('company_id','=',$company->id)->first();
        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Name already exist!'),
                    200
                    );
        }


        $obj = new Role;
        $obj->id=Uuid::generate();
        $obj->name=$request->json('name');
        $obj->company_id = $company->id;
        $obj->created_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();   
        $this->makeLog($request,$company,$obj->created_by,'Role was created');
        return response()->json(array(
                            'error' => false,
                            'message' => 'Role created'),
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
        return response()->json(Role::with('role_list','role_list.menu')->find($id), 200, [], JSON_NUMERIC_CHECK);
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
        $row=Role::where('name','=',$request->json('name'))
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
        $obj=Role::find($id);
        $obj->name=$request->json('name');
        $obj->company_id = $company->id;
        $obj->updated_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();   
        $this->makeLog($request,$company,$obj->updated_by,'Role was Updated');
        return response()->json(array(
                                        'error' => false,
                                        'message' => 'Role updated'),
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
        $obj = Role::find($id);
        $company=Company::find($obj->company_id);
      //  $obj->deleted_by = JWTAuth::parseToken()->toUser()->username;
        $obj->update();   
        if ($obj->delete()) {
            $this->makeLog($request,$company,JWTAuth::parseToken()->toUser()->username,'Role was deleted');
            return response()->json(array('success' => TRUE));
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store_role_list(Request $request)
    {
        $menu=$request->json('menu');
        $role_id=$request->json('role_id');
        
        $role_list_old =  RoleList::where('role_id','=', $role_id)->get();
            foreach ($role_list_old as $role_list_delete) {
                $role_list_delete->deleted_by = JWTAuth::parseToken()->toUser()->username;
                $role_list_delete->delete();
            }

        if ($menu){
                foreach ($menu as $key => $value) {
                    if($value['is_checked']==1 || $value['is_checked']==true){
                        $role_list =  new RoleList;
                        $role_list->id=Uuid::generate();
                        $role_list->role_id=$role_id;
                        $role_list->menu_id=$value['id'];
                        $role_list->created_by = JWTAuth::parseToken()->toUser()->username;
                        $role_list->save();
                    }
                    
                }
        }

        return response()->json(array(
        'error' => false,
        'message' => 'Menu',
        'role_id'=>$role_id
        ),
        200
        );
        
    }



    private function makeLog($request,$company,$user,$action){
        Log::info("=> {\"IP\":\"".$request->ip()."\",\"AGENT\":\"".$request->server('HTTP_USER_AGENT')."\",\"COMPANY\":\"".$company->name."\",\"user\":\"".$user."\",\"ACTION\":\"".$action."\"}");
    }
}
