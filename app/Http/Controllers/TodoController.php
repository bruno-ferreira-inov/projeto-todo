<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        return view('todos.index');
    }

    public function list()
    {
        return response()->json(
            Todo::latest()->get()
        );
    }

    public function store(Request $request)
    {
        if (!$request->expectsJson()) {
            abort(406);
        }

        $validated = $request->validate([
            'title' => 'required|string',
        ]);
        $todo = Todo::create($validated);

        return response()->json($todo);
    }

    public function toggle(Todo $todo)
    {
        $todo->update([
            'completed' => !$todo->completed,
        ]);

        return response()->json($todo);
    }
}
