<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class RatesRoom extends Model
{
     use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'rates_room';
}
