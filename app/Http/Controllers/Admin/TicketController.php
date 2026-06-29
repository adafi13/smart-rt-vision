<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('tenant_id', res_tenant()->id)
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('admin.tickets.index', compact('tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'category' => 'required|in:technical,billing,general,feature_request',
        ]);

        DB::transaction(function () use ($request) {
            $ticket = Ticket::create([
                'tenant_id' => res_tenant()->id,
                'user_id' => Auth::id(),
                'ticket_number' => 'TKT-'.now()->format('Ymd').'-'.str_pad(Ticket::max('id') + 1, 4, '0', STR_PAD_LEFT),
                'category' => $request->category,
                'subject' => $request->subject,
                'priority' => $request->priority,
                'status' => 'open',
            ]);

            TicketReply::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'message' => $request->message,
            ]);
        });

        return redirect()->route('admin.tickets.index')->with('success', 'Tiket bantuan berhasil dibuat.');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->tenant_id !== res_tenant()->id) {
            abort(404);
        }

        $ticket->load(['replies.user']);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->tenant_id !== res_tenant()->id) {
            abort(404);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Auto update to open so super admin knows there's a new reply from tenant
        if ($ticket->status !== 'closed') {
            $ticket->update(['status' => 'open']);
        }

        return back()->with('success', 'Balasan berhasil dikirim.');
    }
}
