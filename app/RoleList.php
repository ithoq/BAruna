<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class RoleList extends Model
{
    protected $table = 'role_list';

      public function menu() {
		return $this->belongsTo('App\Menu', 'menu_id');
	}
}
