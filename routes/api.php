<?php

use App\Address;
use App\City;
use App\Comment;
use App\Company;
use App\Reservation;
use App\Room;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/info', function () {
    $result = Company::find(2);

    return response()->json([$result->reservations]);
});
