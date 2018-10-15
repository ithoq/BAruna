<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomFacilities extends Model
{

	protected $table = 'room_facilities';

	public function facilities() {
		return $this->belongsTo('App\Facilities', 'facilities_id');
	}



}
