<div class="container pagecontainer offset-0">

  <!-- SLIDER -->
  <div class="col-md-8 details-slider">

    <div id="c-carousel">
      <div id="wrapper">
        <div id="inner">
          <div id="caroufredsel_wrapper2">
            <div id="carousel">
              @foreach ($gallery as $gal)
                <img src="{{ $gal->image }}" alt="{{$gal->title}}"/>
              @endforeach
            </div>
          </div>
          <div id="pager-wrapper">
            <div id="pager">
              @foreach ($gallery as $gal)
                <img src="{{ $gal->image }}" alt="{{$gal->title}}"/>
                <img src="{{ $gal->image_thumb }}" width="120" height="68" alt="{{$gal->title}}"/>
              @endforeach
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        <button id="prev_btn2" class="prev2"><img src="{{ URL::asset('green/images/spacer.png') }}" alt=""/></button>
        <button id="next_btn2" class="next2"><img src="{{ URL::asset('green/images/spacer.png') }}" alt=""/></button>

      </div>
    </div> <!-- /c-carousel -->





  </div>
  <!-- END OF SLIDER -->

  <!-- RIGHT INFO -->
  <div class="col-md-4 detailsright offset-0">
    <div class="padding20">
      <h4 class="lh1">{{$company_profile->name}}</h4>
      <img src="{{ URL::asset('green/images/smallrating-5.png') }}" alt=""/>
    </div>

    <div class="line3"></div>

    <div class="clearfix"></div><br/>
    {!! Form::open(['url' => Session::get('locale').'/direct', 'method'=>'GET','class'=>'ct-formSearch--home','role'=>'form']) !!}
    {!! Form::hidden('company', Input::get('company')) !!}
    <div class="col-md-4 padding20">
      <span class="opensans size13"><b>Check In</b></span>
    </div>
    <div class="col-md-8 padding20">
      {!! Form::text('check_in_date', Input::get('check_in_date'), ['id' =>  'check_in_date', 'placeholder' =>  'yyyy-mm-dd','class'=>'form-control mySelectCalendar']) !!}
    </div>
    <div class="clearfix"></div><br/>
    <div class="col-md-4">
    <span class="opensans size13"><b>Check Out </b></span>
    </div>
    <div class="col-md-8">
      {!! Form::text('check_out_date', Input::get('check_out_date'), ['id' =>  'check_out_date', 'placeholder' =>  'yyyy-mm-dd','class'=>'form-control mySelectCalendar']) !!}
    </div>
    <div class="clearfix"></div><br/>

    <div class="col-md-4">
      <span class="opensans size13"><b>Adults</b></span>
    </div>
    <div class="col-md-8">
        {!! Form::select('number_adults', $nummber, Input::get('number_adults'), ['class' => 'form-control mySelectBoxClass']) !!}
    </div>
    <div class="clearfix"></div><br/>

    <div class="col-md-4">
      <span class="opensans size13"><b>Children (1-8)</b></span>
    </div>
    <div class="col-md-8">
      {!! Form::select('number_children', $nummber, Input::get('number_children'), ['class' => 'form-control mySelectBoxClass']) !!}
    </div>
    <div class="clearfix"></div><br/>

    <div class="col-md-4">
      <span class="opensans size13"><b>Infants (<1)</b></span>
    </div>
    <div class="col-md-8">
      {!! Form::select('number_infants', $nummber, Input::get('number_infants'), ['class' => 'form-control mySelectBoxClass']) !!}
    </div>
    <div class="clearfix"></div><br/>

    <div class="hpadding20">
      <button type="submit" class="booknow margtop20 btnmarg">Check Availability</button>
    </div>
  </div>
  </form>
  <!-- END OF RIGHT INFO -->

</div>
<!-- END OF container-->
