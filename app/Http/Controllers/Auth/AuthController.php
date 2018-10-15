<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use DB;
use Log;
use Auth;
use Hash;

class AuthController extends Controller
{




    public function authenticate(Request $request) {

         $company = Company::where('code','=',$request->json('company_code'))
                      ->where('active','=',1)
                      ->where('type','=','SINGLE')
                      ->first();
         if (!$company){
             return response()->json(['message' => 'Company not register!'], 401);
         }

        $credentials = array('username' => $request->json('username'), 'password' => $request->json('password') ,'company_id' => $company->id );
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                $this->makeLog($request,$company,'NO_USER','Username or Password not valid');
                return response()->json(['message' => 'Username or Password not valid'], 401);
            }
        } catch (JWTException $e) {
            $this->makeLog($request,$company,'NO_USER','could_not_create_token');
            return response()->json(['message' => 'could_not_create_token'], 500);
        }
        $this->makeLog($request,$company,$request->json('username'),'Login');
        return response()->json(compact('token','company'));
    }



    public function authenticate_group(Request $request) {

         $company = Company::where('code','=',$request->json('company_code'))
                      ->where('active','=',1)
                      ->where('type','=','GROUP')
                      ->first();
         if (!$company){
             return response()->json(['message' => 'Company not register!'], 401);
         }

        $credentials = array('username' => $request->json('username'), 'password' => $request->json('password') ,'company_id' => $company->id );
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                $this->makeLog($request,$company,'NO_USER','Username or Password not valid');
                return response()->json(['message' => 'Username or Password not valid'], 401);
            }
        } catch (JWTException $e) {
            $this->makeLog($request,$company,'NO_USER','could_not_create_token');
            return response()->json(['message' => 'could_not_create_token'], 500);
        }
        $this->makeLog($request,$company,$request->json('username'),'Login');
        return response()->json(compact('token','company'));
    }



    public function authenticate_admin(Request $request)
  {
        $credentials = array('username' => $request->json('username'), 'password' => $request->json('password') ,'company_id' => 'ADMIN' );
           try {
            if (!$token = JWTAuth::attempt($credentials)) {
                // $this->makeLog($request,$company,'NO_USER','Username or Password not valid');
                return response()->json(['message' => 'Username or Password not valid'], 401);
            }
        } catch (JWTException $e) {
            // $this->makeLog($request,$company,'NO_USER','could_not_create_token');
            return response()->json(['message' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));



  }


     public function menu_permission(Request $request){


         try {
            $user= JWTAuth::parseToken()->toUser();

            if (!$user){
                 return response()->json(['message' => 'User not login!'], 401);
            }else{
              $role_list=DB::select("select menu.*
                                         from  menu
                                         left join role_list on  role_list.menu_id=menu.id
                                         where role_list.role_id='".$user->role_id."' order by menu.id asc") ;
              return response()->json($role_list, 200, [], JSON_NUMERIC_CHECK);
            }


        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['message' => 'could_not_create_token'], 500);
        }





    }


     public function passwd(Request $request){
        $old_password = $request->get('currentPasswd');
        $new_password = $request->get('newPasswd');
        $re_new_password = $request->get('renewPasswd');
        $user=JWTAuth::parseToken()->toUser();
        $is_password=Hash::check($old_password, $user->password);
        if ($is_password && $new_password==$re_new_password){
            $user->password=Hash::make($new_password);
            $user->updated_by=$user->username;
            $user->save();
            return response()->json(['error' => false,
                                      'message' => 'Change Password Success!'
                                    ]);

        }else{

            return response()->json(array('message' => 'Old Password is wrong!'), 404);

        }



    }


    public function user(){
        $user= JWTAuth::parseToken()->toUser();
        return response()->json(User::with('role')->find($user->id), 200, [], JSON_NUMERIC_CHECK);

    }


    private function makeLog($request,$company,$user,$action){
        Log::info("=> {\"IP\":\"".$request->ip()."\",\"AGENT\":\"".$request->server('HTTP_USER_AGENT')."\",\"COMPANY\":\"".$company->name."\",\"user\":\"".$user."\",\"ACTION\":\"".$action."\"}");
    }
}
