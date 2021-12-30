<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    // protected $table = 'my_rooms'; if you want to rename the table
    // protected $primaryKey = 'room_id'; if you want to rename the primary key column
    // public $timestamps = false; // turn off timestamps by setting to false
    // protected $connection = 'sqlite'; If the database for this model is in another place


    public function cities()
    {
        return $this->belongsToMany('App\City', 'room_city', 'city_id', 'room_id');
    }
}
