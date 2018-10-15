<?php 
            $total = 0;
            $qty = 0;
            $price = 0;
            $taxes = 0;
            $service = 0;
            $discount=0;
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
       <td colspan="<?=$length?>" style="text-align: center"><h3>POS Detail Report</h3></td>
      </tr>

 </thead>

 <tbody>
  <tr>
    <th>Date</th>
    <th>Invoice</th>
    <th >Product</th>
    <th >Account</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Discount</th>
    <th>Taxes</th>
    <th>Service</th>
    <th>Total</th>

  </tr>
  @foreach($datas as $data)
  <tr>
   


    <td>{{ $data->transaction_date }}</td>
    <td>{{ $data->invoice_no }}</td>
    <td>{{ $data->name }}</td>
    <td>{{ $data->account_name }}</td>
    <td>{{ $data->qty}}</td>
    <td>{{ $data->price }}</td>
    <td>{{ $data->discount }}</td>
    <td>{{ $data->taxes }}</td>
    <td>{{ $data->service }}</td>
    <td>{{ $data->total }}</td>
    

    <?php $total=$total+ $data->total;
        $total=$total+ $data->total;
        $qty=$qty+ $data->qty;
        $price=$price+ $data->price;
        $taxes=$taxes+ $data->taxes;
        $discount=$discount+ $data->discount;
        $service=$service+ $data->service;
   ?>
  </tr>
  @endforeach

   <tr>

    <th colspan="4">Total</th>
    <th>{{$qty}}</th>
    <th>{{$price}}</th>
    <th>{{$discount}}</th>
    <th>{{$taxes}}</th>
    <th>{{$service}}</th>
    <th>{{$total}}</th>
  </tr>

 </tbody>
</table>