<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Tasks/Index', [
            'tasks' => Task::with('assignee:id,name')
                ->where('teacher_id', auth()->id())
                ->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Tasks/Create', [
            'workers' => User::where('role', 'worker')->get(['id', 'name']),
        ]);
    }

    public function store(TaskRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Task::create([
            'teacher_id' => auth()->id(),
            'description' => $validated['description'],
            'location' => $validated['location'],
            'scheduled_at' => $validated['scheduled_at'],
            'assigned_to' => $validated['assigned_to'],
        ]);

        return redirect()->route('tasksIndex');
    }

    public function edit(Task $task)
    {
        return Inertia::render('Tasks/Edit', [
            'task' => $task,
            'workers' => User::where('role', 'worker')->get(['id', 'name']),
        ]);
    }

    public function update(TaskRequest $request, Task $task): RedirectResponse
    {
        $validated = $request->validated();

        $task->update($validated);

        return redirect()->route('tasksIndex');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('tasksIndex');
    }
}
