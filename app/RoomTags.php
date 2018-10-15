<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomTags extends Model
{

	protected $table = 'room_tags';

	public function tags() {
		return $this->belongsTo('App\Tags', 'tags_id');
	}



}
