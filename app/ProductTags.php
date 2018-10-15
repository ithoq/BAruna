<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTags extends Model
{
    
	protected $table = 'product_tags';

	public function tags() {
		return $this->belongsTo('App\Tags', 'tags_id');
	}

	
	
}
