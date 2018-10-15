<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class RoomAvailable extends Model
{

    protected $table = 'room_available';
    protected $fillable = array('dates','room_id','company_id');
}
