<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use DB;
use JWTAuth;
use Log;
use Hash;
  
class UserController extends Controller
{
    

    /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {
    $size = $request->get('size', 10);
    $criteria_key = $request->get('criteria_key');
    $criteria_value = $request->get('criteria_value');
    $company_id = $request->get('company_id');

    return response()->json(User::with('role')->where('company_id','=',$company_id)->where($criteria_key,"like","%".$criteria_value."%")->paginate($size), 200, [], JSON_NUMERIC_CHECK);
  }

    public function group_user(Request $request){
        $sql="select users.id,
                     users.name,
                     users.is_user_group,
                     users.is_user_group as is_checked,
                     company.name as company_name
              from  users
              join company on company.id=users.company_id
              where users.company_id in 
              (select company_id 
              from group_company_detail 
              where group_company_id='".$request->get('group_company_id')."'
              )
                        ";                   
       $data=DB::select($sql);
       return  response()->json($data , 200, [], JSON_NUMERIC_CHECK) ;
      
    }


    public function store_group_user(Request $request){
        $user=$request->json('user');
        if ($user){
                foreach ($user as $key => $value) {
                    $user=User::find($value['id']);
                    $user->is_user_group=$value['is_checked'];
                    $user->save();
                }
        }

        return response()->json(array(
        'error' => false,
        'message' => 'Store User Group'
        ),
        200
        );
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
        $row=User::where('username','=',$request->json('username'))
                       ->where('company_id','=',$company->id)->first();
        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Username already exist!'),
                    200
                    );
        }
    

        $obj = new User;
        $obj->id=Uuid::generate();
        $obj->name=$request->json('name');
        $obj->role_id=$request->json('role_id');
        $obj->username=$request->json('username');
        $obj->password=Hash::make($request->json('password'));
        $obj->company_id = $company->id;
        $obj->created_by = JWTAuth::parseToken()->toUser()->username;
        $obj->save();   
        $this->makeLog($request,$company,$obj->created_by,'User was created');
        return response()->json(array(
                            'error' => false,
                            'message' => 'Location created'),
                            200
                            );
    }


    public function update(Request $request,$id){

        $obj=User::find($id);
        $company=Company::find($obj->company_id);
        $username=JWTAuth::parseToken()->toUser()->username;
        $obj->name=$request->json('name');
        $obj->role_id=$request->json('role_id');
        $obj->username=$request->json('username');
        $obj->updated_by = $username;
        $obj->company_id = $company->id;
        //$obj->password=Hash::make($request->json('password'));
        $this->makeLog($request,$company,$username,'user was updated');
        $obj->save(); 


        return response()->json(array(
        'error' => false,
        'message' => 'user updated'
        ),
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
        $obj = User::find($id);
        $company=Company::find($obj->company_id);
        // $obj->deleted_by = JWTAuth::parseToken()->toUser()->username;
        // $obj->update();   
        if ($obj->delete()) {
            $this->makeLog($request,$company,JWTAuth::parseToken()->toUser()->username,'User was deleted');
            return response()->json(array('success' => TRUE));
        }
    }

    private function makeLog($request,$company,$user,$action){
        Log::info("=> {\"IP\":\"".$request->ip()."\",\"AGENT\":\"".$request->server('HTTP_USER_AGENT')."\",\"COMPANY\":\"".$company->name."\",\"user\":\"".$user."\",\"ACTION\":\"".$action."\"}");
    }

    
}
