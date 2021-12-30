<?php

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    /* $result = DB::table('reservations') */
    /*     ->join('rooms', 'reservations.room_id', '=', 'rooms.id') */
    /*     ->join('users', 'reservations.user_id', '=', 'users.id') */
    /*     ->where('rooms.id', '>', 3) */
    /*     ->where('users.id', '>', 1) */
    /*     ->get(); */

    /* // Same as above but easier to read */
    /* $result = DB::table('reservations') */
    /*     ->join('rooms', function ($join) { */
    /*         $join->on('reservations.room_id', '=', 'rooms.id') */
    /*             ->where('rooms.id', '>', 3); */
    /*     })->join('users', function ($join) { */
    /*         $join->on('reservations.user_id', '=', 'users.id') */
    /*             ->where('users.id', '=', 1); */
    /*     })->get(); */

    /* $rooms = DB::table('rooms')->where('id', '>', 3); */
    /* $users = DB::table('users')->where('id', '>', 1); */
    /* $result = DB::table('reservations') */
    /*     ->joinSub($rooms, 'rooms', function ($join) { */
    /*         $join->on('reservations.room_id', '=', 'rooms.id'); */
    /*     }) */
    /*     ->joinSub($users, 'users', function ($join) { */
    /*         $join->on('reservations.user_id', '=', 'users.id'); */
    /*     }) */
    /*     ->get(); */


    $result = DB::table('rooms')
        ->crossJoin('cities')
        ->get();
    dump($result);

    return view('welcome');
});
