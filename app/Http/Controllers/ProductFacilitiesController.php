<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductGallery;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Image;
use DB;


class ProductFacilitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->get('product_id')){

            $sql="select facilities.*, 
                       case when product_facilities.id is null then
                            false 
                        else
                           true 
                        end as is_checked
                from facilities 
                left join product_facilities on facilities.id=product_facilities.facilities_id and product_facilities.product_id='".$request->get('product_id')."' 
                and  product_facilities.company_id='".$request->get('company_id')."'
                where facilities.deleted_at is null
                and  facilities.company_id='".$request->get('company_id')."'
                order by facilities.name asc";
            $data= DB::select($sql);
           return response()->json($data,200, [], JSON_NUMERIC_CHECK);
        }

         $sql="select DISTINCT facilities.id AS id_fac,
                       facilities.*, 
                       false as is_checked
                from facilities 
                left join product_facilities on facilities.id=product_facilities.facilities_id and  product_facilities.company_id='".$request->get('company_id')."'
                where  facilities.deleted_at is null
                and  facilities.company_id='".$request->get('company_id')."'
                order by facilities.name asc";
            $data= DB::select($sql);
           return response()->json($data,200, [], JSON_NUMERIC_CHECK);


        
    }

    
}
