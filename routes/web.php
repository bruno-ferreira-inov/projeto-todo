<?php

use App\Http\Controllers\TodoController;
use App\Models\Todo;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('todos.index');
});

Route::get('/todos', [TodoController::class, 'index']);
Route::get('/todos/list', [TodoController::class, 'list']);
Route::post('/todos', [TodoController::class, 'store']);
Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggle']);
Route::patch('todos/{todo}/update', [TodoController::class, 'update']);
Route::delete('/todos/{todo}', [TodoController::class, 'delete']);
Route::get('/test', [TodoController::class, 'test']);
