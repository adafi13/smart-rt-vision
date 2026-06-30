<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['tenant', 'rw', 'user', 'assignedTo'])->withCount('replies')->latest();

        $search = $request->input('search');
        $status = $request->input('status', 'all');
        $priority = $request->input('priority', 'all');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($priority !== 'all') {
            $query->where('priority', $priority);
        }

        $tickets = $query->paginate(20)->withQueryString();

        $stats = [
            'open' => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::whereIn('status', ['open', 'answered'])->count(), // using answered as in_progress
            'waiting' => Ticket::where('status', 'answered')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
        ];

        return view('super-admin.tickets.index', compact('tickets', 'search', 'status', 'priority', 'stats'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['tenant', 'rw', 'user', 'replies.user', 'assignedTo']);
        // Super admins can assign to any staff
        $admins = User::where('is_super_admin', true)->orWhere('role', 'su')->get();
        return view('super-admin.tickets.show', compact('ticket', 'admins'));
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
            'is_staff_reply' => true,
        ]);

        $updateData = [];
        if ($request->status && $request->status !== $ticket->status) {
            $updateData['status'] = $request->status;
        } else {
            // Auto update to answered if super admin replies and status is waiting_reply or open
            $updateData['status'] = 'answered';
        }
        
        if (!empty($updateData)) {
            $ticket->update($updateData);
        }

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,answered,resolved,closed',
        ]);

        $updateData = ['status' => $request->status];

        if ($request->status === 'resolved' || $request->status === 'closed') {
            $updateData['resolved_at'] = now();
        }

        $ticket->update($updateData);

        return back()->with('success', 'Status tiket berhasil diubah.');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $ticket->update([
            'assigned_to' => Auth::id(),
            'status' => $ticket->status === 'open' ? 'answered' : $ticket->status,
        ]);

        return back()->with('success', 'Tiket berhasil diambil alih.');
    }
}
