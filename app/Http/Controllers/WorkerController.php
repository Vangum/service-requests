<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class WorkerController extends Controller
{
    public function index(): Response
    {
        $worker = auth()->user();

        if (!$worker->isWorker()) {
            abort(403, 'Доступно только для работников');
        }

        $tickets = Ticket::with(['user', 'comments'])
            ->orderByRaw("
            CASE status
                WHEN 'new' THEN 1
                ELSE 2
            END
        ")
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'title' => $ticket->reason,
                    'reason' => $ticket->reason,
                    'location' => $ticket->location,
                    'status' => $ticket->status,
                    'completion_notes' => $ticket->completion_notes,
                    'created_at' => $ticket->created_at,
                    'user' => [
                        'id' => $ticket->user->id,
                        'name' => $ticket->user->name,
                    ],
                    'comment' => $ticket->comments()->first()?->comment,
                ];
            });

        $tasks = Task::with('teacher')
            ->orderByRaw("
            CASE status
                WHEN 'new' THEN 1
                ELSE 2
            END
        ")
            ->orderByDesc('scheduled_at')
            ->get();

        return Inertia::render('Worker/Index', [
            'tickets' => $tickets,
            'tasks' => $tasks,
        ]);
    }

    public function completeTicket(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'completion_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $ticket->update([
            'completion_notes' => $validated['completion_notes'],
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->back();
    }

    public function closedTicket(Request $request, Ticket $ticket): RedirectResponse
    {
        $ticket->update([
            'status' => 'rejected',
        ]);

        return redirect()->back();
    }

    public function completeTask(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'completion_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $task->update([
            'completion_notes' => $validated['completion_notes'],
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->back();
    }

    public function cancelledTask(Task $task): RedirectResponse
    {
        $task->update([
            'status' => 'rejected',
        ]);

        return redirect()->back();
    }
}