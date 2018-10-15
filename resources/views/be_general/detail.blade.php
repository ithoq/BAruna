Nama Hotel : {{$company_profile->name}} <br/>
Bintang Hotel : - <br/>
Form Checkin Check out : - <br/>
@foreach ($rooms as $room)
  ------------------------ <br/><br/>
  Room : {{$room->name}} <br/>
  description : {{$room->description}} <br/>
  Start From : {{$room->room_available[0]->room_rates}} <br/>
  Rates Plan : <br/>
  @foreach ($room->room_available as $room_available)
      >>--------- <br/>
      Rate Name : {{$room_available->rate_name}} <br/>
      Rate Total : {{$room_available->room_rates}} <br/>
  @endforeach
@endforeach
