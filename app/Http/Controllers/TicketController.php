<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Tickets/Index', [
            'tickets' => Ticket::with('user')->latest()->get(),
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
            'resolution_notes' => $validated['resolution_notes'] ?? null,
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
            'resolution_notes' => $validated['resolution_notes'] ?? null,
        ]);

        return redirect()->route('ticketsIndex');
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $ticket->delete();

        return redirect()->route('ticketsIndex');
    }
}
