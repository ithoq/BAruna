<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomType extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'room';


	public function income_account() {
		return $this->belongsTo('App\Account', 'income_account_id');
	}
}
