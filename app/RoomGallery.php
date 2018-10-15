<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomGallery extends Model
{

	protected $table = 'room_gallery';


	public function getImageAttribute()
    {
        return  getenv('S3_URL').$this->attributes['image'];

    }

    public function getImageThumbAttribute()
    {
        return getenv('S3_URL').$this->attributes['image_thumb'];

    }


}
