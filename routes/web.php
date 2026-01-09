<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', []);
})->name('home');

Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register')->name('registerAction');

    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')->name('loginAction');
});

Route::controller(TicketController::class)->group(function () {
    Route::get('/tickets', 'index')->name('ticketsIndex');
    Route::get('/tickets/create', 'create')->name('ticketsCreate');
    Route::post('/tickets', 'store')->name('ticketsStore');
    Route::get('/tickets/{ticket}', 'show')->name('ticketsShow');
    Route::get('/tickets/{ticket}/edit', 'edit')->name('ticketsEdit');
    Route::put('/tickets/{ticket}', 'update')->name('ticketsUpdate');
    Route::delete('/tickets/{ticket}', 'destroy')->name('ticketsDestroy');
});
