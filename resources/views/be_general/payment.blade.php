@extends('be_general.header')

@section('container')
<!-- CONTENT -->
<div class="container">


  <div class="container mt25 offset-0">


    <!-- LEFT CONTENT -->
    <div class="col-md-8 pagecontainer2 offset-0">
      {!! Form::open(['url' => Session::get('locale').'/direct/book', 'method'=>'POST  ','class'=>'ct-formSearch--home','role'=>'form']) !!}
      {!! Form::hidden('company', Input::get('company')) !!}
      <div class="padding30 grey">
        <span class="size16px bold dark left">Guest Details</span>
        <div class="roundstep active right">1</div>
        <div class="clearfix"></div>
        <div class="line4"></div>
        Please tell us who will be checking in. Must be 18 or older. <br/><br/>

        <div class="col-md-3">
          <span class="size12">First Name*</span>
          <input type="text" name="gfirst_name" class="form-control " placeholder="">
        </div>
        <div class="col-md-3">
          <span class="size12">Last Name*</span>
          <input type="text" name="glast_name" class="form-control " placeholder="">
        </div>
        <div class="col-md-2">
          <span class="size12">Adults</span>
          {!! Form::select('number_adults', $nummber, Input::get('number_adults'), ['class' => 'form-control ']) !!}
        </div>
        <div class="col-md-2">
          <span class="size12">Children (1-8)</span>
          {!! Form::select('number_children', $nummber, Input::get('number_children'), ['class' => 'form-control ']) !!}
        </div>
        <div class="col-md-2">
          <span class="size12">Infants (<1)</span>
          {!! Form::select('number_infants', $nummber, Input::get('number_infants'), ['class' => 'form-control ']) !!}
        </div>
        <div class="clearfix"></div>

        <br/>
        <br/>

        <span class="size16px bold dark left">Contact Details</span>
        <div class="roundstep active right">2</div>
        <div class="clearfix"></div>
        <div class="line4"></div>
        Please tell us who will be the Contact <br/><br/>

        <div class="col-md-6">
          <span class="size12">First Name*</span>
          <input type="text" name="cfirst_name" class="form-control " placeholder="">
        </div>
        <div class="col-md-6">
          <span class="size12">Last Name*</span>
          <input type="text" name="clast_name" class="form-control " placeholder="">
        </div>
        <div class="clearfix"></div>

        <br/>

        <div class="col-md-6">
          <span class="size12">Email*</span>
          <input type="email" name="email" class="form-control " placeholder="">
        </div>
        <div class="col-md-6">
          <span class="size12">Phone*</span>
          <input type="text" name="phone" class="form-control " placeholder="">
        </div>
        <div class="clearfix"></div>

        <br/>

        <div class="col-md-6">
          <span class="size12">Address 1*</span>
          <input type="text" name="address1" class="form-control " placeholder="">
        </div>
        <div class="col-md-6">
          <span class="size12">Address 2</span>
          <input type="text" name="address2" class="form-control " placeholder="">
        </div>
        <div class="clearfix"></div>

        <br/>

        <div class="col-md-3">
          <span class="size12">City*</span>
          <input type="text" name="city" class="form-control " placeholder="">
        </div>
        <div class="col-md-3">
          <span class="size12">State</span>
          <input type="text" name="state" class="form-control " placeholder="">
        </div>
        <div class="col-md-3">
          <span class="size12">Country*</span>
          <input type="text" name="country" class="form-control " placeholder="">
        </div>
        <div class="col-md-3">
          <span class="size12">Post / ZIP Code*</span>
          <input type="text" name="post_code" class="form-control " placeholder="">
        </div>
        <div class="clearfix"></div>

        <br/>

        <div class="col-md-6">
          <span class="size12">Organisation</span>
          <input type="text" name="organisation" class="form-control " placeholder="">
        </div>
        <div class="col-md-6">
          <span class="size12">Estimated arrival time</span>
          <input type="text" name="eta_time" class="form-control " placeholder="">
        </div>
        <div class="clearfix"></div>

        <div class="clearfix"></div>

        <br/>
        <br/>

        <span class="size16px bold dark left">Additional Requests</span>
        <div class="roundstep active right">3</div>
        <div class="clearfix"></div>
        <div class="line4"></div>
        Please tell us who will be the Contact <br/><br/>
        <br/>
        <div class="col-md-4">
        </div>
        <div class="col-md-8 textleft">
          Bedding / Smoking Request (optional)
          <!-- Collapse 3 -->
          <button type="button" class="collapsebtn3 collapsed mt-5" data-toggle="collapse" data-target="#collapse3"></button>
          <div id="collapse3" class="collapse">
            <textarea name="special_request1" rows="3" class="form-control margtop10"></textarea>
          </div>
          <!-- End of collapse 3 -->
          <div class="clearfix"></div>

          Special Requests (optional)
          <!-- Collapse 4 -->
          <button type="button" class="collapsebtn3 collapsed mt-5" data-toggle="collapse" data-target="#collapse4"></button>
          <div id="collapse4" class="collapse">
            <textarea name="special_request2" rows="3" class="form-control margtop10"></textarea>
          </div>
          <!-- End of collapse 4 -->
          <div class="clearfix"></div>

        </div>
        <div class="clearfix"></div>


        <br/>
        <br/>

        <span class="size16px bold dark left">How would you like to pay?</span>
        <div class="roundstep active right">4</div>
        <div class="clearfix"></div>
        <div class="line4"></div>



        <br/>

        <div class="col-md-6">
          {!! Form::radio('payment_method', 'bank_transfer', true) !!}
          <span class="size12">bank_transfer</span>
        </div>
        <div class="col-md-6">
          {!! Form::radio('payment_method', 'credit_card') !!}
            <span class="size12">credit_card</span>
        </div>

        <br/>
        <br/>
        <span class="size16px bold dark left">Review and book your trip</span>
        <div class="roundstep active right">5</div>
        <div class="clearfix"></div>
        <div class="line4"></div>

        <div class="alert alert-warning">
        Important information about your booking:<br/>
        <p class="size12">• This reservation is non-refundable and cannot be changed or canceled.</p>
        </div>
        By selecting to complete this booking I acknowledge that I have read and accept the <a href="#" class="orange">rules &
        restrictions</a> <a href="#" class="orange">terms & conditions</a> , and <a href="#" class="orange">privacy policy</a>.	<br/>

        <button type="submit" class="btn-search4 margtop20">Complete booking</button>
      </form>

      </div>

    </div>
    <!-- END OF LEFT CONTENT -->

    <!-- RIGHT CONTENT -->
    <div class="col-md-4" >

      <div class="pagecontainer2 paymentbox grey">
        <div class="sidebar-title padding20">
          <span class="opensans size18 bold caps">Reservation Summary</span>
        </div>
        <div class="line3"></div>

        <div class="hpadding30 margtop30">

          <span class="size12 grey2 bold">
          Check In: <span class="right">{{$check_in_date}}</span>
          <div class="clearfix"></div>
          Check Out: <span class="right">{{$check_out_date}}</span>
          <div class="clearfix"></div>
          </span>

          <br/>

        </div>

        <div class="line3"></div>

        @foreach ($carts as $cart)

        <div class="hpadding30 margtop30">

          <span class="dark size16 bold">Room : {{$cart->name}}</span>
          <div class="fdash mt10"></div><br/>

          <table class="wh100percent size13">
            <tr>
              <td valign="top">{{$cart->options['night']}} Night(s)</td>
              <td class="textright">{{$cart->price}}</td>
            </tr>
            <tr>
              <td valign="top">Taxes & Fees per night</td>
              <td class="textright">0</td>
            </tr>
            <tr>
              <td colspan="2"><div class="fdash mt10"></div><br/></td>
            </tr>
            <tr>
              <td valign="top" class="dark bold">Subtotal*</td>
              <td class="textright dark bold">{{$cart->price}}</td>
            </tr>
          </table>

          <br/>

        </div>

        @endforeach

        <div class="line3"></div>

        <div class="padding30">
          <span class="left size14 dark">Grand Total:</span>
          <span class="right lred2 bold size18">{{$carts_total}}</span>
          <div class="clearfix"></div>
        </div>


      </div>

        <br/>

        <div class="pagecontainer2 paymentbox grey">
          <div class="sidebar-title padding20">
            <span class="opensans size18 bold caps">Contact</span>
          </div>
          <div class="line3"></div>

        <div class="hpadding30 margtop30">
          <p class="size14 grey">
            <span class="dark size16 bold">{{$company_profile->name}}</span> <br/>
            <span class="glyphicon glyphicon-home"></span> {{$company_profile->address}} <br/>
            <span class="glyphicon glyphicon-phone-alt"></span> {{$company_profile->tlp}} <br/>
            <span class="glyphicon glyphicon-earphone"></span> {{$company_profile->phone}} <br/>
            <span class="glyphicon glyphicon-envelope"></span> {{$company_profile->email}} <br/>
            <span class="glyphicon glyphicon-globe"></span> {{$company_profile->base_url}} <br/>
          </p>
        </div>
      </div>

    </div>
    <!-- END OF RIGHT CONTENT -->


  </div>


</div>
<!-- END OF CONTENT -->
@endsection
