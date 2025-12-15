<?php

use App\Http\Controllers\TodoController;
use App\Models\Todo;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('todos.index');
});

Route::get('/todos',  [TodoController::class, 'index']);
Route::get ('/todos/list', [TodoController::class, 'list']);
Route::post('/todos', [TodoController::class, 'store']);
Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggle']);
