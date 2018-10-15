<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'post';
    protected $appends = ['litle_description'];

	public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;
        $this->attributes['slug'] = str_slug($this->attributes['name']);
    }

    public function post_category() {
		return $this->belongsTo('App\PostCategory', 'post_category_id');
	}




    public function getLitleDescriptionAttribute(){
       return str_limit( strip_tags($this->attributes['description']), $limit = 150, $end = '...');
    }


    public function getImageAttribute()
    {
        return  getenv('S3_URL').$this->attributes['image'];

    }

    public function getImageThumbAttribute()
    {
        return getenv('S3_URL').$this->attributes['image_thumb'];

    }

}
