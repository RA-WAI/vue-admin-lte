<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\ClientController;

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
    Route::post('/api/appointments', 'create');
});

Route::controller(ClientController::class)->group(function () {
    Route::get('/api/all-clients', 'getAllClients');
});

Route::get('{view}', ApplicationController::class)->where('view', '(.*)');


