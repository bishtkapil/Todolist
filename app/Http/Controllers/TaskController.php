<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use App\Models\Task;


class TaskController extends Controller
{
    public function index()
    {
        return view('tasks_index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|unique:tasks,title',
        ], [
            'title.unique' => 'The task title has already been taken.',
        ]);

        $task = Task::create(['title' => $validated['title']]);

        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update([
            'completed' => $request->completed,
        ]);
        return response()->json($task);
    }

    public function destroy($id)
    {
        // dd($id);
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function all()
    {
        $tasks = Task::all();

        return response()->json($tasks);
    }
}

