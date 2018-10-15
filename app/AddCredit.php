<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddCredit extends Model
{
    protected $table = 'add_credit';

	public function credit_category() {
		return $this->belongsTo('App\AddCreditCategory', 'add_credit_type');
	}
}
