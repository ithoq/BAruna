<?php 
            $total = 0;
            $length=7;
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
       <td colspan="<?=$length?>" style="text-align: center"><h3>POS Report</h3></td>
      </tr>

 </thead>

 <tbody>
  
  <tr>

    <th>Date</th>
    <th>Time</th>
    <th>Invoice</th>
    <th>POS</th>
    <th>Guest From</th>
    <th>Created By</th>
    <th>Total</th>

  </tr>
  @foreach($datas as $data)
  <tr>
   
    <td>{{ $data->transaction_date }}</td>
    <td>{{ $data->created_time }}</td>
    <td>{{ $data->invoice_no }}</td>
    <td>{{ $data->pos_name }}</td>
    <td>{{ $data->room_type_name}} - {{$data->room_name}}</td>
    <td>{{ $data->created_by }}</td>
    <td>{{ $data->total }}</td>
    

    <?php $total=$total+ $data->total ?>
  </tr>
  @endforeach

   <tr>

    <th colspan="6">Total</th>
    <th>{{$total}}</th>
  </tr>

 </tbody>
</table>