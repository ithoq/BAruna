<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ReportCategory extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'report_category';

	public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;    
        $this->attributes['slug'] = str_slug($this->attributes['name']);
    }
}
