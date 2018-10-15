<?php 
      $total_price = 0;
      $total_discount = 0;
      $total_service = 0;
      $total_tax = 0;
      $total_amount = 0;
      $total_person = 0;
      $length=10;
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
       <td colspan="<?=$length?>" style="text-align: center"><h3>Room Revenue Detail Report</h3></td>
      </tr>

 </thead>

 <tbody>
 
  <tr>
    <th>Date</th>
    <th>Room Type</th>
    <th>Room</th>
    <th>Folio</th>
    <th>Person</th>
    <th>Price</th>  
    <th>Disc.</th>
    <th>Taxes</th>
    <th>Service</th>
    <th>Total</th>


  </tr>
  @foreach($datas as $data)
  <tr>
   

    <td>{{$data->dates }}</td>
    <td>{{$data->room_name}}</td>
    <td>{{$data->room_type_name}}</td>
    <td>{{$data->folio_no}}</td>
    <td>{{$data->person}} Person</td>
    <td>{{$data->price_amount}}</td>
    <td>{{$data->discount_amount}}</td>
    <td>{{$data->service_amount}}</td>
    <td>{{$data->taxes_amount}}</td>
    <td>{{$data->total_amount}}</td>
    
  </tr>

  <?php 
      $total_price = $total_price + $data->price_amount;
      $total_discount = $total_discount + $data->discount_amount;
      $total_service = $total_service + $data->service_amount;
      $total_tax = $total_tax + $data->taxes_amount;
      $total_amount = $total_amount + $data->total_amount ;
      $total_person = $total_person + $data->person;
?>

  @endforeach

   <tr>

      <th colspan="4">Total</th>
      <th>{{$total_person}} Person</th>
      <th>{{$total_price}}</th>
      <th>{{$total_discount}}</th>
      <th>{{$total_service}}</th>
      <th>{{$total_tax}}</th>
      <th>{{$total_amount}}</th>
  </tr>

 </tbody>
</table>