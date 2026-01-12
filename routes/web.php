<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\WorkerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    if ($user->isWorker()) {
        return redirect()->route('workersIndex');
    }

    if ($user->isTeacher()) {
        return redirect()->route('tasksIndex');
    }

    return redirect()->route('ticketsIndex');
})->name('home');

Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/register', 'showRegisterForm')->name('register');
        Route::post('/register', 'register')->name('registerAction');

        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login')->name('loginAction');
    });

    Route::middleware('auth')->post('/logout', 'logout')->name('logout');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('role:student,teacher,admin')->group(function () {
        Route::get('/tickets', [TicketController::class, 'index'])->name('ticketsIndex');
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('ticketsCreate');
        Route::post('/tickets', [TicketController::class, 'store'])->name('ticketsStore');

        Route::post('/tickets/{ticket}/comment', [TicketController::class, 'addComment'])->name('ticketsComment');
        Route::delete('/tickets/{ticket}/comment', [TicketController::class, 'deleteComment'])->name('ticketsCommentDelete');

        Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('ticketsEdit');
        Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('ticketsUpdate');
        Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('ticketsDestroy');
    });

    Route::middleware('role:teacher,admin')->group(function () {
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasksIndex');
        Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasksCreate');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasksStore');
        Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasksEdit');
        Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasksUpdate');
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasksDestroy');
    });

    Route::middleware('role:worker,admin')->group(function () {
        Route::controller(WorkerController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('workersIndex');
            Route::put('/dashboard/tickets/{ticket}/complete', 'completeTicket')->name('completeTicket');
            Route::put('/dashboard/tickets/{ticket}/closed', 'closedTicket')->name('closedTicket');
            Route::put('/dashboard/tasks/{task}/complete', 'completeTask')->name('completeTask');
            Route::put('/dashboard/tasks/{task}/status', 'cancelledTask')->name('cancelledTask');
        });
    });
});