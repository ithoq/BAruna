<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'category';

	public function parent() {
		return $this->belongsTo('App\Category', 'parent_id');
	}

	
	public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;    
        $this->attributes['slug'] = str_slug($this->attributes['name']);
    }
}
