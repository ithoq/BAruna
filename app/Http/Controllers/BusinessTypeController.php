<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BusinessType;
use App\Company;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Log;
class BusinessTypeController extends Controller
{

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        return response()->json(BusinessType::get(), 200, [], JSON_NUMERIC_CHECK);
    }

    
}
