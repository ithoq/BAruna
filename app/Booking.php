<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Booking extends Model
{
	
    protected $table = 'booking';

    
	public function scopeCompany($query, $company_id)
		{
		    $query = $query->where(function ($query) use ($company_id) { 
		        $query->where('company_id', '=', $company_id)->orWhere('company_id', '=', 'SYSTEM'); 
		    });
		    return $query;    
		}
    
}
