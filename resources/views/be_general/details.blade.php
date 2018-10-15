@extends('be_general.header')

@section('container')
<div id="top" class="header-bg-image hidden-xs hidden-sm no-image" style="background-image: none;">
  <div class="header-filter">
    <div class="container">
        {!! Form::open(['url' => Session::get('locale').'/direct', 'method'=>'GET','class'=>'content-filter','role'=>'form']) !!}
        {!! Form::hidden('company', Input::get('company')) !!}
        <div class="filter-dates">
          <date-picker class="ng-scope">
            <div class="form-group pull-left">
              <label for="date-picker-start" class="ng-binding">Check In</label>
              <div class="calendar-input-container">
                {!! Form::text('check_in_date', Input::get('check_in_date'), ['id' =>  'check_in_date', 'placeholder' =>  'yyyy-mm-dd','class'=>'form-control date-picker-start text-capitalize']) !!}
                <i class="icon icon-calendar" id="date-picker-start-icon"></i>
              </div>
            </div>
            <div class="form-group pull-left">
              <label for="date-picker-end" class="ng-binding">Check Out</label>
              <div class="calendar-input-container">
                {!! Form::text('check_out_date', Input::get('check_out_date'), ['id' =>  'check_out_date', 'placeholder' =>  'yyyy-mm-dd','class'=>'form-control date-picker-end text-capitalize']) !!}
                <i class="icon icon-calendar" id="date-picker-end-icon"></i>
              </div>
            </div>
          </date-picker>
        </div>
        <div class="filter-quantities">
          <availability-filter class="ng-isolate-scope">
            <div class="number-adults-selector pull-left">
              <label for="number_adults" class="ng-binding">Adults</label>
              {!! Form::select('number_adults', $nummber, Input::get('number_adults'), ['class' => 'form-control']) !!}
          </div>
          <div class="number-children-selector pull-left">
              <label for="number_children" class="ng-binding"> Children
                <span ng-show="::maximumChildAge" class="ng-binding">(1-8)</span>
              </label>
              {!! Form::select('number_children', $nummber, Input::get('number_children'), ['class' => 'form-control']) !!}
            </div>
            <div class="number-infants-selector pull-left" >
              <label for="number_infants" class="ng-binding"> Infants
                <span ng-show="::maximumInfantAge" class="ng-binding">(&lt;1)</span>
              </label>
              {!! Form::select('number_infants', $nummber, Input::get('number_infants'), ['class' => 'form-control']) !!}
            </div>
            <div class="number-infants-selector pull-left" >
              <label class="ng-binding">&nbsp;</label>
              <button type="submit" class="form-control">Check Availability </button>
            </div>

          </availability-filter>
        </div>
        <!-- ngIf: !campaign && hasPromotions -->
      </form>
    </div>
  </div>
</div>

<!-- CONTENT -->
<div class="container">



  <div class="container mt25 offset-0">

    <div class="col-md-8 pagecontainer2 offset-0">
      <div id="roomrates" class="tab-pane fade active in">

        @foreach ($rooms as $room)

          @foreach ($room->room_available as $room_available)

          <div class="padding20">
            <div class="col-md-4 offset-0">
              <a href="#"><img src="{{$room->room_gallery->image_thumb}}" alt="" class="fwimg"/></a>
            </div>
            <div class="col-md-8 offset-0">
              <div class="col-md-8 mediafix1">
                <h4 class="opensans dark bold margtop1 lh1">{{$room->name}}</h4>
                <h5>{{$room_available->rate_name}}</h5>
                <ul class="hotelpreferences margtop10">
                  <li class="icohp-internet"></li>
                  <li class="icohp-air"></li>
                  <li class="icohp-pool"></li>
                  <li class="icohp-childcare"></li>
                  <li class="icohp-fitness"></li>
                  <li class="icohp-breakfast"></li>
                  <li class="icohp-parking"></li>
                </ul>
                <div class="clearfix"></div>
                <a href="#">More Information</a>
                <ul class="checklist2 margtop10">
                  <li>FREE Cancellation</li>
                  <li>Pay at hotel or pay today </li>
                </ul>
              </div>
              <div class="col-md-4 center bordertype4">
                <span class="opensans orange size24">{{$room_available->room_rates}}</span><br/>
                <span class="opensans lightgrey size12">/room</span><br/><br/>
                <span class="lred bold">{{$room_available->total_room_available}} left</span><br/><br/>
                {!! Form::open(['url' => Session::get('locale').'/direct/book', 'method'=>'GET','class'=>'ct-formSearch--home','role'=>'form']) !!}
                {!! Form::hidden('company', Input::get('company')) !!}
                {!! Form::hidden('check_in_date', Input::get('check_in_date')) !!}
                {!! Form::hidden('check_out_date', Input::get('check_out_date')) !!}
                {!! Form::hidden('number_adults', Input::get('number_adults')) !!}
                {!! Form::hidden('number_children', Input::get('number_children')) !!}
                {!! Form::hidden('number_infants', Input::get('number_infants')) !!}
                {!! Form::hidden('rooms', $room_available->rates_room_id) !!}
                <button type="submit" class="bookbtn mt1">Book</button>
                </form>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="line-book"></div>

          @endforeach
        @endforeach




      </div>
    </div>

    <div class="col-md-4" >

      <div class="pagecontainer2 paymentbox grey">
        <div class="sidebar-title padding20">
          <span class="opensans size18 bold caps">Contact</span>
        </div>
          <div class="line3"></div>

          <div class="hpadding30 margtop30">
            <p class="size14 grey">
            <b>{{$company_profile->name}}</b> <br/>
            <span class="glyphicon glyphicon-home"></span> {{$company_profile->address}} <br/>
            <span class="glyphicon glyphicon-phone-alt"></span> {{$company_profile->tlp}} <br/>
            <span class="glyphicon glyphicon-earphone"></span> {{$company_profile->phone}} <br/>
            <span class="glyphicon glyphicon-envelope"></span> {{$company_profile->email}} <br/>
            <span class="glyphicon glyphicon-globe"></span> {{$company_profile->base_url}} <br/>
          </p>
        </div>
      </div>

    </div>
  </div>



</div>
<!-- END OF CONTENT -->
@endsection
