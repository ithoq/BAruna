<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	protected $table = 'company';
    

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['cpanel_username', 'cpanel_subdomain','cpanel_password'];
    
   	
	public function getImageAttribute()
    {
        return  getenv('S3_URL').$this->attributes['image'];
        
    }

    public function getLogoAttribute()
    {
        return getenv('S3_URL').$this->attributes['logo'];
        
    }

    public function getTripadvisorLogoAttribute()
    {
        return getenv('S3_URL').$this->attributes['tripadvisor_logo'];
        
    }


    

	
}
