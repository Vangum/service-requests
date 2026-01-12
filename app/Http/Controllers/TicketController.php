<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = auth()->user()->tickets()
            ->with('comments')
            ->latest()
            ->get()
            ->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'title' => $ticket->reason,
                    'reason' => $ticket->reason,
                    'location' => $ticket->location,
                    'status' => $ticket->status,
                    'resolution_notes' => $ticket->completion_notes,
                    'comment' => $ticket->comments()->first()?->comment,
                    'created_at' => $ticket->created_at,
                ];
            });

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Tickets/Create', []);
    }

    public function store(TicketRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Ticket::create([
            'user_id' => auth()->id(),
            'reason' => $validated['reason'],
            'location' => $validated['location'],
        ]);

        return redirect()->route('ticketsIndex');
    }

    public function edit(Ticket $ticket)
    {
        return Inertia::render('Tickets/Edit', [
            'ticket' => $ticket,
        ]);
    }

    public function update(TicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validated();

        $ticket->update([
            'reason' => $validated['reason'],
            'location' => $validated['location'],
        ]);

        return redirect()->route('ticketsIndex');
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $ticket->delete();

        return redirect()->route('ticketsIndex');
    }

    public function addComment(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'comment' => ['nullable', 'string', 'max:255'],
        ]);

        $existingComment = $ticket->comments()
            ->where('user_id', auth()->id())
            ->first();

        if ($existingComment) {
            $existingComment->update(['comment' => $validated['comment']]);
        } else {
            $ticket->comments()->create([
                'user_id' => auth()->id(),
                'comment' => $validated['comment'],
            ]);
        }
        return back();
    }
}
