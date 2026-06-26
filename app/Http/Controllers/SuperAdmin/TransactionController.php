<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['tenant', 'plan'])->latest();

        if ($search = $request->input('search')) {
            $query->whereHas('tenant', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $transactions = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Subscription::count(),
            'paid' => Subscription::where('status', 'active')->sum('amount'),
            'pending' => Subscription::where('status', 'pending_payment')->count(),
        ];

        return view('super-admin.transactions.index', compact('transactions', 'stats'));
    }
}
