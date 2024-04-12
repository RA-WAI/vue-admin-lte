<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('api/users', [UserController::class,'index']);
Route::post('api/users', [UserController::class,'store']);
Route::get('api/users/search', [UserController::class, 'search']);
Route::patch('api/users/{user}/change-role', [UserController::class, 'changeRole']);
Route::put('api/users/{user}', [UserController::class,'update']);
Route::delete('/api/users/{user}', [UserController::class, 'destroy']);



Route::get('{view}', ApplicationController::class)->where('view', '(.*)');


