<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;

Route::middleware(['check.auth'])->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('profile', 'profile')->name('profile');
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('post.logout');

    Route::controller(TaskController::class)->group(function () {
        Route::get('tasks', 'index')->name('tasks.index');
        Route::get('tasks/create', 'create')->name('tasks.create');
        Route::post('tasks', 'store')->name('tasks.store');
        Route::get('tasks/{id}/edit', 'edit')->name('tasks.edit');
        Route::put('tasks/{id}', 'update')->name('tasks.update');
        Route::delete('tasks/{id}', 'destroy')->name('tasks.destroy');
    });
});

Route::middleware(['check.guest'])->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('post.login');

    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'store'])->name('post.register');
});
