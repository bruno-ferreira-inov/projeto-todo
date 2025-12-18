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
            'description' => 'nullable|string',
            'priority' => 'integer',
            'completed' => 'required|boolean',
            'completion_date' => 'nullable|date'
        ]);
        $todo = Todo::create($validated);

        return response()->json($todo);
    }

    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'priority' => 'integer',
            'completed' => 'required|boolean',
            'completion_date' => 'nullable|date'
        ]);

        if ($validated['completed'] && !$todo->completed) {
            $validated['completed_date'] = now();
        }

        if (!$validated['completed']) {
            $validated['completed_date'] = null;
        }
        $todo->update($validated, );

        return response()->json($todo);
    }

    public function toggle(Todo $todo)
    {
        $todo->update([
            'completed' => !$todo->completed,
        ]);

        return response()->json($todo);
    }

    public function test()
    {
        return view('todos.test');
    }
}
