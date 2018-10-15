<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'report';

	public function report_category() {
		return $this->belongsTo('App\ReportCategory', 'report_category_id');
	}


	public function getPdfAttribute()
    {
        return  getenv('S3_URL').$this->attributes['pdf'];
        
    }

    public function getImageThumbAttribute()
    {
        return getenv('S3_URL').$this->attributes['image_thumb'];
        
    }
}
