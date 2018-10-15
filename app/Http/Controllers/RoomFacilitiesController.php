<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RoomGallery;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Image;
use DB;


class RoomFacilitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->get('room_id')){

            $sql="select facilities.*,
                       case when room_facilities.id is null then
                            false
                        else
                           true
                        end as is_checked
                from facilities
                left join room_facilities on facilities.id=room_facilities.facilities_id and room_facilities.room_id='".$request->get('room_id')."'
                and  room_facilities.company_id='".$request->get('company_id')."'
                where facilities.deleted_at is null
                and facilities.type='ROOM'
                and  facilities.company_id='".$request->get('company_id')."'
                order by facilities.name asc";
            $data= DB::select($sql);
           return response()->json($data,200, [], JSON_NUMERIC_CHECK);
        }

         $sql="select DISTINCT facilities.id AS id_fac,
                       facilities.*,
                       false as is_checked
                from facilities
                left join room_facilities on facilities.id=room_facilities.facilities_id and  room_facilities.company_id='".$request->get('company_id')."'
                where  facilities.deleted_at is null
                and facilities.type='ROOM'
                and  facilities.company_id='".$request->get('company_id')."'
                order by facilities.name asc";
            $data= DB::select($sql);
           return response()->json($data,200, [], JSON_NUMERIC_CHECK);



    }


}
