<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Task::query();

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                  ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        $tasks = $query->orderBy('created_at', 'desc')->get();

        // Debugging
        \Log::info('Search term: ' . $request->get('search'));
        \Log::info('SQL Query: ' . $query->toSql());
        \Log::info('Query Bindings: ', $query->getBindings());
        \Log::info('Results count: ' . $tasks->count());

        return view('task.index', compact('tasks'));
    }

    
    public function create()
    {
        return view('task.create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,completed',
        ]);

        Task::create($request->all());
        return redirect()->route('task.index')->with('success', 'Task created successfully!');
    }

    
    public function edit(Task $task)
    {
        return view('task.edit', compact('task'));
    }

    
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,completed',
        ]);

        $task->update($validated);

        return redirect()->route('task.index')
            ->with('success', 'Task updated successfully');
    }

    
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('task.index')->with('success', 'Task deleted successfully!');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $task->update([
            'status' => $request->status
        ]);

        return redirect()->route('task.index')->with('success', 'Status updated successfully');
    }
}
