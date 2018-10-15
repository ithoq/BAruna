<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{

	protected $table = 'product_gallery';
	 

	public function getImageAttribute()
    {
        return  getenv('S3_URL').$this->attributes['image'];
        
    }

    public function getImageThumbAttribute()
    {
        return getenv('S3_URL').$this->attributes['image_thumb'];
        
    }


}
