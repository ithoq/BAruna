<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCategory extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'post_category';

  public function parent() {
    return $this->belongsTo('App\PostCategory', 'parent_id');
  }

	public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;
        $this->attributes['slug'] = str_slug($this->attributes['name']);
    }
}
