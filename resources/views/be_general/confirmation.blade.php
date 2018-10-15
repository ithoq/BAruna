@extends('be_general.header')

@section('container')
<!-- CONTENT -->
<div class="container">


  <div class="container mt25 offset-0">


    <!-- LEFT CONTENT -->
    <div class="col-md-8 pagecontainer2 offset-0">

      <div class="padding30 grey">
        <div class="alert alert-success fade in">
  				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  				<strong>Thank You!</strong> Your Reservation has been confirmed.
  			</div>

        <div class="alert alert-success">
        <p class="size16px">
          <strong>Reference Number</strong><br/>
          {{$booking->rf_number}}
        </p>
        </div>

        <span class="size16px bold dark left">Booking Confirmation</span>
        <div class="clearfix"></div>
        <div class="line4"></div>
        Please print this acknowledge and present it to the property reception on arrival. A receipt will be emailed to you at the addess entered during the booking process.
        <br/>
        <button type="submit" class="btn-search4 margtop20">Print</button>
        <button type="submit" class="btn-search4 margtop20">Back to Hotel Website</button>


        <br/>
        <br/>
        <span class="size16px bold dark left">Booking Summary</span>
        <div class="clearfix"></div>
        <div class="line4"></div>

        <span class="bold dark left">Accommodation</span>

        <br/>
        <br/>
        <span class="size16px bold dark left">Term and Conditions</span>
        <div class="clearfix"></div>
        <div class="line4"></div>
        TnC

        <br/>
        <br/>
        <span class="size16px bold dark left">Property Cancellation Policy</span>
        <div class="clearfix"></div>
        <div class="line4"></div>
        TnC

        <br/>
        <br/>
        <span class="size16px bold dark left">Location Instructions</span>
        <div class="clearfix"></div>
        <div class="line4"></div>
        TnC

      </div>

    </div>
    <!-- END OF LEFT CONTENT -->

    <!-- RIGHT CONTENT -->
    <div class="col-md-4" >

      <div class="pagecontainer2 paymentbox grey">
        <div class="sidebar-title padding20">
          <span class="opensans size18 bold caps">Booking Details</span>
        </div>
        <div class="line3"></div>

        <div class="hpadding30 margtop30">

          <span class="size12 grey2 bold">
          Guest Name: <span class="right">{{$booking->guest_first_name.' '.$booking->guest_last_name}}</span>
          <div class="clearfix"></div>
          Contact Name: <span class="right">{{$booking->contact_first_name.' '.$booking->contact_last_name}}</span>
          <div class="clearfix"></div>
          Contact Email: <span class="right">{{$booking->contact_email}}</span>
          <div class="clearfix"></div>
          Contact Phone: <span class="right">{{$booking->contact_phone}}</span>
          <div class="clearfix"></div>
          Contact Address: <span class="right">{{$booking->contact_address}}</span>
          <div class="clearfix"></div>
          Reference Number: <span class="right">{{$booking->rf_number}}</span>
          <div class="clearfix"></div>
          Booking Date: <span class="right">{{$booking->booking_date}}</span>
          <div class="clearfix"></div>
          Check In: <span class="right">{{$booking->check_in}}</span>
          <div class="clearfix"></div>
          Check Out: <span class="right">{{$booking->check_out}}</span>
          <div class="clearfix"></div>
          Number of Nights: <span class="right">carbon</span>
          <div class="clearfix"></div>
          Booking Status: <span class="right">{{$booking->status_str}}</span>
          <div class="clearfix"></div>
          </span>

          <br/>

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
