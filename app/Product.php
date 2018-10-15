<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'product';
	protected $appends = ['litle_description'];

	
    public function getLitleDescriptionAttribute(){
       return str_limit( strip_tags($this->attributes['description']), $limit = 150, $end = '...');
    }

	

	public function category() {
		return $this->belongsTo('App\Category', 'category_id');
	}

	public function product_gallery() {
		return $this->belongsTo('App\ProductGallery', 'image_id');
	}

	
	public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;    
        $this->attributes['slug'] = str_slug($this->attributes['name']);
    }
}
