<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Slider extends Model
{
    
	protected $table = 'slider';

	public function getImageAttribute()
    {
        return  getenv('S3_URL').$this->attributes['image'];
        
    }

    public function getImageThumbAttribute()
    {
        return getenv('S3_URL').$this->attributes['image_thumb'];
        
    }
    
}
