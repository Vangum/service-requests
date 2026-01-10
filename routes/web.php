<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\WorkerController;
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

    Route::post('/logout', 'logout')->name('logout');
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

Route::controller(TaskController::class)->group(function () {
    Route::get('/tasks', 'index')->name('tasksIndex');
    Route::get('/tasks/create', 'create')->name('tasksCreate');
    Route::post('/tasks', 'store')->name('tasksStore');
    Route::get('/tasks/{task}/edit', 'edit')->name('tasksEdit');
    Route::put('/tasks/{task}', 'update')->name('tasksUpdate');
    Route::delete('/tasks/{task}', 'destroy')->name('tasksDestroy');
});

Route::controller(WorkerController::class)->group(function () {
    Route::get('/dashboard', 'index')->name('workersIndex');
    Route::put('/dashboard/tickets/{ticket}/complete', 'completeTicket')->name('completeTicket');
    Route::put('/dashboard/tickets/{ticket}/closed', 'closedTicket')->name('closedTicket');
    Route::put('/dashboard/tasks/{task}/complete', 'completeTask')->name('completeTask');
    Route::put('/dashboard/tasks/{task}/status', 'cancelledTask')->name('cancelledTask');
});