<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\TaskController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::prefix("v1")->group(function(){
    Route::prefix("/user")->group(function (){
        Route::post("register",[UserController::class,"create"]);
        Route::post("login",[UserController::class,"login"]);
        Route::post("refresh",[UserController::class,"refresh_token"]);
    });

    Route::prefix("/task")->group(function(){
        Route::get("get",[TaskController::class,"getAll"])->middleware('auth:api');
        Route::post("create",[TaskController::class,"create"])->middleware('auth:api');
        Route::put("update",[TaskController::class,"update"])->middleware('auth:api');

    });

    Route::get("/test_date",function(){
        $d1 = strtotime(now());
        echo now();
        $d2 = strtotime("2021-11-09 09:38:42");
        $totalSecondsDiff = abs($d1-$d2);
        $totalMinutesDiff = $totalSecondsDiff/60;
        dd($totalMinutesDiff);
    });
});
