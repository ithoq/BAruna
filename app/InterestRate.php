<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InterestRate extends Model
{
    protected $dates = ['deleted_at'];
	protected $table = 'interest_rate';

	public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;
    }
}
