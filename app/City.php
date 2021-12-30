<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function rooms()
    {
        return $this->belongsToMany('App\Room', 'room_city', 'city_id', 'room_id');
    }
}
