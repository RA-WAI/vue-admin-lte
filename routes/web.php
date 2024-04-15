<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\AppointmentController;

Route::get('/', function () {
    return view('welcome');
});
Route::controller(UserController::class)->group(function () {
    Route::get('/api/users', 'index');
    Route::post('/api/users', 'store');
    Route::get('/api/users/search', 'search');
    Route::patch('/api/users/{user}/change-role', 'changeRole');
    Route::put('/api/users/{user}', 'update');
    Route::delete('/api/users/{user}', 'destroy');
    Route::delete('/api/users', 'bulkDelete');
});

Route::controller(AppointmentController::class)->group(function () {
    Route::get('/api/appointments', 'index');
});

Route::get('{view}', ApplicationController::class)->where('view', '(.*)');


