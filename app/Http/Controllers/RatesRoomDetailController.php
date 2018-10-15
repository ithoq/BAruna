<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RatesRoomDetail;
use App\RatesRoom;
use App\Company;
use App\Rates;
use App\RoomRatesMapping;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Log;
use DB;
use AppHelper;
use Carbon\Carbon;
use App\Events\RateHasChanged;

class RatesRoomDetailController extends Controller
{

    private $module = 'RATESROOMDETAIL';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

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


    public function rates_bulk_update(Request $request){
        $start_date=$request->get('start_date');
        $end_date=$request->get('end_date');
        $rates_id=$request->get('rates_id');
        $rates=$request->get('rates');
        $room_type_id=$request->get('room_type_id');
        $start=Carbon::parse($start_date);
        $end=Carbon::parse($end_date);
        $username=JWTAuth::parseToken()->toUser()->username;
        while ($start->lte($end)) {

            $dates = $start->copy()->format('Y-m-d');
            $this->store_room_detail($rates_id,$dates,$room_type_id,$rates,$username);
            $start->addDay();
        }

        $rates_check   = Rates::find($rates_id);
        $company = Company::where('id',$rates_check->company_id)->first();

        //trigger RateHasChanged event
        if($company->username_cm!=""){
            $RoomRatesMapping = RoomRatesMapping::where('rates_id',$rates_id)
                                                  ->first();
            if($RoomRatesMapping){
                event(new RateHasChanged($company,$username,$room_type_id,$rates_id,$rates,$start_date,$end_date,'Bulk Rate Updated'));
            }
        }

    }


    private function store_room_detail($rates_id,$dates,$room_type_id,$rates,$username){
          $rates_room=RatesRoom::where('room_type_id','=',$room_type_id)
                                ->where('rates_id','=',$rates_id)->first();
          if (is_object($rates_room)){
              $rates_room_detail=RatesRoomDetail::where('rates_room_id','=',$rates_room->id)
                                                ->where('dates','=',$dates)->first();
              if (is_object($rates_room_detail)){
                  $rates_room_detail->rates_room_id=$rates_room->id;
                  $rates_room_detail->dates=$dates;
                  $rates_room_detail->rates=$rates;
                  $rates_room_detail->updated_by = $username;
                  $rates_room_detail->save();
              }else{
                  $rates_room_detail = new RatesRoomDetail;
                  $id=Uuid::generate();
                  $rates_room_detail->id=$id;
                  $rates_room_detail->rates_room_id=$rates_room->id;
                  $rates_room_detail->dates=$dates;
                  $rates_room_detail->rates=$rates;
                  $rates_room_detail->created_by = $username;
                  $rates_room_detail->save();

              }
          }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      DB::beginTransaction();

      try {


        $company=Company::find($request->json('company_id'));
        $username=JWTAuth::parseToken()->toUser()->username;
        $rates_id=$request->json('rates_id');
        $room_type_id=$request->json('room_type_id');
        $dates=$request->json('datenew');
        $rates=$request->json('rates');

        if ($rates_id && $room_type_id ){
              $this->store_room_detail($rates_id,$dates,$room_type_id,$rates,$username);
        }



        AppHelper::makeLog($request,$company->id,$username,'success',$this->module,'', 'update rates per dates ','rates_id',$rates_id);
        //trigger RateHasChanged event
        if($company->username_cm!=""){
          $RoomRatesMapping = RoomRatesMapping::where('rates_id',$rates_id)
                                                ->first();
          if($RoomRatesMapping){
              $start_date = $dates;
              $end_date   = $dates;
              event(new RateHasChanged($company,$username,$room_type_id,$rates_id,$rates,$start_date,$end_date,'Bulk Rate Updated'));
          }
        }



                            DB::commit();
                               // all good

                               return response()->json(array(
                                                   'error' => false,
                                                   'message' => 'Rates updated'),
                                                   200
                                                   );

                           } catch (\Exception $e) {
                               DB::rollback();
                               return response()->json(array(
                                   'error' => true,
                                   'message' => $e->getMessage()),
                                   200
                                   );
                           }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(RatesRoomDetail::find($id), 200, [], JSON_NUMERIC_CHECK);
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

    }



}
