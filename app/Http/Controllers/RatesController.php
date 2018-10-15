<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rates;
use App\Company;
use App\RoomType;
use App\RatesRoom;
use App\RatesPos;
use App\RoomRatesMapping;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use Log;
use DB;
use AppHelper;
use App\Events\RateHasChanged;

class RatesController extends Controller
{

    private $module = 'RATES';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->get('size')){
                $size = $request->get('size', 10);
                $criteria_key = $request->get('criteria_key','name');
                $criteria_value = $request->get('criteria_value','');
                return response()->json(Rates::with('currency')
                                        ->where('company_id','=',$request->get('company_id'))
                                        ->where($criteria_key,"like","%".$criteria_value."%")
                                                    ->paginate($size), 200, [], JSON_NUMERIC_CHECK);
        }

        return response()->json(Rates::with('currency')->where('company_id','=',$request->get('company_id'))->get(), 200, [], JSON_NUMERIC_CHECK);
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
        $username= JWTAuth::parseToken()->toUser()->username;
        $row=Rates::where('name','=',$request->json('name'))
                       ->where('company_id','=',$company->id)->first();
        if (is_object($row)){
                return response()->json(array(
                    'error' => true,
                    'message' => 'Name already exist!'),
                    200
                    );
        }


        $obj = new Rates;
        $id=Uuid::generate();
        $obj->id=$id;
        $obj->name=$request->json('name');
        $obj->currency_id=$request->json('currency_id');
        $obj->taxes_pct=$request->json('taxes_pct');
        $obj->service_pct=$request->json('service_pct');
        $obj->start_date=$request->json('start_date');
        if ($request->json('use_end_date')==true){
            $obj->use_end_date=1;
        }else{
            $obj->use_end_date=0;
        }

        $obj->end_date=$request->json('end_date');
        if ($request->json('include_extra_bed')==true){
            $obj->include_extra_bed=1;
        }else{
            $obj->include_extra_bed=0;
        }

        if ($request->json('open_rate')==true){
            $obj->open_rate=1;
        }else{
            $obj->open_rate=0;
        }

        $obj->company_id = $company->id;
        $obj->created_by = $username;
        $obj->save();
        $obj->id=$id->__toString();

        //Rates room
        foreach ($request->json('rates_room') as $key => $value) {
            $obj_rates_room = new RatesRoom;
            $rates_room_id=Uuid::generate();
            $obj_rates_room->id=$rates_room_id;
            $obj_rates_room->rates_id=$obj->id;
            $obj_rates_room->room_type_id=$value['room_type_id'];
            $obj_rates_room->rates=$value['rates'];
            $obj_rates_room->company_id = $company->id;
            $obj_rates_room->created_by = $username;
            $obj_rates_room->save();
        }

        if ($request->json('include_product')==true){
            //Rates POS
            foreach ($request->json('rates_pos') as $key => $value) {
                $obj_rates_pos = new RatesPos;
                $rates_pos_id=Uuid::generate();
                $obj_rates_pos->id=$rates_pos_id;
                $obj_rates_pos->pax=$value['pax'];
                $obj_rates_pos->taxes_pct=$value['taxes_pct'];
                $obj_rates_pos->service_pct=$value['service_pct'];
                $obj_rates_pos->product_id=$value['product_id'];
                $obj_rates_pos->price=$value['price'];
                $obj_rates_pos->rates_id=$obj->id;
                $obj_rates_pos->company_id = $company->id;
                $obj_rates_pos->created_by = $username;
                $obj_rates_pos->save();
            }
        }
        AppHelper::makeLog($request,$company->id,$username,'success',$this->module,json_encode($obj), 'Create rates '.$obj->name,'rates',$obj->id);
        return response()->json(array(
                            'error' => false,
                            'message' => 'Rates created'),
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
        return response()->json(Rates::find($id), 200, [], JSON_NUMERIC_CHECK);
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
        $username= JWTAuth::parseToken()->toUser()->username;
        //Validate Duplicate Row
        $row=Rates::where('name','=',$request->json('name'))
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
        $obj=Rates::find($id);
        $obj->name=$request->json('name');
        $obj->currency_id=$request->json('currency_id');
        $obj->taxes_pct=$request->json('taxes_pct');
        $obj->service_pct=$request->json('service_pct');
        $obj->start_date=$request->json('start_date');
        $obj->end_date=$request->json('end_date');
        if ($request->json('include_extra_bed')==true){
            $obj->include_extra_bed=1;
        }else{
            $obj->include_extra_bed=0;
        }

        if ($request->json('use_end_date')==true){
            $obj->use_end_date=1;
        }else{
            $obj->use_end_date=0;
        }

        if ($request->json('open_rate')==true){
            $obj->open_rate=1;
        }else{
            $obj->open_rate=0;
        }
        $obj->company_id = $company->id;
        $obj->updated_by = $username;
        $obj->save();

        //Rates room
        foreach ($request->json('rates_room') as $key => $value) {
          if (isset($value['id'])){
              $obj_rates_room =RatesRoom::find($value['id']);
              $obj_rates_room->updated_by = $username;
          }else{
              $obj_rates_room =new RatesRoom;
              $rates_room_id=Uuid::generate();
              $obj_rates_room->id=$rates_room_id;
              $obj_rates_room->created_by = $username;
          }
            $obj_rates_room->rates_id=$obj->id;
            $obj_rates_room->room_type_id=$value['room_type_id'];
            $obj_rates_room->rates=$value['rates'];
            $obj_rates_room->company_id = $company->id;
            $obj_rates_room->save();

            /* trigger RateHasChanged event
            if($company->username_cm!=""){
                $RoomRatesMapping = RoomRatesMapping::where('rates_id',$obj_rates_room->rates_id)
                                                      ->first();
                if($RoomRatesMapping){
                    $start_date = $request->json('start_date');
                    $end_date   = $request->json('end_date');
                    event(new RateHasChanged($company,$username,$obj_rates_room->room_type_id,$obj_rates_room->rates_id,$obj_rates_room->rates,$start_date,$end_date,'Bulk Rate Updated'));
                }
            }
            */
        }

        if ($request->json('include_product')==true){
            //Rates POS
            foreach ($request->json('rates_pos') as $key => $value) {
              if (isset($value['id'])){
                  $obj_rates_pos =RatesPos::find($value['id']);
                  $obj_rates_pos->updated_by = $username;
              }else{
                  $obj_rates_pos = new RatesPos;
                  $rates_pos_id=Uuid::generate();
                  $obj_rates_pos->id=$rates_pos_id;
                  $obj_rates_pos->created_by = $username;
              }


                $obj_rates_pos->pax=$value['pax'];
                $obj_rates_pos->taxes_pct=$value['taxes_pct'];
                $obj_rates_pos->service_pct=$value['service_pct'];
                $obj_rates_pos->product_id=$value['product_id'];
                $obj_rates_pos->price=$value['price'];
                $obj_rates_pos->rates_id=$obj->id;
                $obj_rates_pos->company_id = $company->id;
                $obj_rates_pos->save();
            }
        }

        AppHelper::makeLog($request,$company->id,$username,'success',$this->module,json_encode($obj), 'Update rates '.$obj->name,'rates',$id);
        return response()->json(array(
                                        'error' => false,
                                        'message' => 'Rates updated'),
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
        $obj = Rates::find($id);
        $company=Company::find($obj->company_id);
        $username=JWTAuth::parseToken()->toUser()->username;
        $obj->deleted_by = $username;
        $obj->update();
        if ($obj->delete()) {
            AppHelper::makeLog($request,$company->id,$username,'success',$this->module,json_encode($obj), 'Delete rates '.$obj->name,'rates',$id);
            return response()->json(array('success' => TRUE));
        }
    }



    public function rates_detail(Request $request,$id){
           $company_id=$request->get('company_id');
            $sql="select room_type.name,
                  room_type.id as room_type_id,
                  coalesce(rates_room.rates,0) as rates,
                  rates_room.id
            from room_type
            left join rates_room on rates_room.room_type_id=room_type.id and rates_room.rates_id = '".$id."'
            and  rates_room.company_id = '".$company_id."'
            where room_type.deleted_at is null
            and rates_room.deleted_at is null
            and room_type.company_id = '".$company_id."'";

        $data = DB::select($sql);
        return response()->json($data, 200, [],JSON_NUMERIC_CHECK);


    }


    public function rates_calender(Request $request,$id){
           $company_id=$request->get('company_id');
           $company=Company::find($company_id);
           $start_date=$request->get('start_date');
           $end_date=$request->get('end_date');

           $data['dates']=DB::select( "select tmp_date.datenew as date
                                           from tmp_date
                                           where tmp_date.datenew >= '".$start_date."' && tmp_date.datenew <= '".$end_date."'
                                           order by tmp_date.datenew  asc" );
           $room_type=RoomType::where('company_id','=',$company_id)->orderBy('name','asc')->get();

           foreach ($room_type as $key => $value) {
             $value->rates = DB::select(
                 "select t_root.datenew,
                        t_root.rates_id,
                        t_root.room_type_id,
                        t_root.id  as rates_room_id,
                        coalesce(t_rates_room_detail.rates,t_root.rates) as rates

                 from (
                     select tmp_date.datenew,
                         coalesce(t_maint.rates,0) as rates,
                         t_maint.rates_id,
                         t_maint.room_type_id,
                         t_maint.id
                   from tmp_date
                   left join (
                    select tmp_date.datenew,
                          t_rates.rates_id,
                          t_rates.room_type_id,
                          t_rates.rates,
                          t_rates.id
                   from tmp_date ,
                   (
                    select rates_room.*,
                          case when rates.use_end_date=1 then
                              rates.end_date
                          else
                              DATE_ADD('".$company->app_date."', INTERVAL 3 YEAR)
                          end as end_date,
                           rates.start_date
                   from rates
                   join rates_room on rates.id=rates_room.rates_id

                   ) t_rates
                   where tmp_date.datenew>=t_rates.start_date and  tmp_date.datenew <= t_rates.end_date
                   and t_rates.rates_id='".$id."'
                   and t_rates.room_type_id='".$value->id."'

                   ) t_maint on t_maint.datenew=tmp_date.datenew
                   where tmp_date.datenew>='".$start_date."' and tmp_date.datenew<='".$end_date."'
                   ) t_root
                   left join (
                        select rates_room_detail.*
                        from rates_room_detail
                        where rates_room_detail.dates>='".$start_date."' and rates_room_detail.dates<='".$end_date."'
                    ) t_rates_room_detail on t_rates_room_detail.rates_room_id = t_root.id and t_root.datenew=t_rates_room_detail.dates
                   order by  t_root.datenew asc ") ;
           }


           $data['room_rates']=$room_type;
          return response()->json($data , 200, [], JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);


    }


    private function rates($start_date,$end_date,$room_type_id,$rates_id,$company_id){
            $sql=" select t_root.dates,
                          coalesce(t_rates_room_detail.rates,t_root.rates) as rates,
                          t_root.pos_price
                   from (
                          select tmp_date.datenew as dates,
                                  t_rates.rates as rates,
                                  t_rates.price as pos_price,
                                  t_rates.id
                          from tmp_date,(
                              select rates_room.* , coalesce(t_pos.price,0) as price
                              from rates_room
                              left join (
                                  select rates_pos.rates_id,
                                          sum(rates_pos.price) as price
                                    from rates_pos
                                     where rates_pos.deleted_at is null
                                     group by rates_pos.rates_id
                                ) t_pos on t_pos.rates_id=rates_room.rates_id
                              where rates_room.rates_id='".$rates_id."'
                              and rates_room.company_id='".$company_id."'
                              and rates_room.room_type_id='".$room_type_id."'
                          ) t_rates
                          where tmp_date.datenew >= '".$start_date."' and tmp_date.datenew<'".$end_date."'
                    ) t_root
                    left join (
                         select rates_room_detail.*
                         from rates_room_detail
                         where rates_room_detail.dates>='".$start_date."'
                               and rates_room_detail.dates<='".$end_date."'
                     ) t_rates_room_detail on t_rates_room_detail.rates_room_id = t_root.id
                      and t_root.dates=t_rates_room_detail.dates
                        order by t_root.dates asc";
             return  DB::select($sql);
    }


     public function rate_by_room_type(Request $request){
        $room_type_id=$request->get('room_type_id');
        $start_date=$request->get('start_date');
        $end_date=$request->get('end_date');
        $pax=$request->get('pax');
        $company_id=JWTAuth::parseToken()->toUser()->company_id;
        $company=Company::find($company_id);

        if ($request->get('company_id')){
            $company_id=$request->get('company_id');
        }
        $data=[];



        $sql="select t_rates.*,
                     currency.code as currency_code,
                     currency.bookeepingrate
            from (
                select rates.id,
                       rates.name,
                       rates.currency_id,
                       rates.start_date,
                       case when use_end_date=1 then
                           rates.end_date
                       else
                           DATE_ADD('".$company->app_date."', INTERVAL 3 YEAR)
                       end as end_date,
                      rates.taxes_pct,
                      rates.company_id,
                      rates.service_pct,
                      rates.open_rate,
                      rates.include_extra_bed
                from rates
                where company_id='".$company_id."'
                and rates.deleted_at is null
              )t_rates
            left join currency on currency.id=t_rates.currency_id
            where t_rates.company_id='".$company_id."'
            and t_rates.id in (select rates_id from rates_room where deleted_at is null and rates_room.room_type_id='".$room_type_id."')
            and (t_rates.start_date <= '".$start_date."' and  '".$start_date."' <= t_rates.end_date)
            and (t_rates.start_date <= '".$end_date."' and  '".$end_date."' <= t_rates.end_date )
            ";

        $rates = DB::select($sql);
        foreach ($rates as $key => $value) {
                $value->rates = $this->rates($start_date,$end_date,$room_type_id,$value->id,$company_id);
               $data[$key]=$value;
        }
        return response()->json($data, 200, [],JSON_NUMERIC_CHECK);

    }






}
