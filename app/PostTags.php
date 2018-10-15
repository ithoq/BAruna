<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTags extends Model
{

	protected $table = 'post_tags';

  public function tags() {
  		return $this->belongsTo('App\Tags', 'tags_id');
  }

  public function post(){
  	return $this->belongsTo('App\Post', 'post_id');
  }



}
