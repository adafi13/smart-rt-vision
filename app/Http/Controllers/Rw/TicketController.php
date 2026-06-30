<?php

namespace App\Http\Controllers\Rw;

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
        $tickets = Ticket::where('rw_id', auth()->user()->rw->id)
            ->with('user')
            ->withCount('replies')
            ->latest()
            ->paginate(15);

        return view('rw.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('rw.tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject'    => 'required|string|max:255',
            'message'    => 'required|string|min:10',
            'priority'   => 'required|in:low,normal,high',
            'category'   => 'required|in:technical,billing,general,feature_request',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip|max:10240',
        ]);

        DB::transaction(function () use ($request) {
            // Generate ticket number (padded by latest ticket id)
            $lastId    = Ticket::max('id') ?? 0;
            $ticketNo  = 'TKT-' . now()->format('Ymd') . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

            $ticket = Ticket::create([
                'tenant_id'     => null,
                'rw_id'         => auth()->user()->rw->id,
                'user_id'       => Auth::id(),
                'ticket_number' => $ticketNo,
                'category'      => $request->category,
                'subject'       => $request->subject,
                'priority'      => $request->priority,
                'status'        => 'open',
            ]);

            // Handle optional attachment
            $attachmentPath = null;
            if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
                $attachmentPath = $request->file('attachment')->store('ticket-attachments', 'public');
            }

            TicketReply::create([
                'ticket_id'      => $ticket->id,
                'user_id'        => Auth::id(),
                'message'        => $request->message,
                'is_staff_reply' => false,
                'attachment'     => $attachmentPath,
            ]);
        });

        return redirect()->route('rw.tickets.index')->with('success', 'Tiket bantuan berhasil dibuat. Tim kami akan segera menghubungi Anda.');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->rw_id !== auth()->user()->rw->id) {
            abort(404);
        }

        $ticket->load(['replies.user']);
        return view('rw.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->rw_id !== auth()->user()->rw->id) {
            abort(404);
        }

        $request->validate([
            'message'    => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip|max:10240',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $attachmentPath = $request->file('attachment')->store('ticket-attachments', 'public');
        }

        TicketReply::create([
            'ticket_id'      => $ticket->id,
            'user_id'        => Auth::id(),
            'message'        => $request->message,
            'is_staff_reply' => false,
            'attachment'     => $attachmentPath,
        ]);

        // Reopen ticket if it was resolved/closed and user replies
        if (in_array($ticket->status, ['resolved', 'closed'])) {
            $ticket->update(['status' => 'open']);
        }

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function close(Ticket $ticket)
    {
        if ($ticket->rw_id !== auth()->user()->rw->id) {
            abort(404);
        }

        $ticket->update([
            'status'      => 'resolved',
            'resolved_at' => now(),
        ]);

        return redirect()->route('rw.tickets.index')
            ->with('success', 'Tiket #' . $ticket->ticket_number . ' telah ditandai sebagai selesai.');
    }
}
