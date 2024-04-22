<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\AppointmentController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/api/users', 'index');
        Route::post('/api/users', 'store');
        Route::patch('/api/users/{user}/change-role', 'changeRole');
        Route::put('/api/users/{user}', 'update');
        Route::delete('/api/users/{user}', 'destroy');
        Route::delete('/api/users', 'bulkDelete');
    });

    Route::controller(AppointmentController::class)->group(function () {
        Route::get('/api/appointments', 'index');
        Route::post('/api/appointments', 'create');
        Route::get('/api/appointments/{appointment}/edit', 'edit');
        Route::put('/api/appointments/{appointment}', 'update');
        Route::delete('/api/appointments/{appointment}', 'delete');
    });

    Route::controller(ClientController::class)->group(function () {
        Route::get('/api/all-clients', 'getAllClients');
    });

    Route::controller(ProfileController::class)->group(function (){
        Route::get('/api/profile', 'index');
        Route::post('/api/upload-profile-image', 'uploadProfileImage');
    });

    Route::get('{view}', ApplicationController::class)->where('view', '(.*)');
});
