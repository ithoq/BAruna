<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Role extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'role';

    public function role_list() {
		
		return $this->hasMany('App\RoleList', 'role_id');
	}


	 public function company()
    {
    	
        	return $this->belongsTo('App\Company', 'company_id');
    }


     public function scopeCompany($query, $company_id)
		{
		    $query = $query->where(function ($query) use ($company_id) { 
		        $query->where('company_id', '=', $company_id)->orWhere('company_id', '=', 'SYSTEM'); 
		    });
		    return $query;    
		}
}
