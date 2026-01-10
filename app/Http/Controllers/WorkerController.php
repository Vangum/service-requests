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

        $tickets = Ticket::with('user')->latest()->get();

        $tasks = Task::with('assignee:id,name')->get();

        return Inertia::render('Worker/Index', [
            'tickets' => $tickets,
            'tasks' => $tasks,
        ]);
    }

    public function completeTicket(Request $request, Ticket $ticket): RedirectResponse
    {
        $ticket->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->back();
    }

    public function closedTicket(Request $request, Ticket $ticket): RedirectResponse
    {
        $ticket->update([
            'status' => 'closed',
        ]);

        return redirect()->back();
    }

    public function completeTask(Task $task): RedirectResponse
    {
        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->back();
    }

    public function cancelledTask(Task $task): RedirectResponse
    {
        $task->update([
            'status' => 'cancelled',
        ]);

        return redirect()->back();
    }
}
