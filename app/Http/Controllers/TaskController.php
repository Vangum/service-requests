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
            'completion_notes' => $validated['completion_notes'] ?? null,
            'assigned_to' => $validated['assigned_to'],
        ]);

        return redirect()->route('tasksIndex');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
