<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['tenant', 'user'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(20);

        return view('super-admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['tenant', 'user', 'replies.user']);
        return view('super-admin.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'status' => 'nullable|in:open,answered,resolved,closed',
        ]);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        if ($request->status && $request->status !== $ticket->status) {
            $ticket->update(['status' => $request->status]);
        } else {
            // Auto update to answered if super admin replies
            $ticket->update(['status' => 'answered']);
        }

        return back()->with('success', 'Balasan berhasil dikirim.');
    }
}
