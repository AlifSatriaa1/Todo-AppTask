<?php


use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::resource('task', TaskController::class);
Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('task.updateStatus');
