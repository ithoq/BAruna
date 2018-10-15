<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Gallery extends Model
{
    
	protected $table = 'gallery';

	public function gallery_category() {
		return $this->belongsTo('App\GalleryCategory', 'gallery_category_id');
	}


	public function getImageAttribute()
    {
        return  getenv('S3_URL').$this->attributes['image'];
        
    }

    public function getImageThumbAttribute()
    {
        return getenv('S3_URL').$this->attributes['image_thumb'];
        
    }

}
