<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class Rates extends Model
{
     use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'rates';

    public function currency() {
		return $this->belongsTo('App\Currency', 'currency_id');
	}


  public function getEndDateAttribute()
   {
        if ($this->attributes['use_end_date']==0){
           $today = Carbon::now();
           $today->addYears(3);
           return $today->toDateString();
        }else{
         return $this->attributes['end_date'];
        }

   }

}
