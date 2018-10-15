<?php 
            $total_net_rates = 0;
            $total_net_extrabed = 0;
            $total_sales=0;
            $total_commission=0;
            $length=12 + sizeof($list_pos);
?>  
<table>

<thead>
      <tr>
       <td colspan="<?=$length?>" style="text-align: center"><h2>{{$company->name}}</h2></td>
      </tr>
      <tr>
       <td colspan="<?=$length?>" style="text-align: center">{{$company->address}}</td>
      </tr>
       <tr>
       <td colspan="<?=$length?>" style="text-align: center"><h3>Hotel Recapitulation Report</h3></td>
      </tr>

 </thead>
 <tbody>
  
  <tr>
   <th>Reservation No</th>
    <th>Guest Name</th>
    <th>Room Type</th>
    <th>Room</th>
    <th>Arrival</th>
    <th>Departure</th>
    <th>Agent</th>
    <th>Pax</th>
    <th class="text-right" >Rates</th>
    <th class="text-right" >Extra Bed</th>
    <?php $i=0 ; ?>
    @foreach($list_pos as $pos)
    <th class="text-right" >{{$pos->name}}</th>
    <?php $total_pos_array[$i]=0;
        $i++; ?>
    @endforeach
    <th class="text-right" >Total Sales</th>
    <th class="text-right" >Commission</th>
  </tr>


  @foreach($datas as $data)
  <tr>
    <td>{{ $data['reservation_no'] }}</td>
    <td>{{ $data['guest_profile_name'] }}</td>
    <td>{{ $data['room_type_name'] }}</td>
    <td>{{ $data['room_name'] }}</td>
    <td>{{ $data['arrival_date'] }}</td>
    <td>{{ $data['departure_date'] }}</td>
    <td>{{ $data['agent_name'] }}</td>
    <td>{{ $data['total_pax'] }}</td>
    <td class="text-right">{{ $data['total_net_rates'] }}</td>
    <td class="text-right">{{ $data['total_net_extrabed'] }}</td>
      <?php $i=0; ?>
      @foreach($data['tx_pos'] as $key => $tx_pos)
        <td class="text-right">{{ $data['tx_pos'][$key]->total }}</td>
        <?php 
            $total_pos_array[$i] = $total_pos_array[$i] + $data['tx_pos'][$key]->total;  
        $i++; ?>

      @endforeach
    <td class="text-right">{{ $data['total_sales'] }}</td>
    <td class="text-right">{{ $data['total_commission'] }}</td>
    
    <?php   $total_net_rates = $total_net_rates+ $data['total_net_rates'] ;
            $total_net_extrabed = $total_net_extrabed+ $data['total_net_extrabed'] ;
            $total_sales=$total_sales+ $data['total_sales'] ;
            $total_commission=$total_commission+ $data['total_commission'] ;
            ?>
  </tr>
  @endforeach

   <tr>

    <th colspan="8">Total</th>
    <th class="text-right">{{$total_net_rates}}</th>
    <th class="text-right">{{$total_net_extrabed}}</th>
    <?php $i=0; ?>
     @foreach($list_pos as $key => $pos)
        <th class="text-right">{{$total_pos_array[$i]}}</th>
        <?php $i++; ?>
     @endforeach
    
    <th class="text-right">{{$total_sales}}</th>
    <th class="text-right">{{$total_commission}}</th>
  </tr>

 </tbody>
</table>