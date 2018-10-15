<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddCreditCategory extends Model
{
    protected $dates = ['deleted_at'];
	protected $table = 'add_credit_type';

	public function setNameAttribute($name)
    {
        $this->attributes['name'] = $name;
    }
}
