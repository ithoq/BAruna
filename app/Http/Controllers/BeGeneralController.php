<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Slider;
use App\Setting;
use App\Theme;
use App\Tags;
use App\Room;
use App\RoomAvailable;
use App\RatesRoom;
use App\RoomGallery;
use App\RoomTags;
use App\Category;
use App\GalleryCategory;
use App\ProductTags;
use App\Gallery;
use App\ProductGallery;
use App\Product;
use App\Booking;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use JWTAuth;
use DB;
use Session;
use App\Post;
use App\PostCategory;
use App\PostTags;
use App\Custom\CustomPaginationLinks;
use Validator;
use GuzzleHttp\Client;
use Mail;
use Illuminate\Mail\Message;

use Carbon\Carbon;
use Cart;
use App\Veritrans\Veritrans;

class BeGeneralController extends Controller
{



  public static $rules_contact = array(
    'name'=>'required',
    'message'=>'required',
    'subject'=>'required',
    'email'=>'required|email',
  );

  private $reviews_limit=5;

  /**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
		Veritrans::$serverKey = 'SB-Mid-server-odjTvrowRbrTyk-_dv5JvqYR';

		//set Veritrans::$isProduction  value to true for production mode
		Veritrans::$isProduction = false;
	}

  public function todolist()
  {
    # code...
    $list = Category::limit(10)->orderBy('created_at','desc')->get();
    return response()->json($list, 200, []);
  }

  public function todolistsave(Request $request)
  {
    # code...
    $name = $request->json('item');
    $list = new Category();
    $list->name = $name['name'];
    $list->save();

    return response()->json($list, 200, []);
  }

  public function todolistdelete(Request $request)
  {
    # code...
    $name = $request->json('item');
    dd($name);
    $list = Category::where('name',$name['name']);
    $list->delete();

    return response()->json($list, 200, []);
  }

  private function getData(Request $request){
    if($request->get('company')!=NULL){
        $company_code = $request->get('company');
        $company=Company::where('code','=',$company_code)->first();
    }
    else if ($request->get('order_id')!=NULL){
      $company_code = Booking::where('rf_number','=',$request->get('order_id'))->first();
      $company=Company::where('id','=',$company_code->company_id)->first();
    }
    $data['company_profile'] = $company;
    $social_media = Setting::where('company_id','=',$data['company_profile']->id)
    ->where('name','=','social_media')->first();
    $social_media_decode=json_decode( $social_media)->value;
    $data['social_media']=$social_media_decode;
    $setting_menu_header = Setting::where('company_id','=',$company->id)
    ->where('name','=','menu_header')
    ->first();
    $setting_menu_footer = Setting::where('company_id','=',$company->id)
    ->where('name','=','menu_footer')
    ->first();

    $data['style_config'] = json_decode($company->theme_setting);



    $data['menu_header'] = [];
    $data['menu_footer'] = [];
    $theme=Theme::find($company->theme_id);
    $menu_config  = json_decode($theme->setting);
    $menu_header_config=$menu_config->menu_header_config;
    if (is_object($setting_menu_header)){

      $menu=$menu_header_config->container_before;
      foreach (json_decode( $setting_menu_header)->value as $key => $value) {
        if (is_object($value)){
          if ($value->type=='CUSTOM_LINK'){
            $label = str_replace("HREF","href='".$value->data->url."'",$menu_header_config->link_before );
            $label = str_replace("LABEL",$value->label,$label );
            $menu=$menu
            .$menu_header_config->before
            .$label
            .$menu_header_config->link_after
            .$menu_header_config->after;
          }else  if ($value->type=='BLOG'){
            $label = str_replace("HREF","href='".$company->base_url.'blog/category/all'."'",$menu_header_config->link_before );
            $label = str_replace("LABEL",$value->label,$label );
            $menu=$menu
            .$menu_header_config->before
            .$label
            .$menu_header_config->link_after
            .$menu_header_config->after;
          } else if ($value->type=='CATEGORIES'){
            $label = str_replace("HREF","href='".$company->base_url.'category/'.$value->slug."'",$menu_header_config->link_before );
            $label = str_replace("LABEL",$value->label,$label );
            $menu=$menu
            .$menu_header_config->before
            .$label
            .$menu_header_config->link_after
            .$menu_header_config->after;
          } else if ($value->type=='GALLERY'){
            $label = str_replace("HREF","href='".$company->base_url.'gallery/'.$value->slug."'",$menu_header_config->link_before );
            $label = str_replace("LABEL",$value->label,$label );
            $menu=$menu
            .$menu_header_config->before
            .$label
            .$menu_header_config->link_after
            .$menu_header_config->after;

          }else if ($value->type=='PAGES'){
            $label = str_replace("HREF","href='".$company->base_url.'page/'.$value->slug."'",$menu_header_config->link_before );
            $label = str_replace("LABEL",$value->label,$label );
            $menu=$menu
            .$menu_header_config->before
            .$label
            .$menu_header_config->link_after
            .$menu_header_config->after;

          }else if ($value->type=='MODULE'){

            $label = str_replace("HREF","href='".$company->base_url.$value->data->module,$menu_header_config->link_before );
            $label = str_replace("LABEL",$value->label,$label );
            $menu=$menu
            .$menu_header_config->before
            .$label
            .$menu_header_config->link_after
            .$menu_header_config->after;




          }else if ($value->type=='CONTACT'){
            $label = str_replace("HREF","href='".$company->base_url."contact'",$menu_header_config->link_before );
            $label = str_replace("LABEL",$value->label,$label );
            $menu=$menu
            .$menu_header_config->before
            .$label
            .$menu_header_config->link_after
            .$menu_header_config->after;

          }  else {
            $label = str_replace("HREF","href='".$company->base_url."'",$menu_header_config->link_before );
            $label = str_replace("LABEL",$value->label,$label );

            $menu=$menu
            .$menu_header_config->before
            .$label
            .$menu_header_config->link_after
            .$menu_header_config->after;

          }
        }
      }
      $menu=$menu.$menu_header_config->container_after;
      $data['menu_header'] =$menu;

    }



    $menu_footer_config=$menu_config->menu_footer_config;

    if (is_object($setting_menu_footer)){

      $menu=$menu_footer_config->container_before;
      foreach (json_decode( $setting_menu_footer)->value as $key => $value) {
        if (is_object($value)){
          if ($value->type=='CUSTOM_LINK'){
            $label = str_replace("HREF","href='".$value->data->url."'",$menu_footer_config->link_before );
            $label = str_replace("LABEL",$value->label,$label );
            $menu=$menu
            .$menu_footer_config->before
            .$label
            .$menu_footer_config->link_after
            .$menu_footer_config->after;
          }else  if ($value->type=='BLOG'){
            $label = str_replace("HREF","href='".$company->base_url.'blog/category/all'."'",$menu_footer_config->link_before );
            $label = str_replace("LABEL",$value->label,$label );
            $menu=$menu
            .$menu_footer_config->before
            .$label
            .$menu_footer_config->link_after
            .$menu_footer_config->after;
          } else if ($value->type=='CATEGORIES'){
            $label = str_replace("HREF","href='".$company->base_url.'category/'.$value->slug."'",$menu_footer_config->link_before );
            $label = str_replace("LABEL",$value->label,$label );
            $menu=$menu
            .$menu_footer_config->before
            .$label
            .$menu_footer_config->link_after
            .$menu_footer_config->after;
          } else if ($value->type=='GALLERY'){
            $label = str_replace("HREF","href='".$company->base_url.'gallery/'.$value->slug."'",$menu_footer_config->link_before );
            $label = str_replace("LABEL",$value->label,$label );
            $menu=$menu
            .$menu_footer_config->before
            .$label
            .$menu_footer_config->link_after
            .$menu_footer_config->after;

          }else if ($value->type=='PAGES'){
            $label = str_replace("HREF","href='".$company->base_url.'page/'.$value->slug."'",$menu_footer_config->link_before );
            $label = str_replace("LABEL",$value->label,$label );
            $menu=$menu
            .$menu_footer_config->before
            .$label
            .$menu_footer_config->link_after
            .$menu_footer_config->after;

          } else {
            $label = str_replace("HREF","href='".$company->base_url."'",$menu_footer_config->link_before );
            $label = str_replace("LABEL",$value->label,$label );

            $menu=$menu
            .$menu_footer_config->before
            .$label
            .$menu_footer_config->link_after
            .$menu_footer_config->after;

          }
        }
      }
      $menu=$menu.$menu_footer_config->container_after;
      $data['menu_footer'] =$menu;

    }

    return $data;
  }

  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function booking_index(Request $request)
  {

    $data=$this->getData($request);

    //gallery
    $setting=Setting::where('company_id','=',$data['company_profile']->id)
    ->where('name','=','menu_header')->first();
    $setting_decode=json_decode( $setting)->value;
    $data_gallery_category = [];
    if (is_object($setting)){
      $setting_decode=json_decode( $setting)->value;
      foreach ($setting_decode as $key => $value) {
        if ($value->type=='GALLERY'){

          $data_gallery_category=$value->data;
          $data['title'] = $value->label;
          break;

        }
      }
    }
    //
    //$data['gallery_category']=GalleryCategory::whereIn('id',$data_gallery_category)->get();
    $data['gallery_category']=GalleryCategory::where('company_id',$data['company_profile']->id)->get();

    $data_gallery_category = $data['gallery_category'];
    $dataGallery=Gallery::with('gallery_category');
    $dataGallery->where('company_id','=',$data['company_profile']->id);
    $dataGallery->where(function ($dataGallery) use ($data_gallery_category)
    {
      foreach ($data_gallery_category as $key)
      {
        $dataGallery->orWhere('gallery_category_id','=',$key->id);
      }
    });

    $data['gallery'] = $dataGallery->get();

    $data['rooms']=Room::with('room_gallery')->where('company_id','=',$data['company_profile']->id)->get();

    if($request->get('check_in_date')!=null){
      $check_in_date  = $request->get('check_in_date');
      $check_out_date = $request->get('check_out_date');
    } else {
      $datenow = Carbon::now();
      $check_in_date  = $datenow->addDays(1)->toDateString();
      $check_out_date = $datenow->addDays(2)->toDateString();
    }

    foreach ($data['rooms'] as $room) {
      $room->room_available = $this->get_room_available($data['company_profile']->id,$room->id,$check_in_date,$check_out_date);
      $room->room_available_detail = $this->get_room_available_detail($data['company_profile']->id,$room->id,$check_in_date,$check_out_date);
    }


    $carts = Cart::instance('booking')->content();
	   $carts_total = Cart::instance('booking')->total();
    //form array
    $data['nummber'] = array('0' => "0",'1' => "1", '2' => "2",
                             '3' => "3",'4' => "4", '5' => "5",
                             '6' => "6",'7' => "7", '8' => "8",
                             '9' => "9",'10' => "10");

    //return response()->json($data, 200, []);
    return view('be_general.details',$data);
  }

  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function booking_payment(Request $request)
  {

    $data=$this->getData($request);
    $data['nummber'] = array('0' => "0",'1' => "1", '2' => "2",
                             '3' => "3",'4' => "4", '5' => "5",
                             '6' => "6",'7' => "7", '8' => "8",
                             '9' => "9",'10' => "10");
    $use_cart =  false;

    //gallery
    $setting=Setting::where('company_id','=',$data['company_profile']->id)
    ->where('name','=','menu_header')->first();
    $setting_decode=json_decode( $setting)->value;
    $data_gallery_category = [];
    if (is_object($setting)){
      $setting_decode=json_decode( $setting)->value;
      foreach ($setting_decode as $key => $value) {
        if ($value->type=='GALLERY'){

          $data_gallery_category=$value->data;
          $data['title'] = $value->label;
          break;

        }
      }
    }
    //
    //$data['gallery_category']=GalleryCategory::whereIn('id',$data_gallery_category)->get();
    $data['gallery_category']=GalleryCategory::where('company_id',$data['company_profile']->id)->get();

    $data_gallery_category = $data['gallery_category'];
    $dataGallery=Gallery::with('gallery_category');
    $dataGallery->where('company_id','=',$data['company_profile']->id);
    $dataGallery->where(function ($dataGallery) use ($data_gallery_category)
    {
      foreach ($data_gallery_category as $key)
      {
        $dataGallery->orWhere('gallery_category_id','=',$key->id);
      }
    });

    $data['gallery'] = $dataGallery->get();

    $data['rooms']=Room::with('room_gallery')->where('company_id','=',$data['company_profile']->id)->get();

    if($request->get('check_in_date')!=null){
      $check_in_date  = $request->get('check_in_date');
      $check_in_date_c = Carbon::createFromFormat('Y-m-d', $check_in_date);
      $check_out_date = $request->get('check_out_date');
      $check_out_date_c = Carbon::createFromFormat('Y-m-d', $check_out_date);
    } else {
      $check_in_date_c  = Carbon::now()->addDays(1);
      $check_out_date_c = Carbon::now()->addDays(2);
      $check_in_date  = $check_in_date_c->toDateString();
      $check_out_date = $check_out_date_c->toDateString();
    }

    foreach ($data['rooms'] as $room) {
      $room->room_available = $this->get_room_available($data['company_profile']->id,$room->id,$check_in_date,$check_out_date);
      $room->room_available_detail = $this->get_room_available_detail($data['company_profile']->id,$room->id,$check_in_date,$check_out_date);
    }

    //get data from url as cart
    Carbon::setLocale('en');
    $data['check_in_date']  = $check_in_date_c->formatLocalized('%A %d, %B %Y');
    $data['check_out_date'] = $check_out_date_c->formatLocalized('%A %d, %B %Y');
    $night = $check_in_date_c->diffInDays($check_out_date_c);
    $room_rates_id = $request->get('rooms');
    $number_adults = $request->get('number_adults');
    $number_children = $request->get('number_children');
    $number_infants = $request->get('number_infants');

    $data['book_rooms']=Room::join('rates_room', 'room.id', '=', 'rates_room.room_type_id')
                        ->where('room.company_id','=',$data['company_profile']->id)
                        ->where('rates_room.id','=',$room_rates_id)
                        ->select('room.*')
                        ->get();

   $rowid = $check_in_date."".$check_out_date."".$room_rates_id;

    foreach ($data['book_rooms'] as $room) {
      $room->get_room_payment = $this->get_room_payment($data['company_profile']->id,$room->id,$room_rates_id,$check_in_date,$check_out_date);
      if($use_cart){
        $tmp_cart = Cart::instance('booking')->content();
        foreach ($tmp_cart as $cart) {
            if($cart->id == $rowid) {
                Cart::instance('booking')->remove($cart->rowId);
            }
        }
      } else {
        Cart::instance('booking')->destroy();
      }

      Cart::instance('booking')->add(['id' => $rowid,
                 'name' => $room->name.' '.$room->get_room_payment[0]->rate_name,
                 'qty' => 1,
                 'price' => $room->get_room_payment[0]->room_rates,
                 'options' => ['number_adults'   => $number_adults,
                               'number_children' => $number_children,
                               'number_infants'  => $number_infants,
                               'check_in_date'  => $check_in_date_c->toDateString(),
                               'check_out_date'  => $check_out_date_c->toDateString(),
                               'night'  => $night,
                               'rates_room_id'  => $room_rates_id,
                               'company_id'  => $data['company_profile']->id,
                     ]
                 ]);
    }

    $data['carts'] = Cart::instance('booking')->content();
    $data['carts_total'] = Cart::instance('booking')->total();

    //return response()->json($data, 200, []);
    return view('be_general.payment',$data);
  }

  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function post_booking_payment(Request $request)
  {

    $data=$this->getData($request);

    $carts = Cart::instance('booking')->content();

    $date = Carbon::now();

    DB::beginTransaction();

    try {

        foreach ($carts as $cart) {

              //save chart to booking iniial single room only next add to booking items
              $booking = new Booking;
              $id=Uuid::generate();
              $booking->id = $id;
              $booking->rf_number         = 'BECODE'.$date->year.''.$date->month.''.$date->day.''.$date->timestamp;
              $booking->booking_date      = $date->toDateString();
              $booking->guest_first_name  = $request->get('gfirst_name');
              $booking->guest_last_name   = $request->get('glast_name');
              $booking->contact_first_name= $request->get('cfirst_name');
              $booking->contact_last_name = $request->get('clast_name');
              $booking->contact_address   = $request->get('address1');
              $booking->contact_address2  = $request->get('address2');
              $booking->contact_phone     = $request->get('phone');
              $booking->contact_email     = $request->get('email');
              $booking->city              = $request->get('city');
              $booking->state             = $request->get('state');
              $booking->country           = $request->get('country');
              $booking->post_code         = $request->get('post_code');
              $booking->organisation      = $request->get('organisation');
              $booking->eta_time          = $request->get('eta_time');
              $booking->special_request1  = $request->get('special_request1');
              $booking->special_request2  = $request->get('special_request2');
              $booking->payment_method    = $request->get('payment_method');
              $booking->check_in          = $cart->options->check_in_date;
              $booking->check_out         = $cart->options->check_out_date;
              $booking->adult             = $cart->options->number_adults;
              $booking->child             = $cart->options->number_children;
              $booking->infant            = $cart->options->number_infants;
              $booking->company_id        = $cart->options->company_id;
              $booking->rates_room_id     = $cart->options->rates_room_id;
              $booking->amount_due        = $cart->price;
              $booking->save();

              /*modify room available
              1. get room id from $cart->options->room_rates_id
              2. create new or modify room available
              */

              $rate_room = RatesRoom::where('id',$booking->rates_room_id)->first();
              $check_in_date = Carbon::createFromFormat('Y-m-d', $booking->check_in);
              $check_out_date = Carbon::createFromFormat('Y-m-d', $booking->check_out);
              $night = $check_in_date->diffInDays($check_out_date);
              if($rate_room){
                while ($night > 0) {
                    $ra_array = array(
                                    'dates' => $check_in_date->toDateString(),
                                    'room_id'  => $rate_room->room_type_id,
                                    'company_id'     => $booking->company_id,
                                  );
                    $room_available = RoomAvailable::where($ra_array)->first();
                    if(!$room_available){
                      $room = Room::where('id',$rate_room->room_type_id)->first();
                      $id=Uuid::generate();
                      $room_available = new RoomAvailable();
                      $room_available->id = $id;
                      $room_available->dates = $check_in_date->toDateString();
                      $room_available->room_id = $rate_room->room_type_id;
                      $room_available->company_id = $booking->company_id;
                      $room_available->total_available = $room->total_available;
                    }
                    $room_available->total_available--;
                    $room_available->save();
                    $check_in_date->addDay();
                    $night--;
                }
              }

        }

        DB::commit();

     } catch (\Exception $e) {
         DB::rollback();

     }

    //Cart::instance('booking')->destroy();

    //---------------
    $vt = new Veritrans;

    $transaction_details = array(
        'order_id'          => $booking->rf_number,
        'gross_amount'      => $booking->amount_due
    );

    // Populate items
    $items = [
        array(
            'id'                => 'item1',
            'price'         => $booking->amount_due,
            'quantity'  => 1,
            'name'          => $room_available->room_id
        )
    ];

    // Populate customer's billing address
    $billing_address = array(
        'first_name'            => $booking->contact_first_name,
        'last_name'             => $booking->contact_last_name,
        'address'               => $booking->contact_address,
        'city'                  => $booking->city,
        'postal_code'           => $booking->post_code,
        'phone'                 =>  $booking->contact_phone,
        'country_code'  => 'IDN'
        );

    // Populate customer's shipping address
    $shipping_address = array(
        'first_name'            => $booking->contact_first_name,
        'last_name'             => $booking->contact_last_name,
        'address'               => $booking->contact_address,
        'city'                  => $booking->city,
        'postal_code'           => $booking->post_code,
        'phone'                 =>  $booking->contact_phone,
        'country_code'=> 'IDN'
        );

    // Populate customer's Info
    $customer_details = array(
        'first_name'            => $booking->contact_first_name,
        'last_name'             => $booking->contact_last_name,
        'email'                     => $booking->contact_email,
        'phone'                     => $booking->contact_phone,
        'billing_address' => $billing_address,
        'shipping_address'=> $shipping_address
        );

    // Data yang akan dikirim untuk request redirect_url.
    // Uncomment 'credit_card_3d_secure' => true jika transaksi ingin diproses dengan 3DSecure.
    $transaction_data = array(
        'payment_type'          => 'vtweb',
        'vtweb'                         => array(
            //'enabled_payments'    => [],
            'credit_card_3d_secure' => true
        ),
        'transaction_details'=> $transaction_details,
        'item_details'           => $items,
        'customer_details'   => $customer_details
    );

    try
    {
        $vtweb_url = $vt->vtweb_charge($transaction_data);
        return redirect($vtweb_url);
    }
    catch (Exception $e)
    {
        return $e->getMessage;
    }
    //---------------

    $data['booking'] = $booking;

    //return response()->json($data, 200, []);
    return view('be_general.confirmation',$data);
  }

  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function booking_detail($rf_number, Request $request)
  {

    $data=$this->getData($request);

    $booking = Booking::where('rf_number',$rf_number)->first();

    $data['booking'] = $booking;

    //return response()->json($data, 200, []);
    return view('be_general.confirmation',$data);
  }

  public function booking_confirmation(Request $request)
  {

    $data=$this->getData($request);
    //?order_id=BECODE201711251511612103&status_code=201&transaction_status=pending
    $rf_number = $request->get('order_id');

    $booking = Booking::where('rf_number',$rf_number)->first();

    $data['booking'] = $booking;

    //return response()->json($data, 200, []);
    return view('be_general.confirmation',$data);
  }

  public static function get_room_available($company_id,$room_type_id,$start_date,$end_date)
  {


    if ($start_date==$end_date){
      $datetime1 = Carbon::parse($end_date);
      $end_date=   $datetime1->addDays(1)->toDateString();
    }

    return  DB::select(" SELECT DISTINCT
      t_rates.rate_name,
      t_rates.rates_room_id,
      tmp_date.datenew,
      t_room_total.id,
      min(COALESCE(t_available.total_available,t_room_total.total_room)) AS total_room_available,
      sum(COALESCE(t_rates_detail.rates,t_rates.rates)) AS room_rates
      FROM tmp_date

      LEFT JOIN(
        SELECT *
        FROM room_available
        WHERE company_id='".$company_id."'
        AND room_id='".$room_type_id."'
        AND dates>='".$start_date."' AND dates <'".$end_date."'
        ) t_available ON t_available.dates=tmp_date.datenew
        LEFT JOIN (
          SELECT room.id,
          room.total_available AS total_room
          FROM room
          WHERE room.company_id='".$company_id."'
          AND room.deleted_at IS NULL
          AND room.id='".$room_type_id."'

          ) t_room_total ON t_room_total.id='".$room_type_id."'

          left join (
            select rates_room.room_type_id, rates.name as rate_name , rates_room.rates, rates_room.id as rates_room_id
            from rates_room, rates
            where rates.company_id ='".$company_id."' and rates.id = rates_room.rates_id and
            rates_room.room_type_id='".$room_type_id."'
            ) t_rates on t_rates.room_type_id='".$room_type_id."'

            LEFT JOIN(
              SELECT * FROM rates_room_detail
              WHERE dates>='".$start_date."' AND dates <'".$end_date."'
              ) t_rates_detail ON t_rates_detail.rates_room_id=t_rates.rates_room_id and t_rates_detail.dates=tmp_date.datenew

              WHERE datenew>='".$start_date."' AND datenew <'".$end_date."'
              group by t_rates.rates_room_id
              ORDER BY room_rates ASC
              ");
    }

  public static function get_room_available_detail($company_id,$room_type_id,$start_date,$end_date)
  {


    if ($start_date==$end_date){
      $datetime1 = Carbon::parse($end_date);
      $end_date=   $datetime1->addDays(1)->toDateString();
    }

    return  DB::select(" SELECT DISTINCT
      t_rates.rate_name,
      t_rates.rates_room_id,
      tmp_date.datenew,
      t_room_total.id,
      COALESCE(t_available.total_available,t_room_total.total_room) AS total_room_available,
      COALESCE(t_rates_detail.rates,t_rates.rates) AS room_rates
      FROM tmp_date

      LEFT JOIN(
        SELECT *
        FROM room_available
        WHERE company_id='".$company_id."'
        AND room_id='".$room_type_id."'
        AND dates>='".$start_date."' AND dates <'".$end_date."'
        ) t_available ON t_available.dates=tmp_date.datenew
        LEFT JOIN (
          SELECT room.id,
          room.total_available AS total_room
          FROM room
          WHERE room.company_id='".$company_id."'
          AND room.deleted_at IS NULL
          AND room.id='".$room_type_id."'

          ) t_room_total ON t_room_total.id='".$room_type_id."'

          left join (
            select rates_room.room_type_id, rates.name as rate_name , rates_room.rates, rates_room.id as rates_room_id
            from rates_room, rates
            where rates.company_id ='".$company_id."' and rates.id = rates_room.rates_id and
            rates_room.room_type_id='".$room_type_id."'
            ) t_rates on t_rates.room_type_id='".$room_type_id."'

            LEFT JOIN(
              SELECT * FROM rates_room_detail
              WHERE dates>='".$start_date."' AND dates <'".$end_date."'
              ) t_rates_detail ON t_rates_detail.rates_room_id=t_rates.rates_room_id and t_rates_detail.dates=tmp_date.datenew

              WHERE datenew>='".$start_date."' AND datenew <'".$end_date."'
              ORDER BY t_rates.rates_room_id, tmp_date.datenew ASC
              ");
  }

  public static function get_room_payment($company_id,$room_type_id,$room_rates_id,$start_date,$end_date)
  {

      //dd($room_type_id);

    if ($start_date==$end_date){
      $datetime1 = Carbon::parse($end_date);
      $end_date=   $datetime1->addDays(1)->toDateString();
    }

    return  DB::select(" SELECT DISTINCT
      t_rates.rate_name,
      t_rates.rates_room_id,
      tmp_date.datenew,
      t_room_total.id,
      min(COALESCE(t_available.total_available,t_room_total.total_room)) AS total_room_available,
      sum(COALESCE(t_rates_detail.rates,t_rates.rates)) AS room_rates
      FROM tmp_date

      LEFT JOIN(
        SELECT *
        FROM room_available
        WHERE company_id='".$company_id."'
        AND room_id='".$room_type_id."'
        AND dates>='".$start_date."' AND dates <'".$end_date."'
        ) t_available ON t_available.dates=tmp_date.datenew
        LEFT JOIN (
          SELECT room.id,
          room.total_available AS total_room
          FROM room
          WHERE room.company_id='".$company_id."'
          AND room.deleted_at IS NULL
          AND room.id='".$room_type_id."'

          ) t_room_total ON t_room_total.id='".$room_type_id."'

          left join (
            select rates_room.room_type_id, rates.name as rate_name , rates_room.rates, rates_room.id as rates_room_id
            from rates_room, rates
            where rates.company_id ='".$company_id."' and rates.id = rates_room.rates_id and
            rates_room.id ='".$room_rates_id."' and
            rates_room.room_type_id='".$room_type_id."'
            ) t_rates on t_rates.room_type_id='".$room_type_id."'

            LEFT JOIN(
              SELECT * FROM rates_room_detail
              WHERE dates>='".$start_date."' AND dates <'".$end_date."'
              ) t_rates_detail ON t_rates_detail.rates_room_id=t_rates.rates_room_id and t_rates_detail.dates=tmp_date.datenew

              WHERE datenew>='".$start_date."' AND datenew <'".$end_date."'
              group by t_rates.rates_room_id
              ORDER BY room_rates ASC
              ");
    }

  public static function get_room_rates($company_id,$room_type_id,$start_date,$end_date)
  {


    if ($start_date==$end_date){
      $datetime1 = Carbon::parse($end_date);
      $end_date=   $datetime1->addDays(1)->toDateString();
    }

    /*  */

    return  DB::select(" SELECT DISTINCT tmp_date.datenew,
      t_room_total.id,
      COALESCE(t_available.total_available,t_room_total.total_room) AS total_room_available
      FROM tmp_date
      LEFT JOIN(
        SELECT *
        FROM room_available
        WHERE company_id='".$company_id."'
        AND room_id='".$room_type_id."'
        AND dates>='".$start_date."' AND dates <'".$end_date."'
        ) t_available ON t_available.dates=tmp_date.datenew
        LEFT JOIN (
          SELECT room.id,
          room.total_available AS total_room
          FROM room
          WHERE room.company_id='".$company_id."'
          AND room.deleted_at IS NULL
          AND room.id='".$room_type_id."'

          ) t_room_total ON t_room_total.id='".$room_type_id."'
          WHERE datenew>='".$start_date."' AND datenew <'".$end_date."'
          ORDER BY tmp_date.datenew ASC
          ");
    }


 /* laravel + html */


}
