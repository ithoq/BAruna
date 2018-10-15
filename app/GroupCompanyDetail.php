<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class GroupCompanyDetail extends Model
{

    protected $table = 'group_company_detail';


    public function company() {
  		return $this->belongsTo('App\Company', 'company_id');
  	}

}
