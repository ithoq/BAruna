<?php 
            $total = 0;
            $length = 8;
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
       <td colspan="<?=$length?>" style="text-align: center"><h3>Expenses Report</h3></td>
      </tr>

 </thead>
 <tbody>
  <tr>
    <th>Date</th>
    <th>Account</th>
    <th>Reference No</th>
    <th>Supplier</th>
    <th>Department</th>
    <th>Created By</th>
    <th>Remark</th>
    <th>Amount</th>
  </tr>
  @foreach($datas as $data)
  <tr>
   
    <td>{{ $data->transaction_date }}</td>
    <td>{{ $data->account_name }}</td>
    <td>{{ $data->reference_no }}</td>
    <td>{{ $data->supplier_name }}</td>
    <td>{{ $data->department_name }}</td>
    <td>{{ $data->created_by }}</td>
    <td>{{ $data->remark }}</td>
    <td>{{ $data->price }}</td>
    

    <?php $total=$total+ $data->price ?>
  </tr>
  @endforeach

   <tr>

    <th colspan="7">Total</th>
    <th>{{$total}}</th>
  </tr>

 </tbody>
</table>