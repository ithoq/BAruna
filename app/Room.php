<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'room';
	protected $appends = ['litle_description'];


    public function getLitleDescriptionAttribute(){
       return str_limit( strip_tags($this->attributes['description']), $limit = 150, $end = '...');
    }





	public function room_gallery() {
		return $this->belongsTo('App\RoomGallery', 'image_id');
	}

    public function room_rates() {
        return $this->hasMany('App\RatesRoom', 'room_type_id', 'id');
	}

	public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;
        $this->attributes['slug'] = str_slug($this->attributes['name']);
    }
}
