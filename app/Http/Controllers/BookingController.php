<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Uuid;
use Log;
use DB;
use Hash;
use Validator;
use GuzzleHttp\Client;
use JWTAuth;
use DateTime;
use Carbon\Carbon;
use Mail;
use Illuminate\Mail\Message;
use Cart;
use App\Veritrans\Veritrans;

use App\Product;
use App\Company;
use App\Booking;
use App\BookingGuest;
use App\BookingRoom;
use App\BookingPayment;
use App\RatesRoom;
use App\Room;
use App\RoomAvailable;

class BookingController extends Controller
{


    public static $rules = array(
    'first_name'=>'required',
    'last_name'=>'required',
    'phone'=>'required',
    'email'=>'required|email',
    'payment_method'=>'required',
    'country'=>'required',
    );

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $data['name']=$request->json('name');
            $data['booking_date']=$request->json('booking_date');
            $data['email']=$request->json('email');
            $data['address']=$request->json('address');
            $data['country']=$request->json('country');
            $data['phone']=$request->json('phone');
            $data['adult']=$request->json('adult');
            $data['child']=$request->json('child');
            $data['product_id']=$request->json('product_id');
            $data['hotel_name']=$request->json('hotel_name');
            $data['activities_date']=$request->json('activities_date');
            $data['message']=$request->json('message');
            $company=Company::where('code','=',$request->json('company'))->first();
            $data['company_id']=$company->id;
            $data['company_name']=$company->name;
            $data['company_email']=$company->email;
            $data['company_address']=$company->address;
            $data['company_phone']=$company->tlp." / ".$company->phone;
            $data['company_logo']=$company->logo;
            $data['company_domain']=$company->base_url;

            $validator = Validator::make($data, BookingController::$rules);

                if ($validator->fails()) {
                    return response()->json(array(
                        'error' => true,
                        'message' => $validator->errors()->all()),
                        200
                        );
                }else{

                DB::beginTransaction();

                try {
                    $obj=new Booking;
                    $id=Uuid::generate();
                    $obj->id=$id;
                    $obj->name=$data['name'];
                    $obj->booking_date=date('Y-m-d');
                    $obj->address=$data['address'];
                    $obj->phone=$data['phone'];
                    $obj->adult=$data['adult'];
                    $obj->email=$data['email'];
                    $obj->child=$data['child'];
                    $activities_date = Carbon::parse($data['activities_date']);
                    $obj->activities_date=$activities_date->toDateString();
                    $obj->hotel_name=$data['hotel_name'];
                    $obj->message=$data['message'];
                    $obj->company_id=$data['company_id'];
                    $obj->product_id=$data['product_id'];
                      $obj->save();
                    $obj->id=$id->__toString();
                    $data['booking_id'] = $obj->id;
                    //mengambil nama product dari product_id
                    $product=Product::find($data['product_id']);
                    if($product){
                        $data['product_name'] = $product->name;
                    }
                    $this->sentEmail($data);
                     DB::commit();

                     return response()->json(array(
                        'error' => false,
                        'message' => ['Booking Success']),
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
    }

    public function post_booking_payment(Request $request)
    {

      $carts = Cart::instance('booking')->content();
      $date = Carbon::now();

      $data['booking_date'] = $date->toDateString();
      $data['guest_first_name']=$request->get('gfirst_name');
      $data['guest_last_name']=$request->get('glast_name');
      $data['first_name']=$request->get('cfirst_name');
      $data['last_name']=$request->get('clast_name');
      $data['address']=$request->get('address1');
      $data['address2']=$request->get('address2');
      $data['phone']=$request->get('phone');
      $data['email']=$request->get('email');
      $data['city']=$request->get('city');
      $data['state']=$request->get('state');
      $data['country']=$request->get('country');
      $data['post_code']=$request->get('post_code');
      $data['organisation']=$request->get('organisation');
      $data['eta_time']=$request->get('eta_time');
      $data['special_request1']=$request->get('special_request1');
      $data['special_request2']=$request->get('special_request2');
      $data['payment_method']=$request->get('payment_method');

      $validator = Validator::make($data, BookingController::$rules);

      if ($validator->fails()) {
          return response()->json(array(
              'error' => true,
              'message' => $validator->errors()->all()),
              200
              );
      }else{

          DB::beginTransaction();

          try {

              foreach ($carts as $cart) {

                    //save chart to booking iniial single room only next add to booking items
                    $booking = new Booking;
                    $id=Uuid::generate();
                    $booking->id = $id;
                    $booking->rf_number         = 'BECODE'.$date->year.''.$date->month.''.$date->day.''.$date->timestamp;
                    $booking->booking_date      = $data['booking_date'];
                    $booking->check_in          = $cart->options->check_in_date;
                    $booking->check_out         = $cart->options->check_out_date;
                    $booking->adult             = $cart->options->number_adults;
                    $booking->child             = $cart->options->number_children;
                    $booking->infant            = $cart->options->number_infants;
                    $booking->eta_time          = $data['eta_time'];
                    $booking->special_request1  = $data['special_request1'];
                    $booking->special_request2  = $data['special_request2'];
                    $booking->payment_method    = $data['payment_method'];
                    $booking->amount_due        = $cart->price;
                    $booking->company_id        = $cart->options->company_id;
                    $booking->save();
                    $booking->id=$id->__toString();

                    $booking_guest = new BookingGuest;
                    $id=Uuid::generate();
                    $booking_guest->id                = $id;
                    $booking_guest->booking_id        = $booking->id;
                    $booking_guest->guest_first_name  = $data['guest_first_name'];
                    $booking_guest->guest_last_name   = $data['guest_last_name'];
                    $booking_guest->first_name        = $data['first_name'];
                    $booking_guest->last_name         = $data['last_name'];
                    $booking_guest->address           = $data['address'];
                    $booking_guest->address2          = $data['address2'];
                    $booking_guest->phone             = $data['phone'];
                    $booking_guest->email             = $data['email'];
                    $booking_guest->city              = $data['city'];
                    $booking_guest->state             = $data['state'];
                    $booking_guest->country           = $data['country'];
                    $booking_guest->post_code         = $data['post_code'];
                    $booking_guest->organisation      = $data['organisation'];
                    $booking_guest->company_id        = $cart->options->company_id;
                    $booking_guest->save();

                    $booking_rooms = new BookingRoom;
                    $id=Uuid::generate();
                    $booking_rooms->id                = $id;
                    $booking_rooms->booking_id        = $booking->id;
                    $booking_rooms->company_id        = $cart->options->company_id;
                    $booking_rooms->rates_room_id     = $cart->options->rates_room_id;
                    /*modify room available
                    1. get room id from $cart->options->room_rates_id
                    2. create new or modify room available
                    */
                    $rate_room = RatesRoom::where('id',$booking_rooms->rates_room_id)->first();
                    $check_in_date = Carbon::createFromFormat('Y-m-d', $booking->check_in);
                    $check_out_date = Carbon::createFromFormat('Y-m-d', $booking->check_out);
                    $night = $check_in_date->diffInDays($check_out_date);

                    if($rate_room){
                      //asign
                      $booking_rooms->room_type_id = $rate_room->room_type_id;
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
                      //asign
                      $booking_rooms->room_type_id = $rate_room->room_type_id;
                    }
                    $booking_rooms->save();

                    $booking_payments = new BookingPayment;
                    $id=Uuid::generate();
                    $booking_payments->id                = $id;
                    $booking_payments->booking_id        = $booking->id;
                    $booking_payments->company_id        = $cart->options->company_id;
                    $booking_payments->amount_due        = $cart->price;
                    $booking_payments->payment_type      = $data['payment_method'];
                    $booking_payments->save();

              }

              DB::commit();

              //Cart::instance('booking')->destroy();

           } catch (\Exception $e) {
               DB::rollback();

               return $e->getMessage;
           }

           $data['booking'] = $booking;

           //getcompany data
           $company=Company::where('id','=',$booking->company_id)->first();
           $data['company_id']=$company->id;
           $data['company_name']=$company->name;
           $data['company_email']=$company->email;
           $data['company_address']=$company->address;
           $data['company_phone']=$company->tlp." / ".$company->phone;
           $data['company_logo']=$company->logo;
           $data['company_domain']=$company->base_url;

           //run payment
           $vt = new Veritrans;

           $transaction_details = array(
               'order_id'          => $booking->rf_number,
               'gross_amount'      => $booking->amount_due
           );

           // Populate items
           $items = [
               array(
                   'id'            => 'item1',
                   'price'         => $booking->amount_due,
                   'quantity'      => 1,
                   'name'          => $data['company_name'],
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

      }

      //return response()->json($data, 200, []);
      //return view('be_general.confirmation',$data);

    }






    public function sentEmail($data){

        //Begin Send Mail
        $data['body']           = 'Booking';
        $data['to_email']       = $data['email'];
        $data['to_name']        = $data['name'];
        $data['from_email']     = env('SENDGRID_FROM_EMAIL');
        $data['from_name']      = $data['company_name'];
        $data['replyto_email']  = $data['company_email'];
        $data['replyto_name']   = $data['company_name'];
        $data['subject']        = '['.$data['company_name'].'] Booking Confirmation';
        $data['template_id']    = env('SENDGRID_TEMPLATE_BOOKING');
        $data['link_logo']      = $data['company_logo'];
        $data['link_click']     = $data['company_domain']."booking/viewbooking/".$data['booking_id'];
        $data['client_domain_name']  = $data['company_domain'];
        //$data['client_domain_name']  = str_replace("http://","",$data['company_domain']);
        //$data['client_domain_name']  = str_replace("https://","",$data['client_domain_name']);

        //avoid null : convert null to ""
        $data = array_map(function($v){
            return (is_null($v)) ? "-" : $v;
        },$data);

        $data = array_map(function($v){
            return (is_string($v)) ? $v : (string)$v;
        },$data);

        $send_00 = false;

        //dd($data);

        //sending email

        try {

          $response = Mail::send('email.plain', ['data' => $data['body']], function (Message $message) use ($data) {
            $message->to($data['to_email'], $data['to_name']);
            $message->from($data['from_email'], $data['from_name']);
            $message->setReplyTo($data['replyto_email'], $data['replyto_name']);
            $message->subject($data['subject']);
            $message->embedData([
                  'personalizations' => [
                      [
                          'to' => [
                              'email' => $data['to_email'],
                              'name' => $data['to_name'],
                          ],
                          'cc' => [
                              'email' => $data['replyto_email'],
                              'name' => $data['replyto_name'],
                          ],
                          'substitutions' => [
                              '%booking_name%'      => $data['name'],
                              '%booking_product%'   => $data['product_name'],
                              '%booking_date%'      => $data['booking_date'],
                              '%booking_address%'   => $data['address'],
                              '%booking_phone%'     => $data['phone'],
                              '%booking_adult%'     => $data['adult'],
                              '%booking_child%'     => $data['child'],
                              '%booking_email%'     => $data['email'],
                              '%booking_act_date%'  => $data['activities_date'],
                              '%booking_hotel%'     => $data['hotel_name'],
                              '%booking_message%'   => $data['message'],
                              '%client_company_name%' => $data['company_name'],
                              '%client_email%'        => $data['company_email'],
                              '%client_address%'      => $data['company_address'],
                              '%client_phone%'        => $data['company_phone'],
                              '%client_domain_name%'  => $data['client_domain_name'],
                              '%link_click%'          => $data['link_click'],
                              '%link_logo%'           => $data['link_logo'],

                          ],
                      ],
                  ],
                  'categories' => ['airybook'],
                  'template_id' => $data['template_id'],
                  'custom_args' => [
                      'user_id' => '123'
                  ]
              ], 'sendgrid/x-smtpapi');
          });

          if($response->getStatusCode()=='202' || $response->getStatusCode()=='200'){
            $send_00 = true;
          }

        } catch (ModelNotFoundException $e) {

          $send_00 = false;

        }
        //end of sending email
    }




}
