<?php 
            $total=0; 
            $total_per_date=array();
            $length=3+sizeof($datas['header']);
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
       <td colspan="<?=$length?>" style="text-align: center"><h3>Room Charge Report</h3></td>
      </tr>

 </thead>

 <tbody>
 
    <tr>

      <th style="width: 20px">Room</th>
      <th style="width: 20px">Room Type</th>
      @foreach($datas['header'] as $data)
      <th style="width: 13px">{{$data->label_date}}</th>
      <?php $total_per_date[$data->date]=0; 
            ?>
      @endforeach
      <th class="text-right">Total</th>

  </tr>
   
  @foreach($datas['detail'] as $data)

    <tr>
       <td>{{$data['room_name']}}</td>
       <td>{{$data['room_type_name']}}</td>
        
       @foreach($data['dates'] as $tot) 
            <td style="background-color:{{$tot['color']}}">{{$tot['total']}}</td>
            <?php 
              $total_per_date[$tot['date']]=$total_per_date[$tot['date']]+$tot['total'];
            ?>
       @endforeach                  
        <td><strong>{{$data['total']}}</strong></td>
     
    </tr>  

    <?php
              $total=$total+$data['total'];
          ?>

  @endforeach

   <tr>
    <th colspan="2">Total</th>
     @foreach($datas['header'] as $data)
        <th>{{$total_per_date[$data->date]}}</th>
    @endforeach
    <th>{{$total}}</th>
  </tr>
    
    <tr>
      <td></td>
    </tr>  
    <tr>
      <tr>
      <td></td>
    </tr>  
    <tr>

      @foreach($datas['agent'] as $data)
            <th style="background-color:{{$data->color}}">{{$data->name}}</th>
       @endforeach                  

    </tr>  




 </tbody>
</table>