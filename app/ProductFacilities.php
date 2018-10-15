<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFacilities extends Model
{
    
	protected $table = 'product_facilities';

	public function facilities() {
		return $this->belongsTo('App\Facilities', 'facilities_id');
	}

	
	
}
