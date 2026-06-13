<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\SubmissionController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/join', [HomeController::class, 'joinRoom'])->name('room.join');

// Room routes
Route::get('/r/{roomCode}', [RoomController::class, 'show'])->name('room.show');
Route::post('/r/{roomCode}/submit', [RoomController::class, 'submit'])->name('room.submit');

// Admin auth routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // Room management
        Route::get('/rooms', [AdminRoomController::class, 'index'])->name('rooms.index');
        Route::get('/rooms/create', [AdminRoomController::class, 'create'])->name('rooms.create');
        Route::post('/rooms', [AdminRoomController::class, 'store'])->name('rooms.store');
        Route::get('/rooms/{roomCode}', [AdminRoomController::class, 'show'])->name('rooms.show');
        Route::get('/rooms/{roomCode}/edit', [AdminRoomController::class, 'edit'])->name('rooms.edit');
        Route::put('/rooms/{roomCode}', [AdminRoomController::class, 'update'])->name('rooms.update');
        Route::delete('/rooms/{roomCode}', [AdminRoomController::class, 'destroy'])->name('rooms.destroy');

        // Submissions
        Route::get('/rooms/{roomCode}/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
        Route::patch('/submissions/{id}/status', [SubmissionController::class, 'updateStatus'])->name('submissions.status');
        Route::delete('/submissions/{id}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');
        Route::get('/submissions/{id}/quote', [SubmissionController::class, 'generateQuoteImage'])->name('submissions.quote');
    });
});

// Room admin panel (shortcut via room code)
Route::get('/r/{roomCode}/admin', function($roomCode) {
    return redirect()->route('admin.rooms.show', $roomCode);
});
