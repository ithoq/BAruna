<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Setting extends Model
{
    
	protected $table = 'setting';

	

	public function getValueAttribute(){
		return json_decode($this->attributes['value'],true);

	}

}
