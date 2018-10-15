<?php 
            $total = 0;
            $length=9;
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
       <td colspan="<?=$length?>" style="text-align: center"><h3>Room Revenue Report</h3></td>
      </tr>

 </thead>

 <tbody>

  <tr>

    <th>Date</th>
    <th>Room Type</th>
    <th>Room</th>
    <th>Guest</th>
    <th>Arrival</th>
    <th>Departure</th>
    <th>Agent</th>
    <th>Person</th>
    <th>Amount (IDR)</th>
  </tr>
  @foreach($datas as $data)
  <tr>
   
    <td>{{ $data->dates }}</td>
    <td>{{ $data->room_type_name }}</td>
    <td>{{ $data->room_name }}</td>
    <td>{{ $data->name }}</td>
    <td>{{ $data->arrival_date }}</td>
    <td>{{ $data->extended_date }}</td>
    <td>{{ $data->agent_name }}</td>
    <td>{{ $data->person }}</td>
    <td>{{ $data->total_amount }}</td>

    <?php $total=$total+ $data->total_amount ?>
  </tr>
  @endforeach

   <tr>

    <th colspan="8">Total</th>
    <th>{{$total}}</th>
  </tr>

 </tbody>
</table>